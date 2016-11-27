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
 * Represents specific data for foreign keys.
 * 
 * @package mysqlgendoc\models
 * @autor Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */
class ForeignModel
{
    /**
     * @var string Foreign table name.
     * @access public
     */
    public $with_table;
    
    /**
     * @var string Foreign column name. 
     * @access public
     */
    public $with_column;
    
    /**
     * @var string Delete rule for foreign key. 
     * @access public
     */
    public $on_delete;
    
    /**
     * @var string Update rule for foreign key. 
     * @access public
     */
    public $on_update;
    
    /**
     * Adds the foreign key data to an array object.
     * 
     * @param array $array Array to fill.
     * @access public
     */
    /**
     * Adds the foreign key data to an associative array version.
     * 
     * @return array Associative array with foreign key data.
     * @access public
     */
    public function export_foreignkey_array ( & $array )
    {
        $array["with_table"]  = $this->with_table;
        $array["with_column"] = $this->with_column;
        $array["on_delete"]   = $this->on_delete;
        $array["on_update"]   = $this->on_update;
    }
}