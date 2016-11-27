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
 * Default controller.
 * 
 * @author Caique M Araujo <caique.msn@live.com>
 * @package mysqlgendoc\controllers
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */

class Controller extends Template
{
    /**
     * Page parameters.
     * 
     * @var array Parameters. 
     * @access public
     */
    public $params;
       
    /**
     * Configures main controller by setting defaults values.
     * 
     * @param array $params Parameters from URL.
     * @access public
     */
    public function __construct( $params = array() ) 
    { 
        parent::__construct();
        $this->params = $params;         
    }
}
