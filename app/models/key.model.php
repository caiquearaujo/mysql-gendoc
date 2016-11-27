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
 * Represents a column key.
 * 
 * @package mysqlgendoc\models
 * @autor Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */
class KeyModel extends DocumentationModel
{
    /**
     * Key types
     */
    const PRIMARY = "PRIMARY";
    const UNIQUE  = "UNIQUE";
    const INDEX   = "INDEX";
    const FOREIGN = "FOREIGN";
    
    /**
     * @var string Key name. 
     * @access public
     */
    public $name;
    
    /**
     * @var string Key type. 
     * @access public
     */
    public $type;
    
    /**
     * @var ForeignModel Specific data if it's a foreign key type. 
     * @access public
     */
    public $foreign;
    
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
     * Exports key in an associative array version to save in JSON.
     * 
     * @return array Key in an associative array format.
     * @access public
     */
    public function export_array ()
    {
        $key = array();
        
        $key["name"] = $this->name;
        $key["type"] = $this->type;
        
        if ( $this->type === $this::FOREIGN )
        { $this->foreign->export_foreignkey_array( $key ); }

        $this->export_doc_array( $key );
        
        return $key;
    }
    
    /**
     * Loads the form to fill with documentation data and shows all object informations.
     * 
     * @access public
     */
    public function load_form ( $t, $c, $k, $parent )
    {
        $this->controller->components->documentation_open_panel(
                translate ( AppLocale::TYPE_DOCUMENTATION, "key", false ), 
                $this->type, 
                $this->controller->components::UNIQUE_PANEL, 
                "orange");
        
        $this->controller->components->label("{column} ".$parent, "green");
        
        if ( $this->name != null )
        { $this->controller->components->tag($this->name, "orange"); }
                
        if ( $this->type === $this::FOREIGN )
        { 
            $this->controller->components->divider();
            
            $this->controller->components->tag("Foreign with", "black");
            $fk = $this->foreign->with_table." > <em>".$this->foreign->with_column."</em>";
            $this->controller->components->label($fk, "blue");
            
            $this->controller->components->divider();
            
            $this->controller->components->tag("ON UPDATE ".$this->foreign->on_update, "black");
            $this->controller->components->tag("ON DELETE ".$this->foreign->on_delete, "black");            
        }
        
        $params = "data-table=\"".$t."\" data-column=\"".$c."\""."\" data-key=\"".$k."\"";
        $this->load_documentation_form($t.$c.$k, $params);
        
        $this->controller->components->documentation_close_panel(); 
    }
    
    /**
     * Exports only key that has documentation comment in HTML version with documentation.
     * 
     * @access public
     */
    public function export_html ( $column )
    {
        if ( $this->has_name() || 
                $this->has_comment() || 
                    $this->type === $this::FOREIGN ||
                        $this->name !== null )
        {
            $this->controller->components->documentation_open_line();
            $this->controller->components->documentation_open_column();

            $this->key_code(); 

            $this->controller->components->documentation_close_column();
            $this->controller->components->documentation_open_column();

            echo "<em>{".$column."}</em> ";

            if ( $this->name != null )
            { echo $this->name; }

            $this->controller->components->documentation_close_column();
            $this->controller->components->documentation_open_column();

            $this->get_doc($this::DOC_COMM);

            if ( $this->type === $this::FOREIGN )
            { 
                $this->controller->components->divider();

                $this->controller->components->tag(translate ( AppLocale::TYPE_DOCUMENTATION, "foreign", false ), "black");
                $fk = $this->foreign->with_table." > <em>".$this->foreign->with_column."</em>";
                $this->controller->components->label($fk, "blue");

                $this->controller->components->divider();

                $this->controller->components->tag("ON UPDATE ".$this->foreign->on_update, "black");
                $this->controller->components->tag("ON DELETE ".$this->foreign->on_delete, "black");            
            }

            $this->controller->components->documentation_close_column();
            $this->controller->components->documentation_close_line();    
        }
    }
    
    /**
     * A key can be PRIMARY, UNIQUE, INDEX or FOREIGN. Returns a label component
     * with two characters code, such as: PK, UK, IK or FK.
     * 
     * @return string Label with code for key.
     * @access public
     */
    public function key_code ()
    {
        if ( $this->type === $this::PRIMARY )
        { $this->controller->components->label ( "PK", "orange" ); }
        if ( $this->type === $this::UNIQUE )
        { $this->controller->components->label ( "UK", "brown" ); }
        if ( $this->type === $this::INDEX )
        { $this->controller->components->label ( "IX", "blue" ); }
        if ( $this->type === $this::FOREIGN )
        { $this->controller->components->label ( "FK", "violet" ); }
    }
}