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
 * Begins starting a session in secure mode, then sets the debug mode
 * and initializes application class.
 * 
 * @package mysqlgendoc
 * @autor Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */

// Appends global functions
require_once ( ABSPATH . "/app/functions/global-functions.php" );

// Initializes secure session
sec_session_start();

// Verifies debug mode
if ( DEBUG === false )
{
    // Hides all errors
    error_reporting( 0 );
    ini_set( "display_startup_errors", 0);
    ini_set( "display_erros", 0 );
}
else
{
    // Shows all errors
    error_reporting( E_ALL );
    ini_set( "display_startup_errors", 1);
    ini_set( "display_erros", 1 );
}

/** @var MyDB Main class to starts the application */
$app = new MyGD();