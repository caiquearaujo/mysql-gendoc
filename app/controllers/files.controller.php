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

class FilesController extends Controller
{
    /**
     * Default content to show in homepage. As a index page.
     * 
     * @access public
     */
    public function index ()
    {
        $this->set_page_info( translate ( AppLocale::TYPE_PAGE, "files_title", false ), 
                                    translate ( AppLocale::TYPE_PAGE, "files_description", false ));
        
        $this->get_header();
        $this->get_page( "files" ); 
        $this->get_footer();
    }
    
    /**
     * Show all JSON files inside /public/uploads folder.
     * 
     * @param string $database Database name.
     * @param int $version Database file version (by default: 1).
     * @return string File name to save database.
     * @access public
     */
    public function show_files ()
    {
        foreach ( scandir ( UP_PATH ) as $ifile )
        {
            if ( substr ( $ifile, 0, 1 ) !== "." && strpos ( $ifile, ".json" ) !== false )
            { $this->components->list_item( $ifile , "file" ); }
        }
    }
    
    /**
     * Tries to find the database JSON file, if it found some file, then
     * increases the file version till gets a original name.
     * 
     * @param string $database Database name.
     * @param int $version Database file version (by default: 1).
     * @return string File name to save database.
     * @access public
     */
    public static function exists ( $database, $version = 1 )
    {
        $jfile = $database.".database.v".$version.".json";
        
        foreach ( scandir ( UP_PATH ) as $ifile )
        {
            if ( substr ( $ifile, 0, 1 ) !== "." && $ifile === $jfile )
            {
                $version++;
                $jfile = $this->exists( $database, $version );
            }
        }
        
        return $jfile;
    }
    
    /**
     * Checks if there is a file inside /public/uploads folders.
     * 
     * @return boolean If has files or not.
     * @access public
     */
    public static function has_files ()
    {
        foreach ( scandir ( UP_PATH ) as $ifile )
        {
            if ( substr ( $ifile, 0, 1 ) !== "." && strpos ( $ifile, ".json" ) !== false )
            { return true; }
        }
        
        return false;
    }
    
}