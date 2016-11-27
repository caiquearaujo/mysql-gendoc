<?php

/** Prevents users from accessing file directly. */
if ( !defined("ABSPATH") ) 
{ exit ( header( "Location: " . HOME_URI . "/404" ) ); }

/* 
 * Copyright (C) 2016 Caique M Araujo
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Represents a table.
 * 
 * @package mysqlgendoc\models
 * @autor Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */
class TableModel extends DocumentationModel
{    
    /**
     * @var string Table name.
     * @access public
     */
    public $name;
    
    /**
     * @var string Table engine.
     * @access public
     */
    public $engine;
    
    /**
     * @var string Table collation.
     * @access public
     */
    public $collation;
    
    /**
     * @var array|ColumnModel Array with table columns.
     * @access private
     */
    private $columns;
    
    /**
     * Initializes controller to the model.
     * 
     * @param Controller $controller
     * @access public
     */
    public function __construct( &$controller = null ) 
    {
        parent::__construct ($controller);
    }
    
    /**
     * Gets a column object by your index inside columns array.
     * 
     * @param int $index Index of column object inside columns array.
     * @return ColumnModel Column object.
     * @access public
     */
    public function get_column ( $index )
    { return $this->columns[$index]; }
    
    /**
     * Find all table columns in the database schema.
     * 
     * @access public
     */
    public function import_db ()
    { $this->fromdb( $this->sql_columns(), $this->columns ); }
    
    /**
     * Sets the parameters of a prepared statement to get table columns.
     * Data to find a column: database_name, table_name
     * 
     * @param mysqli_stmt $stmt Prepared statement (by reference).
     * @access public
     */
    public function bind_param ( &$stmt )
    {
        $stmt->bind_param('ss', $this->database_name, $this->name);
    }
        
    /**
     * Adds a new column to this table.
     * 
     * @param type $stmt Prepared statement (by reference).
     * @param type $current_process Number of process.
     * @param type $process_size Total of processes.
     * @access public
     */
    public function add ( &$stmt, $current_process, $process_size )
    {
        $stmt->bind_result( $column_name, $column_default, $column_nullable,
                                    $column_type, $column_extra, $column_comment );
        
        $adding = translate ( AppLocale::TYPE_PROCESS, "adding", false );
        $of     = translate ( AppLocale::TYPE_PROCESS, "of", false );
        $data   = translate ( AppLocale::TYPE_PROCESS, "columns", false );
        
        while ( $stmt->fetch() )
        {
            $this->controller->write_update_progress($adding.($current_process+1).$of.$process_size.$data.$this->name.".");

            $this->add_column( $column_name, $column_default, $column_nullable,
                                    $column_type, $column_extra, $column_comment );
            $current_process++;
        }
    }
    
    /**
     * Adds a column object into table columns.
     * 
     * @param mysqli $mysqli Database connection.
     * @param string $database_name Database name.
     * @param string $name Column name.
     * @param string $default Column default value.
     * @param string $nullable Column nullable state.
     * @param string $type Column data type.
     * @param string $extra Column additional data type information. 
     * @param string $comment Column comment.
     * @access private
     */
    private function add_column ( $name, $default, $nullable,
                                        $type, $extra, $comment )
    { 
        /** @var ColumnModel $column Column */
        $column = new ColumnModel($this->controller);
        
        // Sets column data
        $column->database_name = $this->database_name;
        $column->table_name    = $this->name;
        $column->name          = $name;
        $column->type          = $type;
        
        if ( $nullable === "NO" )
        { $column->null = false; }
        else
        { $column->null = true; }
        
        if ( $default !== "" && $default !== null )
        { $column->default_value = $default; }
        
        if ( $extra !== "" && $extra !== null )
        { $column->extra = $extra; }
        
        if ( $comment !== "" && $comment !== null )
        { $column->comment = utf8_encode ( $comment ); }
        
        // Find column keys
        $column->import_db();
                
        // Adds column to array
        $this->columns[] = $column;
    }
    
    /**
     * Exports table in an associative array version to save in JSON.
     * 
     * @return array Table in an associative array format.
     * @access public
     */
    public function export_array ()
    {
        $table = array();
        
        $table["name"]      = $this->name;
        $table["engine"]    = $this->engine;
        $table["collation"] = $this->collation;
                
        if ( isset ( $this->columns ) )
        {
            foreach ( $this->columns as $column )
            { $table["columns"][] = $column->export_array(); }
        }
        
        $this->export_doc_array($table);
        
        return $table;        
    }
    
    /**
     * Loads the form to fill with documentation data and shows all object informations.
     * 
     * @access public
     */
    public function load_form ( $t )
    {
        $this->controller->components->documentation_open_panel(
                translate ( AppLocale::TYPE_DOCUMENTATION, "table", false ), 
                $this->name, 
                $this->controller->components::MULTIPLE_PANELS, 
                "blue");
        
        $this->controller->components->tag($this->engine, "red");
        $this->controller->components->tag($this->collation, "red");
        
        $params = "data-table=\"".$t."\"";
        $this->load_documentation_form($t, $params);
        
        if ( isset ( $this->columns ) )
        {
            $c = 0;
            foreach ( $this->columns as $column )
            { 
                $column->load_form($t, $c, $this->name); 
                $c++;
            }
        }
        
        $this->controller->components->documentation_close_panel(); 
    }
    
    /**
     * Exports table in HTML version with documentation.
     * 
     * @param int $i Table number.
     * @access public
     */
    public function export_html ( $i )
    {        
        $name = $this->get_doc($this::DOC_NAME, translate ( AppLocale::TYPE_DOCUMENTATION, "table", false ));
        
        echo "<h3>".($i+1).". $name</h3>";
        
        $this->controller->components->documentation_open_panel(
                translate ( AppLocale::TYPE_DOCUMENTATION, "table", false ), 
                $this->name, 
                $this->controller->components::MULTIPLE_PANELS, 
                "red");
        
        $this->controller->components->tag($this->engine, "red");
        $this->controller->components->tag($this->collation, "red");
        
        $this->controller->components->divider();
        
        $this->get_doc($this::DOC_COMM);
                
        echo "<h4>Columns</h4>";
        $this->controller->components->documentation_open_table();   
        
        if ( isset ( $this->columns ) )
        {
            foreach ( $this->columns as $column )
            { $column->export_html(); }
        } 
        $this->controller->components->documentation_close_table();
        
        if ( $this->has_keys_todocument() )
        {
            echo "<h4>Keys</h4>";
            $this->controller->components->documentation_open_table();   

            if ( isset ( $this->columns ) )
            {
                foreach ( $this->columns as $column )
                { $column->export_html_keys(); }
            } 
            
            $this->controller->components->documentation_close_table();
        }
        
        $this->controller->components->documentation_close_panel();
    }
    
    /**
     * Gets all columns inside an item JSON associative array.
     * 
     * @param array $table Table from a JSON associative array.
     * @access public
     */
    public function import_json ( $table )
    { 
        if ( isset ( $table["columns"] ) )
        {
            $this->columns = array();
            
            foreach ( $table["columns"] as $column_ )
            {
                /** @var ColumnModel $column Column */
                $column = new ColumnModel($this->controller);

                // Sets column data
                $column->name = $column_["name"];
                $column->type = $column_["type"];
                
                $column->import_documentation_json( $column_ );

                if ( !$column_["null"] )
                { $column->null = false; }
                else
                { $column->null = true; }

                if ( isset( $column_["default_value"] ) )
                { $column->default_value = $column_["default_value"]; }
                
                if ( isset( $column_["extra"] ) )
                { $column->extra = $column_["extra"]; }

                if ( isset( $column_["comment"] ) )
                { $column->comment = $column_["comment"]; }
                
                // Find column keys
                $column->import_json( $column_ );

                // Adds column to array
                $this->columns[] = $column;
            }
        }
    }
    
    /**
     * Verifies if there is keys to show documentation.
     * 
     * @returns boolean
     * @access public
     */
    public function has_keys_todocument ()
    {                        
        if ( isset ( $this->columns ) )
        {
            foreach ( $this->columns as $column )
            { 
                if ( isset ( $column->keys ) )
                {            
                    foreach ( $column->keys as $key )
                    { 
                        if ( $key->has_name() || 
                                $key->has_comment() || 
                                    $key->type === $key::FOREIGN ||
                                        $key->name !== null )
                        { return true; }
                    }            
                }
            }
        }
        return false;
    }
    
    /**
     * Query to get all columns for table.
     * 
     * @return string SQL query.
     * @access private
     */
    private function sql_columns ()
    {
return <<<'EOT'
    select column_name, column_default, is_nullable, 
               column_type, extra, column_comment
      from information_schema.columns
     where table_schema = ? and table_name = ?
  order by ordinal_position
EOT;
    }
}