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
 * Controller to get a JSON database and structure it to a documentation form.
 * 
 * @package mysqlgendoc\controllers
 * @autor Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */
class DocumentationController extends Controller
{
    /**
     * Model for stores database.
     *
     * @var DatabaseSchemaModel 
     * @access private
     */
    private $database_schema;
    
    /**
     * File to be documented.
     *
     * @var string File name. 
     * @access public
     */
    public $database_file;
    
    /**
     * URL of file to be documented.
     *
     * @var string File url. 
     * @access public
     */
    public $database_url;
        
    /**
     * Begins to load a documentation to write. It verifies if file exists and
     * if it is setted in the URL. Then open file and read its data.
     * 
     * @access public
     */
    public function write ()
    {
        $this->start();
        
        // Sets page title and description
        $this->set_page_info( translate ( AppLocale::TYPE_PAGE, "writed_title", false ), 
                                    translate ( AppLocale::TYPE_PAGE, "writed_description", false ));
        
        // Gets header
        $this->get_header();
        
        // Top templete
        $this->get_page("documentation-top");
        
        // If there is a file as parameter
        if ( sizeof ( $this->params ) == 1 )
        {
            $this->database_file = $this->params[0];
            $this->database_url  = UP_PATH."/".$this->database_file;
            
            if ( !file_exists ( $this->database_url ) )
            {
                $this->write_alert(translate ( AppLocale::TYPE_PROCESS, "invalid_file", false ));
                $this->redirect(HOME_URI);
            }
            else
            {
                // Fill page with documentation
                $this->get_database();
                $this->database_schema->load_form();
                // Bottom template
                $this->get_page("documentation-bottom");
            }
        }
        else
        {   
            $this->write_alert(translate ( AppLocale::TYPE_PROCESS, "invalid_file", false ));
            $this->redirect(HOME_URI);
        }
        
        
        $this->get_footer();
        $this->end();
    }
    
    /**
     * Begins to save a documentation. It verifies if file exists and 
     * if it is setted in the URL.
     * 
     * @access public
     */
    public function save ()
    {
        $this->start();
        $this->components->progress_bar();
        
        // If there is a file in parameters
        if ( sizeof ( $this->params ) == 1 )
        {
            $this->database_file = $this->params[0];
            $this->database_url  = UP_PATH."/".$this->database_file;
            
            if ( !file_exists ( $this->database_url ) )
            {
                $this->set_error(translate ( AppLocale::TYPE_PROCESS, "invalid_file", false ));
                $this->write_error();
            }
            else
            {
                $this->save_documentation();
            }
        }
        else
        {   
            $this->set_error(translate ( AppLocale::TYPE_PROCESS, "invalid_file", false ));
            $this->write_error();
        }
                
        $this->end();
    }
    
    /**
     * Opens the database JSON file and saves all of your filled documentation.
     * 
     * @access public
     */
    public function save_documentation ()
    {                
        // Gets database from a file
        $this->get_database();
        // Gets form post
        $json = json_decode( filter_input( INPUT_POST, "data"), true );
        
        // If form post is setted
        if ( $json )
        {
            $this->write_process(translate ( AppLocale::TYPE_PROCESS, "saving_full", false ));

            $this->processes_size  = count( $json["names"] );
            $this->current_process = 0;

            // For each input inside post array,
            // insert data in the right object in database schema.
            for ( $i = 0; $i < count( $json["names"] ); $i++ )
            {
                $this->write_update_progress(translate ( AppLocale::TYPE_PROCESS, "saving", false ).$this->current_process.translate ( AppLocale::TYPE_PROCESS, "of", false ). $this->processes_size.".");

                $temp_name = $json["names"][$i];
                $temp_comm = $json["docs"][$i];

                $name = $temp_name["value"];
                $comm = $temp_comm["value"];

                if ( isset ( $temp_name["table"] ) 
                        && !isset ( $temp_name["column"] ) )
                { 
                    $this->database_schema->
                            get_table($temp_name["table"])->
                            add_documentation($name, $comm);
                }
                else if ( isset ( $temp_name["column"] ) 
                            && !isset ( $temp_name["key"] ) )
                { 
                     $this->database_schema->
                            get_table($temp_name["table"])->
                            get_column($temp_name["column"])->
                            add_documentation($name, $comm);
                }
                else if ( isset ( $temp_name["key"] ) )
                { 
                    $this->database_schema->
                            get_table($temp_name["table"])->
                            get_column($temp_name["column"])->
                            get_key($temp_name["key"])->
                            add_documentation($name, $comm);                    
                }
                else
                { 
                    $this->database_schema->
                            add_documentation($name, $comm);   
                }
            }

            // Save the file
            $database_doc = fopen( $this->database_url, "w");
            fwrite( $database_doc, json_encode( $this->database_schema->export_array() ) );
            
            $this->write_process(translate ( AppLocale::TYPE_PROCESS, "done_file", false ).$this->database_file);

            $this->next_step();
        }
        
        $this->end();
    }
    
    /**
     * Gets the database from a JSON file.
     * 
     * @access public
     */
    private function get_database ()
    {
        $this->database_schema = new DatabaseSchemaModel($this);
        $json_file = file_get_contents( $this->database_url );
        $this->database_schema->import_json( json_decode( $json_file, true ) );
    }
    
    /** 
     * Enables next step button on page.
     * 
     * @access public
     */
    private function next_step ()
    {
        $url = HOME_URI."/documentation/html/".$this->database_file;
        
        echo "<script language=\"javascript\">nextStep(\"$url\");</script>";
        $this->flush();
    }
    
    /**
     * Shows a documentation in HTML format.
     * 
     * @access public
     */
    public function html ()
    {
        $this->start();
        
        // Sets page title and description
        $this->set_page_info( translate ( AppLocale::TYPE_PAGE, "htmld_title", false ), 
                                    translate ( AppLocale::TYPE_PAGE, "htmld_description", false ));
        
        // Gets header
        $this->get_header();
                
        // If there is a file in parameters
        if ( sizeof ( $this->params ) == 1 )
        {
            $this->database_file = $this->params[0];
            $this->database_url  = UP_PATH."/".$this->database_file;
            
            // Top templete
            $this->get_page("documentation-htmlview-top");
            
            if ( !file_exists ( $this->database_url ) )
            {
                $this->write_alert(translate ( AppLocale::TYPE_PROCESS, "invalid_file", false ));
                $this->redirect(HOME_URI);
            }
            else
            {
                // Fill page with documentation
                $this->get_database();
                $this->database_schema->export_html();
            }
        }
        else
        {   
            $this->write_alert(translate ( AppLocale::TYPE_PROCESS, "invalid_file", false ));
            $this->redirect(HOME_URI);
        }
        
        
        $this->get_footer();
        $this->end();
    }
    
    /**
     * Shows a documentation in clen HTML format.
     * 
     * @access public
     */
    public function clean ()
    {
        $this->start();
        
        // Fixes container with no class to expand it (Semantic-UI).
        //$this->container = "";
        // Sets page title and description
        $this->set_page_info( translate ( AppLocale::TYPE_PAGE, "htmld_title", false ), 
                                    translate ( AppLocale::TYPE_PAGE, "htmld_description", false ));
        
        // Gets header without menu and doesn't load a template
        $this->get_header(false);
        $this->components->alert_box(translate ( AppLocale::TYPE_ALERT, "default_title", false ), "", false);
        $this->components->info_box(translate ( AppLocale::TYPE_INFO, "default_title", false ), "", false);
        
        // If there is a file in parameters
        if ( sizeof ( $this->params ) == 1 )
        {
            $this->database_file = $this->params[0];
            $this->database_url  = UP_PATH."/".$this->database_file;
            
            if ( !file_exists ( $this->database_url ) )
            {
                $this->write_alert(translate ( AppLocale::TYPE_PROCESS, "invalid_file", false ));
                $this->redirect(HOME_URI);
            }
            else
            {
                // Fill page with documentation
                $this->get_database();
                $this->database_schema->export_html();
            }
        }
        else
        {   
            $this->write_alert(translate ( AppLocale::TYPE_PROCESS, "invalid_file", false ));
            $this->redirect(HOME_URI);
        }
        
        $this->end();
    }
    
}
