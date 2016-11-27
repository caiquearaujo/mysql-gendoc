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
 * Represents specific data for objects (database, tables, columns, key) documentations.
 * 
 * @package mysqlgendoc\models
 * @autor Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */
class DocumentationModel extends FromDBModel
{
    /**
     * Type of documentation.
     */
    const DOC_NAME = 0;
    const DOC_COMM = 1;
    
    /**
     * @var string Contextual name for object. 
     * @access private
     */
    private $doc_name;
    
    /**
     * @var string Comment for object. 
     * @access private
     */
    private $doc_comm;
    
    /**
     * Initializes controller to the model.
     * 
     * @param Controller $controller
     * @access protected
     */
    protected function __construct( &$controller = null ) 
    { parent::__construct( $controller ); }
    
    /**
     * Adds the documentation to object.
     * 
     * @param string $name Contextual name for object. 
     * @param string $comment Comment for object. 
     * @access public
     */
    public function add_documentation ( $name, $comment )
    {
        if ( $name !== "" )
        { $this->doc_name = $name; }
        
        if ( $comment !== "" )
        { $this->doc_comm = $comment; }
    }
    
    /**
     * Tries to get a documentation from an item JSON associative array.
     * 
     * @param array $json Item from a JSON associative array.
     * @access protected
     */
    protected function import_documentation_json ( $json )
    {
        if ( isset ( $json["doc_name"] ) )
        { $this->doc_name = $json["doc_name"]; }
        
        if ( isset ( $json["doc_comm"] ) )
        { $this->doc_comm = $json["doc_comm"]; }
    }
    
    /**
     * Export the documentation to an array object, if documentation exists.
     * 
     * @param array $array Array to fill (by reference).
     * @access protected
     */
    /**
     * Exports documentation in an associative array version to save in JSON,
     * if it exists.
     * 
     * @return array Associative array with documentation data..
     * @access protected
     */
    protected function export_doc_array ( &$array )
    {
        if ( isset ( $this->doc_name ) )
        { $array["doc_name"] = $this->doc_name; }
        
        if ( isset ( $this->doc_comm ) )
        { $array["doc_comm"] = $this->doc_comm; }
    }
    
    /**
     * Loads the form to fill with documentation data.
     * 
     * @access protected
     */
    protected function load_documentation_form ( $id , $params = null )
    {
        $doc_name = null;
        $doc_comm = null;
        
        if ( isset ( $this->doc_name ) )
        { $doc_name = $this->doc_name; }
        
        if ( isset ( $this->doc_comm ) )
        { $doc_comm = $this->doc_comm; }
        
        $this->controller->components->documentation_form($id, $params, $doc_name, $doc_comm);
    }
    
    /**
     * Returns documentation name or documentation comment to show.
     * If it requires documentation name, return the name or the default value $return
     * if name is not setted.
     * If it requires documentation comment, return the HTML format to comment if it is setted.
     * 
     * @param const $type Type of data to get.
     * @param string $return Default value to return, if documentation name is not setted.
     * @return string|boolean Return the documentation name or the documentation comment in HTML format.
     * @access protected
     */
    protected function get_doc ( $type, $return = true )
    {
        switch ( $type )
        {
            case $this::DOC_NAME:
                return isset ( $this->doc_name ) ? $this->doc_name : $return;
            case $this::DOC_COMM:
                $this->html_comment();
        }
        
        return $return;
    }
    
    /**
     * Returns if has or not a documentation comment.
     * 
     * @return boolean
     * @access protected
     */
    protected function has_comment ()
    { return isset ( $this->doc_comm ) ? true : false; }
    
    /**
     * Returns if has or not a documentation name.
     * 
     * @return boolean
     * @access protected
     */
    protected function has_name ()
    { return isset ( $this->doc_name ) ? true : false; }

    /**
     * Shows documentation comment in HTML format if it exists.
     * 
     * @access private
     */
    private function html_comment ()
    {
        if ( isset ( $this->doc_comm ) )
        { echo "<p class=\"comment\">{$this->doc_comm}</p>"; }
    }
}

