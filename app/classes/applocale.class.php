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
 * Class to control app locale. Based in files disponibles in /apps/locales.
 * 
 * @author Caique M Araujo <caique.msn@live.com>
 * @package mysqlgendoc\main
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */
class AppLocale
{    
    /**
     * Types of content to get.
     */
    const TYPE_PAGE          = "page";
    const TYPE_VIEW          = "view";
    const TYPE_STEP          = "step";
    const TYPE_FORM          = "form";
    const TYPE_BUTTONS       = "button";
    const TYPE_INFO          = "info";
    const TYPE_ALERT         = "alert";
    const TYPE_DOCUMENTATION = "documentation";
    const TYPE_PROCESS       = "process";
    
    /**
     * Stores datas from a locale JSON file.
     * 
     * @var array Locale array with all data.
     * @access private
     */
    private $locale_json;
    
    /**
     * Loads file to locale.
     * 
     * @param string $locale Locale code (en|pt-br).
     * @access public
     */
    public function __construct( $locale ) 
    {
        $file = ABSPATH."/app/locales/".$locale.".json";
        
        // If locale file exists, then loads it...
        // If not then loads the default locale (en).
        if ( file_exists ( $file ) )
        { $locale_file = file_get_contents( ABSPATH."/app/locales/".$locale.".json" ); }
        else
        { $locale_file = file_get_contents( ABSPATH."/app/locales/en.json" ); }
        
        $this->locale_json = json_decode( $locale_file, true );
    }
    
    /**
     * Gets the key translation by your type.
     * 
     * @param const $type Type of content.
     * @param string $key Code name.
     * @return string Translated data.
     * @access public
     */
    public function translation ( $type , $key )
    { return $this->locale_json[$type][0][$key]; }
}