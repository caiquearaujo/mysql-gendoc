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
 * Responsible to control all processes statuses from a controller. It allows
 * to control output buffer and update progress state writing a message
 * anda value to a progress-bar (/public/views/components/progress-bar.php).
 * 
 * @package mysqlgendoc\main
 * @autor Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */
class Status
{
    /**
     * Semantic-UI classes for progress bar state.
     */
    const INDICATING = "indicating";
    const WARNING    = "warning";
    const ERROR      = "warning";
    
    /**
     * Stores a integer value with amount of processes.
     * 
     * @var int All processes to be done.
     * @access public
     */
    public $processes_size;
    
    /**
     * Defines current process number in processes range.
     * 
     * @var int Current process being done. 
     * @access public
     */
    public $current_process;
        
    /**
     * Sets if some error occurred. 
     * 
     * @var boolean Error boolean.
     * @access public
     */
    public $error = false;
    
    /**
     * Sets error message.
     * 
     * @var string Error message.
     * @access public
     */
    public $error_message;
    
    /**
     * Starts the output buffer and prevents cache.
     * 
     * @access public
     */
    public function start ()
    {
        header( "Cache-Control: no-cache" ); 
        ob_start();
    }
    
    /**
     * Disables the output buffer and flushes.
     * 
     * @access public
     */
    public function end ()
    {
        ob_end_flush();
    }
    
    /**
     * Flushes the system write buffers of PHP.
     * 
     * @access public
     */
    public function flush()
    {
        ob_flush();
        flush();
    }
        
    /**
     * Sets current process and the total number of processes to be done.
     * By default current process will be equal to zero.
     * 
     * @param int $size Number of processes to be done.
     * @param int $current Number of process (by default: zero).
     * @access public
     */
    public function set_process ( $size , $current = 0 )
    {
        $this->current_process = $current;
        $this->processes_size  = $size;
    }
    
    /**
     * Increases number of processes to be done.
     * 
     * @param int $size Number of processes to increase.
     * @access public
     */
    public function increase_progress ( $size )
    {
        $this->processes_size += $size;
    }
        
    /**
     * Updates the progress bar value. The view needs to implement
     * progress bar component (/public/views/components/progress-bar.php)
     * 
     * @param int $status End status of a progress bar, 
     *                    it changes your Semantic-UI CSS class.
     *                    If not set, it gonna be in progress.
     * @access public
     */
    public function update_processes ( $status = "indicating" )
    {
        $this->current_process++;
        $p = ( $this->current_process / $this->processes_size ) * 100;
        
        // Writes a call to a javascript function, then flushes the buffer
        echo "<script language=\"javascript\">updateProgressBar(\"$p\",\"$status\");</script>";
        $this->flush();
    }
    
    /**
     * Sets an error. 
     * 
     * @param string $m Error message to be shown.
     * @access public
     */
    public function set_error ( $m )
    {
        $this->error         = true;
        $this->error_message = $m;
    }
    
    /**
     * Shows error message and updates the progress bar value. The view needs to implement
     * progress bar component (/public/views/components/progress-bar.php)
     * 
     * @access public
     */
    public function write_error ()
    {
        // Writes a call to a javascript function, then flushes the buffer
        echo "<script language=\"javascript\">writeProgressBar(\"$this->error_message\");</script>";
        echo "<script language=\"javascript\">updateProgressBar(\"100\",\"error\");</script>";
        $this->flush(); 
    }
    
    /**
     * Shows a message. The view needs to implement
     * progress bar component (/public/views/components/progress-bar.php)
     * 
     * @param string $m Info message to be shown.
     * @access public
     */
    public function write_process ( $m )
    {
        // Writes a call to a javascript function, then flushes the buffer
        echo "<script language=\"javascript\">writeProgressBar(\"$m\");</script>";
        $this->flush(); 
    }
    
    /**
     * Shows a info message in the view. The view needs to implement
     * info box component (/public/views/components/show-info.php)
     * 
     * @param string $m Info message to be shown.
     * @access public
     */
    public function write_info ( $m )
    {
        // Writes a call to a javascript function, then flushes the buffer
        echo "<script language=\"javascript\">showInfo(\"$m\");</script>";
        $this->flush(); 
    }
    
    /**
     * Shows a alert message in the view. The view needs to implement
     * alert box component (/public/views/components/show-alert.php)
     * 
     * @param string $m Alert message to be shown.
     * @access public
     */
    public function write_alert ( $m )
    {
        // Writes a call to a javascript function, then flushes the buffer
        echo "<script language=\"javascript\">showAlert(\"$m\");</script>";
        $this->flush(); 
    }
    
    /**
     * Shows a message and updates the progress bar value. The view needs to implement
     * progress bar component (/public/views/components/progress-bar.php)
     * 
     * @param string $m Info message to be shown.
     * @param int $status End status of a progress bar, 
     *                    it changes your Semantic-UI CSS class.
     *                    If not set, it gonna be in progress.
     * @access public
     */
    public function write_update_progress ( $m, $status = "indicating" )
    {
        $this->write_process( $m ); 
        $this->update_processes( $status );
        $this->flush();
    }
    
    /**
     * Redirects to another page. The view needs to implement
     * info box component (/public/views/components/show-info.php)
     * 
     * @param string $u Destiny url.
     * @access public
     */
    public function redirect ( $u )
    {
        // Writes a call to a javascript function, then flushes the buffer
        echo "<script language=\"javascript\">redirect(\"$u\");</script>";
        $this->flush(); 
    }
}

