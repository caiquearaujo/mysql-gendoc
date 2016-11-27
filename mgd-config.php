<?php

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
 * General configuration of application. It adds all mainly data before
 * begin the application. It also appends the app/mgd-loader, responsible for
 * load the application.
 * 
 * @package mysqlgendoc
 * @autor Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */

/** @var string Root path based on mgd-config file. One level above public folder. */
define ( "ABSPATH" , dirname( __FILE__ ) );

/** @var string Public upload path. Inside public folder. */
define ( "UP_PATH" , ABSPATH . "/public/uploads/");

/** @var string Public web address. It's your server URL that points to www (public) folder. */
define ( "HOME_URI" , "http://127.0.0.1/mysqlgendoc/public" );

/** @var boolean Enables https protocol. By default it's not enabled. */
define ( "HTTPS", false );

/** @var string Application locale. Needs to have the respective locale file in /apps/locales/. */
define ( "LOCALE", "en" );

/** @var boolean Enables debug mode.
 * If you are developing, change the value for true to see PHP errors.
 * By default it's not enabled.
 */
define ( "DEBUG" , false );

/**
 * Don't make changes after here...
 */

// Sets memory limit for application
ini_set('memory_limit', '128M');
// Loads the application
require_once (ABSPATH . "/app/mgd-loader.php");