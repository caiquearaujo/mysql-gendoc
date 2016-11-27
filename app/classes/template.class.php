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
 * Responsible to control all templates to show a view correctly.
 * Sets title and description page.
 * Calls header, view template and fotter.
 * Appends css and javascript into a template.
 * Configures main container style for view.
 * Gets 404 error page template.
 * 
 * @package mysqlgendoc\main
 * @autor Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */
class Template extends Status
{    
    /**
     * Page title.
     * 
     * @var string Title.
     * @access private
     */
    private $title;
    
    /**
     * Page description.
     * 
     * @var string Description.
     * @access private
     */
    public $description;
    
    /**
     * Components to show in view.
     * 
     * @var Components Components object.
     * @access public
     */
    public $components;
    
    /**
     * Main container class (Semantic-UI). By default it's "text".
     * 
     * @var string Container class of Semantic-UI.
     * @access public
     */
    public $container;
        
    /**
     * Configures template by setting defaults values.
     * 
     * @param array $params
     * @access public
     */
    public function __construct() 
    {
        $this->title       = translate ( AppLocale::TYPE_PAGE, "default_title", false );
        $this->description = translate ( AppLocale::TYPE_PAGE, "default_description", false );
        $this->components  = new Components();
        $this->container   = "text";
    }
    
    /**
     * Includes header section page.
     * 
     * @param boolean $menu Enable or disable menu (by default: enabled).
     * @access public
     */
    public function get_header ( $menu = true )
    { require ( ABSPATH . "/public/includes/header.php" ); }
    
    /**
     * Load header files in template, such as: css and javacript files.
     * If the controller needs to load a specific file for your page...
     * It has to implement the function import_files and it will be loaded
     * by load_files.
     * 
     * The load_files function is called in /public/includes/header.php
     * 
     * @access public
     */
    public function load_files ()
    {
        $this->import_external_javascript("https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js");
        
        $this->import_css( "libs/semantic-ui", "icon.min" );
        $this->import_css( "libs/semantic-ui", "semantic.min" ); 
        $this->import_css( "css", "main" );
        
        $this->import_javascript( "js", "main" );
        
        if ( method_exists ( $this, "head" ) )
        { $this->head(); }
    }
    
    /**
     * Includes footer section page.
     * 
     * @access public
     */
    public function get_footer ()
    { require ( ABSPATH . "/public/includes/footer.php" ); }
    
    /**
     * Includes, if exists, a view page by your name.
     * The views will be catch at /views/{name}.php
     * 
     * @param $name Name of template file.
     * @access public
     */
    public function get_page ( $name )
    {    
        $file = ABSPATH . "/public/views/" . $name . ".php";
        
        if ( file_exists ( $file ) )
        { require ( ABSPATH . "/public/views/" . $name . ".php" ); }
        else
        { $this->not_found(); }     
    }
        
    /**
     * Gets the page title.
     * 
     * @return string Page title.
     * @access public
     */
    public function get_title ()
    { return $this->title; }
    
    /**
     * Gets the page description.
     * 
     * @return string Page description.
     * @access public
     */
    public function get_description ()
    { return $this->description; }
    
    /**
     * Sets the page title and description.
     * 
     * @param string $title Page title.
     * @param string $description Page description.
     * @access public
     */
    public function set_page_info ( $title , $description )
    {
        $this->title       = $title . " / MySQL-GenDoc";
        $this->description = $description;
    }
        
    /**
    * Imports a css file to page.
    * 
    * @author Caique M Araujo <caique.msn@live.com>
    * @since 1.0.0 Inicial version.
    * @version 1.0.0
    * @param string $library_folder Folder where file is /public/{folder}.
    *                               You don't need to use / at begin or end.
    * @param string $file_name Name of file without extension {file}.css.
    * @acess public
    */
    public function import_css ( $library_folder, $file_name )
    {
       echo "<link href=\"".HOME_URI."/".$library_folder."/".$file_name.".css\" rel=\"stylesheet\" type=\"text/css\"/>\n\t\t";
    }

   /**
    * Imports a external css file to page.
    * 
    * @author Caique M Araujo <caique.msn@live.com>
    * @since 1.0.0 Inicial version.
    * @version 1.0.0
    * @param string $url Full url where file is.
    * @acess public
    */
    public function import_external_css ( $url )
    {
       echo "<link href=\"".$url."\" rel=\"stylesheet\" type=\"text/css\"/>\n\t\t";
    }

   /**
    * Imports a javascript file to page.
    * 
    * @author Caique M Araujo <caique.msn@live.com>
    * @since 1.0.0 Inicial version.
    * @version 1.0.0
    * @param string $library_folder Folder where file is /public/{folder}.
    *                               You don't need to use / at begin or end.
    * @param string $file_name Name of file without extension {file}.js.
    * @acess public
    */
    public function import_javascript ( $library_folder, $file_name )
    {
       echo "<script src=\"".HOME_URI."/".$library_folder."/".$file_name.".js\" type=\"text/javascript\"></script>\n\t\t";
    }

   /**
    * Imports a external javascript file to page.
    * 
    * @author Caique M Araujo <caique.msn@live.com>
    * @since 1.0.0 Inicial version.
    * @version 1.0.0
    * @param string $url Full url where file is.
    * @acess public
    */
    public function import_external_javascript ( $url )
    {
       echo "<script src=\"".$url."\" type=\"text/javascript\"></script>\n\t\t";
    }
    
    /**
     * If there's a not found page, then the application will call this method
     * for show 404 error page.
     * 
     * @access public
     */
    public function not_found ()
    {
        $this->set_page_info( translate ( AppLocale::TYPE_PAGE, "404_title", false ), 
                                    translate ( AppLocale::TYPE_PAGE, "404_description", false ));
        
        $this->get_page( "404" );
    }
}

