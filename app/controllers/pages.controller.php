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
 * Default controller to get static pages.
 * 
 * @package mysqlgendoc\controllers
 * @autor Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */
class PagesController extends Controller
{    
    /**
     * Default content to show in homepage. As a index page.
     * 
     * @access public
     */
    public function homepage ()
    {
        $this->get_header();
        $this->get_page( "home" );
        $this->get_footer();
    }
    
    /**
     * Loads a static page from a template file in /public/views.
     * 
     * @param string $page Name of templete {name}.php
     * @access public
     */
    public function page ( $page )
    {
        $this->get_header();
        $this->get_page( $page );
        $this->get_footer();
    }
}