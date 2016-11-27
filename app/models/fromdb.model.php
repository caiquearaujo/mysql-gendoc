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
 * Default method to get data from database.
 * 
 * @package mysqlgendoc\models
 * @autor Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */
class FromDBModel extends Model
{
    /**
     * It's necessary to get a table by its database name.
     * 
     * @var string Database name. 
     * @access protected
     */
    protected $database_name;
    
    /**
     * It's necessary to get a column by its table name.
     * 
     * @var string Table name. 
     * @access protected
     */
    protected $table_name;
    
    /**
     * Initializes controller to the model.
     * 
     * @param Controller $controller
     * @access protected
     */
    protected function __construct( &$controller = null ) 
    {
        $this->controller = $controller;
    }
    
    /**
     * Gets some result from database.
     * 
     * @param string $sql SQL query to execute.
     * @param array $data Array to fill with elements.
     * @param boolean $first Sets if is the first process to be done.
     * @return mysqli_stmt Prepared statement to fetch. 
     * @access protected
     */
    protected function fromdb ( $sql, &$data, $first = false )
    {
        // Executes query to get all tables
        /** @var mysqli_stmt $stmt Prepared Statment */
        $stmt = $this->controller->mysqli->prepare( $sql );
                            
        if ( $stmt ) 
        {                     
            // Call bind_param method from object that implements
            // FromDB interface.
            $this->bind_param ( $stmt );
            $stmt->execute();
            $stmt->store_result();
                        
            $current_process = 0;
            $process_size    = $stmt->num_rows;
            
            if ( $process_size > 0 )
            { 
                if ( !$first )
                { $this->continue_reading( $process_size, $data ); }
                else
                { $this->start_reading( $process_size, $data ); }
            }            
            
            // Call add method from object that implements
            // FromDB interface.
            $this->add( $stmt, $current_process, $process_size );
            
            $stmt->close();
        }
        else
        { 
            $this->controller->set_error(translate ( AppLocale::TYPE_PROCESS, "noquery", false ) . $this->controller->mysqli->error); 
            return null;
        }
    }
    
    /**
     * Starts processes to reading arrays. It sets current_process as zero and
     * restart process_size in the controller statuses.
     * 
     * @param int $size Size of data to reading.
     * @param array $array Array to check (by reference).
     * @acess private
     */
    protected function start_reading ( $size, &$array )
    {
        $this->check_array( $array );                
        $this->controller->set_process( $size );
    }
    
    /**
     * Continues processes to reading arrays. It incresses number of processes
     * to be done in the controller statuses.
     * 
     * @param int $size Size of data to reading.
     * @param array $array Array to check (by reference).
     * @acess private
     */
    protected function continue_reading ( $size, &$array )
    {
        $this->check_array( $array );                
        $this->controller->increase_progress( $size );
    }
    
    /**
     * Checks if array is setted... if not, then initializes it.
     * 
     * @param array $array Array to check (by reference).
     * @access private
     */
    private function check_array ( &$array )
    {
        if ( !isset ( $array ))
        { $array = array(); }
    }
}
