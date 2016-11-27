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
 * Implements required methods to get data from database.
 * 
 * @package mysqlgendoc\interfaces
 * @autor Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */
interface FromDBInterface
{
    /**
     * Sets the parameters to a prepared statement.
     * 
     * @param mysqli_stmt $stmt Prepared statement (by reference).
     * @access public
     */
    public function bind_param ( &$stmt );
    
    /**
     * Adds a new element if a prepared statement has some result to fetch.
     * 
     * @param type $stmt Prepared statement (by reference).
     * @param type $current_process Number of process (by reference).
     * @param type $process_size Total of processes (by reference).
     * @access public
     */
    public function add ( &$stmt, &$current_process, &$process_size );
}