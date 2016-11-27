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
 * Represents a table column.
 * 
 * @package mysqlgendoc\models
 * @autor Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */
class ColumnModel extends DocumentationModel
{
    /**
     * Data that is being searched.
     */
    const SEARCH_KEYS     = 0;
    const SEARCH_FOREIGNS = 1;
    
    /**
     * @var int Type of data searching. 
     * @access private
     */
    private $searching;
    
    /**
     * @var string Column name. 
     * @access public
     */
    public $name;
    
    /**
     * @var string Column data type. 
     * @access public
     */
    public $type;
    
    /**
     * @var string Column additional data type information. 
     * @access public
     */
    public $extra;
    
    /**
     * @var string Default value for column. 
     * @access public
     */
    public $default_value;
    
    /**
     * @var boolean Nullable state for column. 
     * @access public
     */
    public $null;
    
    /**
     * @var string Comment for column. 
     * @access public
     */
    public $comment;
    
    /**
     * @var array|KeyModel Array with column keys. 
     * @access public
     */
    public $keys;
    
    /**
     * Initializes controller to the model.
     * 
     * @param Controller $controller
     * @access public
     */
    public function __construct( &$controller = null ) 
    { parent::__construct ($controller); }
        
    /**
     * Gets a key object by your index inside keys array.
     * 
     * @param int $index Index of key object inside keys array.
     * @return KeyModel Key object.
     * @access public
     */
    public function get_key ( $index )
    { return $this->keys[$index]; }
    
    /**
     * Find all column keys in the database schema.
     * 
     * @param mysqli $mysqli Database connection.
     * @param string $database_name Database name.
     * @param string $table_name Table name.
     * @access public
     */
    public function import_db ()
    {        
        $this->searching = $this::SEARCH_KEYS;
        $this->fromdb ( $this->sql_keys(), $this->keys );
        
        $this->searching = $this::SEARCH_FOREIGNS;
        $this->fromdb ( $this->sql_foreign_keys(), $this->keys );
    }
    
    /**
     * Sets the parameters of a prepared statement to get column keys.
     * Data to find a key: database_name, table_name, column_name
     * 
     * @param mysqli_stmt $stmt Prepared statement (by reference).
     * @access public
     */
    public function bind_param ( &$stmt )
    { $stmt->bind_param('sss', $this->database_name, $this->table_name, $this->name); }
   
    /**
     * Adds a new key to this table.
     * 
     * @param type $stmt Prepared statement (by reference).
     * @param type $current_process Number of process.
     * @param type $process_size Total of processes.
     * @access public
     */
    public function add ( &$stmt, $current_process, $process_size )
    {
        if ( $this->searching === $this::SEARCH_KEYS )
        { 
            $stmt->bind_result( $key_index, $key_unique ); 
            
            while ( $stmt->fetch() )
            {
                $this->controller->update_processes();
                $this->add_key( $key_index, $key_unique );
            }
        }
        else
        { 
            $stmt->bind_result( $name, $with_table, $with_column, $update, $delete ); 
        
            while ( $stmt->fetch() )
            {
                $this->controller->update_processes();
                $this->add_foreign_key( $name, $with_table, $with_column, $update, $delete );
            }
        }
    }
        
    /**
     * Adds a key object into column keys.
     * 
     * @param string $key_index Key index type or name.
     * @param int $key_unique Key unique state.
     * @access private
     */
    private function add_key ( $key_index, $key_unique )
    { 
        /** @var KeyModel $key Key */
        $key = new KeyModel ( $this->controller );
        // Sets key name as null
        $key_name = null;
        
        // Sets key index type:
        // 1. If it's a primary key... then it's PRIMARY
        // 2. If it's not a primary key, but it's unique... then it's UNIQUE
        //    and it can have a key name.
        // 3. If it's not all cases above... then it's INDEX
        //    and it can have a key name.
        if ( $key_index == "PRIMARY" )
        { $key_index = $key::PRIMARY; }
        else if ( $key_index !== "PRIMARY" && $key_unique == 0 )
        { 
            $key_name  = $key_index;
            $key_index = $key::UNIQUE;
        }
        else
        { 
            $key_name  = $key_index;
            $key_index = $key::INDEX;             
        }   
        
        // Sets key data
        $key->name = $key_name;
        $key->type = $key_index;
        
        // Adds key to array
        $this->keys[] = $key;
    }
    
    /**
     * Adds a foreign key object into column keys.
     * 
     * @param string $name
     * @param string $with_table
     * @param string $with_column
     * @param string $update
     * @param string $delete
     * @access private
     */
    private function add_foreign_key ( $name, $with_table, $with_column, $update, $delete )
    { 
        /** @var KeyModel $key Key */
        $key = new KeyModel ( $this->controller );
        
        // Sets key data
        $key->name = $name;
        $key->type = $key::FOREIGN;
        // Sets foreign key data
        $key->foreign              = new ForeignModel();
        $key->foreign->with_table  = $with_table;
        $key->foreign->with_column = $with_column;
        $key->foreign->on_update   = $update;
        $key->foreign->on_delete   = $delete;
                
        // Adds key to array
        $this->keys[] = $key;
    }
    
    /**
     * Gets all keys inside an item JSON associative array.
     * 
     * @param array $column Column from a JSON associative array.
     * @access public
     */
    public function import_json ( $column )
    { 
        if ( isset ( $column["keys"] ) )
        {
            $this->keys = array();
            
            foreach ( $column["keys"] as $key_ )
            {
                /** @var KeyModel $key Key */
                $key = new KeyModel ( $this->controller );
                
                $key->name = $key_["name"];
                $key->type = $key_["type"];
                
                $key->import_documentation_json( $key_ );
                
                if ( $key->type === $key::FOREIGN )
                {
                    $key->foreign              = new ForeignModel();
                    $key->foreign->with_table  = $key_["with_table"];
                    $key->foreign->with_column = $key_["with_column"];
                    $key->foreign->on_update   = $key_["on_update"];
                    $key->foreign->on_delete   = $key_["on_delete"];
                }

                // Adds key to array
                $this->keys[] = $key;
            }
        }
    }
    
    /**
     * Exports column in an associative array version to save in JSON.
     * 
     * @return array Column in an associative array format.
     * @access public
     */
    public function export_array ()
    {
        $column = array();
        
        $column["name"] = $this->name;
        $column["type"] = $this->type;
        $column["null"] = $this->null;
        
        if ( isset ( $this->extra ) )
        { $column["extra"] = $this->extra; }
        
        if ( isset ( $this->default_value ) )
        { $column["default_value"] = $this->default_value; }
        
        if ( isset ( $this->comment ) )
        { $column["comment"] = $this->comment; }
                
        if ( isset ( $this->keys ) )
        {
            foreach ( $this->keys as $key )
            { $column["keys"][] = $key->export_array(); }
        }
        
        $this->export_doc_array($column);
        
        return $column;        
    }
    
    /**
     * Loads the form to fill with documentation data and shows all object informations.
     * 
     * @access public
     */
    public function load_form ( $t, $c, $parent )
    {
        $this->controller->components->documentation_open_panel(translate ( AppLocale::TYPE_DOCUMENTATION, "column", false ), $this->name, "segments", "green");     
        $this->controller->components->label("{table} ".$parent, "blue");
        
        if ( isset ( $this->extra ) )
        { $this->type .= " ".$this->extra; }
        
        $this->controller->components->tag($this->type, "green");
        
        if ( isset ( $this->default_value ) )
        { $this->controller->components->tag("DEFAULT ".$this->default_value, "teal"); }
        
        if ( $this->null )
        { $this->controller->components->tag("NULL", "red"); }
        
        if ( isset ( $this->comment ) )
        { $this->controller->components->tag($this->comment, "grey"); }
        
        $params = "data-table=\"".$t."\" data-column=\"".$c."\"";
        $this->load_documentation_form($t.$c, $params);
        
        if ( isset ( $this->keys ) )
        {
            $k = 0;
            
            foreach ( $this->keys as $key )
            { 
                $key->load_form ( $t, $c, $k, $this->name ); 
                $k++;
            }
        }
        
        $this->controller->components->documentation_close_panel(); 
    }
    
    /**
     * Exports column in HTML version with documentation.
     * 
     * @access public
     */
    public function export_html ()
    {        
        $this->controller->components->documentation_open_line();
        $this->controller->components->documentation_open_column();
                
        if ( isset ( $this->keys ) )
        {            
            foreach ( $this->keys as $key )
            { $key->key_code(); }
        }
        
        if ( $this->null )
        { $this->controller->components->label("NULL", "red"); }
        
        $this->controller->components->documentation_close_column();
        $this->controller->components->documentation_open_column();
        
        echo "<strong>".$this->name."</strong>";
        $c_name = $this->get_doc($this::DOC_NAME, null);
        
        if ( $c_name !== null )
        { echo " {<em>".$c_name."</em>}"; }
        
        $this->controller->components->divider(); 
        
        if ( isset ( $this->extra ) )
        { $this->type .= " ".$this->extra; }
        
        echo "<em>".$this->type."</em>";
        
        $this->controller->components->documentation_close_column();
        $this->controller->components->documentation_open_column();
        
        $this->get_doc($this::DOC_COMM);
        
        if ( isset ( $this->default_value ) )
        { $this->controller->components->divider(); $this->controller->components->tag("DEFAULT ".$this->default_value, "teal"); }
                
        if ( isset ( $this->comment ) )
        { $this->controller->components->divider(); $this->controller->components->tag($this->comment, "grey"); }
        
        $this->controller->components->documentation_close_column();
        $this->controller->components->documentation_close_line();
    }
    
    /**
     * Exports column keys in HTML version with documentation.
     * 
     * @access public
     */
    public function export_html_keys ()
    {                        
        if ( isset ( $this->keys ) )
        {            
            foreach ( $this->keys as $key )
            { $key->export_html( $this->name ); }            
        }
        
    }
        
    /**
     * Query to get all PRIMARY, UNIQUE and INDEX (KEY) keys for column.
     * 
     * @return string SQL query.
     * @access private
     */
    private function sql_keys ()
    {
return <<<'EOT'
    select index_name, non_unique
      from information_schema.statistics
     where table_schema = ? and table_name = ? and column_name = ?
  order by table_name, seq_in_index
EOT;
    }
    
    /**
     * Query to get all FOREIGN keys for column.
     * 
     * @return string SQL query.
     * @access private
     */
    private function sql_foreign_keys ()
    {
 return <<<'EOT'
    select k.constraint_name, k.referenced_table_name, k.referenced_column_name, 
	            r.update_rule, r.delete_rule
      from information_schema.key_column_usage k
inner join information_schema.referential_constraints r 
                on r.constraint_name = k.constraint_name 
               and r.constraint_schema = k.constraint_schema 
               and r.table_name = k.table_name
     where k.constraint_schema = ? and k.table_name = ? and k.column_name = ?
EOT;
    }
}