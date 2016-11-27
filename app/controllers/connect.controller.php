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
 * Controller to load and read a database to save it inside a JSON file.
 * 
 * @package mysqlgendoc\controllers
 * @autor Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */
class ConnectController extends Controller
{    
    /**
     * Stores database schema.
     * 
     * @var DatabaseModel Database object. 
     * @access private
     */
    private $database_schema;
    
    /**
     * Stores mysqli connection to allows objects use it.
     * 
     * @var mysqli Connection with database. 
     * @access public
     */
    public $mysqli;
        
    /**
     * Default content to show in connection page.
     * Also starts the main function of the controller: 
     * load, read and save a database schema.
     * 
     * @access public
     */
    public function index ()
    {
        // Starts the output buffer
        $this->start();
        
        // Sets page title and description
        $this->set_page_info( translate ( AppLocale::TYPE_PAGE, "connect_title", false ), 
                                    translate ( AppLocale::TYPE_PAGE, "connect_description", false ));
        
        // Shows initial view
        $this->get_header();
        $this->get_page("connect");
        $this->get_footer();
        
        // Flushes the output buffer
        $this->flush();
        // Connects with a database
        $this->connect();
        
        // Disables the outputbuffer
        $this->end();
    }
    
    /**
     * Tries to connect to database. If something is wrong alert user,
     * if not... then begin to get database schema.
     * 
     * @access private
     */
    private function connect ()
    {
        // Gets the post data
        $server   = filter_input(INPUT_POST, "server", FILTER_SANITIZE_URL);
        $user     = filter_input(INPUT_POST, "user", FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
        $database = filter_input(INPUT_POST, "database", FILTER_SANITIZE_STRING);
        
        if ( $server === "" || $user === "" || $database === "" )
        {
            $this->set_error(translate ( AppLocale::TYPE_PROCESS, "noconnection", false )."<em>".translate ( AppLocale::TYPE_PROCESS, "nofields", false )."<em>"); 
            $this->write_error();
            $this->redirect(HOME_URI);
        }
        else
        {
            // Connect to database using MySQLi
            $this->mysqli = new mysqli($server, $user, $password, $database);

            // If can't connect, shows error
            if ( $this->mysqli->connect_error )
            { 
                $this->set_error(translate ( AppLocale::TYPE_PROCESS, "noconnection", false )."<em>{$this->mysqli->connect_error}<em>"); 
                $this->write_error();     
                $this->redirect(HOME_URI);
            }
            else
            {
                $this->write_process(translate ( AppLocale::TYPE_PROCESS, "reading", false ));

                $this->database_schema = new DatabaseSchemaModel ( $this );
                $this->database_schema->import_db( $database );

                if ( !$this->error )
                {
                    // Writes a feedback to user
                    $this->write_process(translate ( AppLocale::TYPE_PROCESS, "done", false ));

                    // File name to database
                    $file = FilesController::exists( $database );
                    
                    // Open database JSON file
                    $json = fopen( UP_PATH.$file, "w");
                    // Puts array inside JSON file         
                    fwrite( $json, json_encode( $this->database_schema->export_array() ) );

                    // Writes a feedback to user
                    $this->write_process(translate ( AppLocale::TYPE_PROCESS, "done_file", false ).$file);
                    $this->next_step( $file );
                }
                else
                {
                    $this->write_error(); 
                }
            }
        }
    }
    
    /** 
     * Enables next step button on page.
     * 
     * @param string $file File name to open documentation form page.
     * @access private
     */
    private function next_step ( $file )
    {
        $url = HOME_URI."/documentation/write/".$file;
        
        echo "<script language=\"javascript\">nextStep(\"$url\");</script>";
        $this->flush();
    }
}