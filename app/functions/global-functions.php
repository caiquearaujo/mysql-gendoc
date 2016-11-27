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
 * Appends all most used application functions.
 * 
 * @package mysqlgendoc\functions
 * @autor Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */

/**
 * Start a session in a secure mode. It prevents crackers from accessing
 * session cookies by using javascript. And, by regenerating session id, it
 * prevents session to be stolen.
 * 
 * @author Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 * @acess public
 */
function sec_session_start ()
{
    // Session name
    $session_name = "mgd-session";
    
    // Session protocol setted at mgb-config file
    $secure = HTTPS;
    
    // Prevents javascript from accessing session id
    $httponly = true;
    
    // Forces session to use only one cookie
    if ( ini_set ( "session.use_only_cookies", 1 ) === FALSE )
    {
        // Loads the session-error page view in /public/views
        $pages = new PagesController();
        $pages->page( "session-error" );
        exit();
    }
    
    // Gets all cookie parameters updated
    $cookie_params = session_get_cookie_params();
    session_set_cookie_params( $cookie_params["lifetime"], $cookie_params["path"],
                                $cookie_params["domain"], $secure, $httponly);
    
    // Sets session name
    session_name( $session_name );
    // Starts session
    session_start();
    // Retrieves the session and deletes the previous one
    session_regenerate_id();
}

/**
 * Checks if a array key exists and if it has a value.
 * For example: you want to know if $json["name"], you call this function.
 * If it exists it will returns $json["name"], if not... it will returns null.
 * 
 * @author Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 * @param array $array Array.
 * @param string|int $key Array key value or index.
 * @return string|null The array key value or null.
 * @access public
 */
function has_value ( $array , $key )
{
    // If there is a key, return array value for key
    if ( isset ( $array[$key] ) && !empty ( $array[$key] ) )
    { return $array[$key]; }
    
    return null;
}

/**
 * Auto loads all default classes from application.
 * 
 * All of our main classes are in "app/classes" folder. The class name will always be
 * "{name}.class.php". For example: MyGD class has its file as "mygb.class.php".
 * Nomenclature: {name}
 * 
 * All of our controllers are in "app/controllers" folder. The controller name will always be
 * "{name}.controller.php". For example: PagesController class has its file as "pages.controller.php".
 * Nomenclature: {name}Controller
 * 
 * All of our models are in "app/models" folder. The model name will always be
 * "{name}.model.php". For example: TableModel class has its file as "table.model.php".
 * Nomenclature: {name}Model
 * 
 * All of our interfaces are in "app/inferfaces" folder. The interface name will always be
 * "{name}.interface.php". For example: FromDBInterface class has its file as "fromdb.interface.php".
 * Nomenclature: {name}Interface
 * 
 * If you want to add a new kind of class, you just need to put the information above,
 * and make changes in ajust_class_name function to read your kind of class.
 * 
 * It's absolutely important that all classes have their right nomenclature in their right folder.
 * 
 * @author Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 * @param string Class name.
 * @param string Directory to explore.
 * @access public
 */
function autoload_classes ( $class, $dir = null )
{
    // If there is no directory setted, begins in the application root
    if ( $dir === null )
    { $dir = ABSPATH . "/app/";}
    
    foreach ( scandir ( $dir ) as $file )
    {
        // If its a directory and it's not a recursive directory, then
        // explores directory...
        if ( is_dir ( $dir.$file ) 
                && substr ( $file, 0, 1 ) !== "." )
        { autoload_classes( $class, $dir.$file."/" ); }
        
        // If it's a valid file with ".php" extension
        // Then ajust class name and find your file to require
        // For example:
        // 1. $class = PageController
        // 2. $class_ = ajust_class_name that returns page.controller.php
        // 3. If $file is equal to $class_ require!
        if ( substr ( $file, 0, 2 ) !== "._" 
                && preg_match ( "/.php$/i" , $file ) )
        {
            $class_ = ajust_class_name( $class );
            if ( $file === $class_ )
            { require_once ( $dir.$file ); }
        }
    }
}

/**
 * First it detects class type (model, inferface, controller, class)
 * and returns your right file name.
 * 
 * PageController -> page.controller.php
 * MyGD           -> mygd.class.php
 * TableModel     -> table.model.php
 * 
 * @author Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 * @param string $name A class name to ajust.
 * @return string File name.
 * @acess public
 */
function ajust_class_name ( $name )
{
    $class = strtolower( $name );
    
    // Return default controller and model
    if ( $class === "controller" || $class === "model" )
    { return $class . ".php"; }
    
    if ( strpos ( $class , "model") !== false )
    { return str_replace( "model", "", $class ) . ".model.php"; }
    else if ( strpos ( $class , "interface") !== false )
    { return str_replace( "interface", "", $class ) . ".interface.php"; }
    else if ( strpos ( $class , "controller") !== false )
    { return str_replace( "controller", "", $class ) . ".controller.php";  }
    else
    { return $class . ".class.php"; }
}

/**
 * Auto loads all classes needed.
 * @see http://php.net/manual/en/function.spl-autoload-register.php
 */
spl_autoload_register( "autoload_classes" );

/**
 * Easy way to writes a url in a page.
 * 
 * @author Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 * @param string $page A page url to append with your HOME_PATH.
 * @acess public
 */
function get_url ( $page )
{ echo HOME_URI ."/". $page; }

/** @var Locale Object to get translations. */
$locale = new AppLocale(LOCALE);

/**
 * Easy way to gets a translation.
 * 
 * @author Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 * @param const $type Type of content.
 * @param string $key Code name.
 * @param boolean $echo If needs to echo (by default: true).
 * @acess public
 */
function translate ( $type , $key, $echo = true )
{ 
    global $locale;
    
    if ( $echo )
    { echo $locale->translation ( $type, $key ); }
    else
    { return $locale->translation ( $type, $key ); }
}