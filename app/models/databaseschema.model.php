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
 * Represents a database.
 * 
 * @package mysqlgendoc\models
 * @autor Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */
class DatabaseSchemaModel extends DocumentationModel
{
    /**
     * @var string Database name.
     * @access public
     */
    public $name;
    
    /**
     * @var string Database collation.
     * @access public
     */
    public $collation;
    
    /**
     * @var array|TableModel Array with tables.
     * @access private
     */
    private $tables;
    
    /**
     * Initializes controller to the model.
     * 
     * @param Controller $controller
     * @access public
     */
    public function __construct( &$controller = null ) 
    { parent::__construct ($controller); }
        
    /**
     * Gets a table object by your index inside tables array.
     * 
     * @param int $index Index of table object inside tables array.
     * @return TableModel Table object.
     * @access public
     */
    public function get_table ( $index )
    { return $this->tables[$index]; }
    
    /**
     * Find database data.
     * 
     * @param mysqli $mysqli Database connection.
     * @param string $database_name Database name.
     * @access public
     */
    public function import_db ( $database_name )
    {
        // Sets database name
        $this->name = $database_name;
        
        // Executes query to get database collation
        /** @var mysqli_stmt $stmt Prepared Statment */
        $stmt = $this->controller->mysqli->prepare( $this->sql_database() );
                            
        if ( $stmt ) 
        {                        
            $stmt->bind_param('s', $database_name);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result( $database_collation );
            
            while ( $stmt->fetch() )
            { $this->collation = $database_collation; }
            
            $this->fromdb( $this->sql_tables() , $this->tables , true );
        
            $stmt->close();
        }
        else
        { $this->controller->set_error(translate ( AppLocale::TYPE_PROCESS, "noquery", false ) . $this->controller->mysqli->error); }
    }
        
    /**
     * Sets the parameters of a prepared statement to get table data.
     * Data to find a table: database_name
     * 
     * @param mysqli_stmt $stmt Prepared statement (by reference).
     * @access public
     */
    public function bind_param ( &$stmt )
    { 
        $stmt->bind_param('s', $this->name);
    }
        
    /**
     * Adds a new table to this database.
     * 
     * @param type $stmt Prepared statement (by reference).
     * @param type $current_process Number of process.
     * @param type $process_size Total of processes.
     * @access public
     */
    public function add ( &$stmt, $current_process, $process_size )
    {
        $stmt->bind_result( $table_name, $table_collation, $table_engine );
        
        $adding = translate ( AppLocale::TYPE_PROCESS, "adding", false );
        $of     = translate ( AppLocale::TYPE_PROCESS, "of", false );
        $data   = translate ( AppLocale::TYPE_PROCESS, "tables", false );
        
        while ( $stmt->fetch() )
        {
            $this->controller->write_update_progress($adding.($current_process+1).$of.$process_size.$data.$this->name.".");

            $this->add_table( $table_name, $table_collation, $table_engine );
            $current_process++;
        }
    }
    
    /**
     * Adds a table object into database.
     * 
     * @param mysqli $mysqli Database connection.
     * @param string $name Table name.
     * @param string $collation Table collation.
     * @param string $engine Table engine.
     * @access private
     */
    private function add_table ( $name, $collation, $engine )
    { 
        /** @var TableModel $table Table */
        $table = new TableModel($this->controller);
        
        // Sets table data
        $table->database_name = $this->name;
        $table->name          = $name;
        $table->collation     = $collation;
        $table->engine        = $engine;
                
        // Find table columns
        $table->import_db();
                
        // Adds table to array
        $this->tables[] = $table;
    }
        
    /**
     * Gets database data from a JSON associative array.
     * 
     * @param array $json JSON associative array.
     * @access public
     */
    public function import_json ( $json )
    {
        // Sets database name
        $this->name      = $json["name"];
        $this->collation = $json["collation"];
       
        $this->import_documentation_json( $json );  
        
        $this->import_tables_json( $json );
    }
    
    /**
     * Gets all tables inside a JSON associative array.
     * 
     * @param array $json JSON associative array.
     * @access public
     */
    private function import_tables_json ( $json )
    {        
        if ( isset ( $json["tables"] ) )
        {
            $this->tables = array();
            
            foreach ( $json["tables"] as $table_ )
            {
                /** @var TableModel $table Table */
                $table = new TableModel($this->controller);

                // Sets table data
                $table->name      = $table_["name"];
                $table->collation = $table_["collation"];
                $table->engine    = $table_["engine"];
                
                $table->import_documentation_json( $table_ );
                
                // Find table columns
                $table->import_json( $table_ );

                // Adds table to array
                $this->tables[] = $table;
            }
        }
    }
    
    /**
     * Exports database in an associative array version to save in JSON.
     * 
     * @return array Database in an associative array format.
     * @access public
     */
    public function export_array ()
    {
        $database = array();
        
        $database["name"]      = $this->name;
        $database["collation"] = $this->collation;
        
        if ( isset ( $this->tables ) )
        {
            foreach ( $this->tables as $table )
            { $database["tables"][] = $table->export_array(); }
        }
        
        $this->export_doc_array($database);
        
        return $database;        
    }
    
    /**
     * Loads the form to fill with documentation data and shows all object informations.
     * 
     * @access public
     */
    public function load_form ()
    {        
        $this->controller->components->documentation_open_panel(
                translate ( AppLocale::TYPE_DOCUMENTATION, "database", false ), 
                $this->name, 
                $this->controller->components::MULTIPLE_PANELS, 
                "red");
        
        $this->controller->components->tag($this->collation, "red");
        $this->load_documentation_form(0);
        
        if ( isset ( $this->tables ) )
        {
            $i = 0;
            foreach ( $this->tables as $table )
            { 
                $table->load_form($i); 
                $i++;
            }
        }
        
        $this->controller->components->documentation_close_panel();  
    }
    
    /**
     * Exports database in HTML version with documentation.
     * 
     * @access public
     */
    public function export_html ()
    {        
        $name = $this->get_doc($this::DOC_NAME, translate ( AppLocale::TYPE_DOCUMENTATION, "database", false ));
        
        echo "<h2>$name</h2>";
        
        $this->controller->components->documentation_open_panel(
                translate ( AppLocale::TYPE_DOCUMENTATION, "database", false ), 
                $this->name, 
                $this->controller->components::MULTIPLE_PANELS, 
                "red");
        
        $this->controller->components->tag($this->collation, "red");
        
        $this->controller->components->divider();
        
        $this->get_doc($this::DOC_COMM);
        
        $this->controller->components->documentation_close_panel();
                        
        if ( isset ( $this->tables ) )
        {
            $i = 0;
            foreach ( $this->tables as $table )
            { 
                $table->export_html($i); 
                $i++;
            }
        } 
    }
    
    /**
     * Query to get database informations.
     * 
     * @return string SQL query.
     * @access private
     */
    private function sql_database ()
    {
return <<<'EOT'
select default_collation_name
  from information_schema.schemata
 where schema_name = ?
EOT;
    }
    
    /**
     * Query to get all tables for database.
     * 
     * @return string SQL query.
     * @access private
     */
    private function sql_tables ()
    {
return <<<'EOT'
    select table_name, table_collation, engine
      from information_schema.tables
     where table_schema = ?
  order by table_name
EOT;
    }
}