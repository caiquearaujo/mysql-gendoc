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
 * Main application class.
 * Manages controllers and methods to call, as well parameters to send.
 * 
 * @author Caique M Araujo <caique.msn@live.com>
 * @package mysqlgendoc\main
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */
class MyGD
{
    /**
     * Types of name to get in get_name function.
     */
    const PAGE       = 0;
    const CONTROLLER = 1;
    const METHOD     = 2;
    
    /**
     * Page name to save if there is no controller, then it will be used to call
     * a view template in /public/views
     * 
     * Gets the name from URL: sample.com/{page}/
     * @var string Page name that will loads a view template.
     * @access private
     */
    private $page;
    
    /**
     * Controller name to be accessed, after find the controller... it changes
     * to respective object (class).
     * 
     * Gets the name from URL: sample.com/{controller}/
     * @var string|Controller Controller name that will be converted to Controller object.
     * @access private
     */
    private $controller;
    
    /**
     * Method to be accessed, after find the method... it changes
     * to execute respective method in the controller object.
     * 
     * Gets the name from URL: sample.com/controller/{method}
     * @var string Method name that will be converted to execute a method in Controller object.
     * @access private
     */
    private $method;
    
    /**
     * Parameters to be sent to a method in the Controller object.
     * 
     * Gets the parameters from URL: sample.com/controller/method/{param..1}/{param..N}/
     * @var array Parameters inside an array, such as: array ( param..1, param..N ).
     * @access private
     */
    private $params;
        
    /**
     * Class constructor to get values for controller, method and parameters. Then,
     * configures the controller object and the method to be called.
     * 
     * @return Everything is done.
     * @access public
     */
    public function __construct() 
    {
        // Get values for controller, method and parameters from URL.
        $this->get_url_data();
        
        // If there is no controller, then call the default controller to pages
        // (PagesController) and the default method for home-page homepage().
        if ( !$this->controller )
        {
            $this->controller = new PagesController();
            $this->controller->homepage();
            return;
        }
                        
        // Tries to find controller object,
        // if has no success then tries to find a template page.
        if ( !class_exists( $this->controller ) )
        {
            $this->controller = new PagesController();
            $this->controller->page( $this->page );
            return;
        }
        
        // Creates a new instance for controller sending the parameters
        $this->controller = new $this->controller( $this->params );
        
        // Tries to find method method, if has success call method and send the
        // parameters
        if ( method_exists( $this->controller, $this->method ) )
        {
            $this->controller->{$this->method}( $this->params );
            return;
        }
        
        // If there is no method name, tries to find a default method,
        // if it has success then call default method
        if ( !$this->method && method_exists( $this->controller, "index" ) )
        {
            $this->controller->index( $this->params );
            return;
        }
        
        // If until here no return has executed, then requests 404 page
        $this->not_found();
        return;
    }
    
    /**
     * Gets all values from $_GET["url"] and configures proprierties:
     * $this->controller, $this->method and $this->params.
     * 
     * $_GET["url"] is configured in /public/.htaccess file.
     * 
     * @access private
     */
    private function get_url_data ()
    {
        // Gets url
        $url = filter_input ( INPUT_GET , "url" , FILTER_SANITIZE_URL );
        
        if ( $url )
        {            
            // Cleans url and explodes it
            $url = rtrim( $url, "/" );
            $url = explode( "/", $url );
            
            // If has a controller, sets controller name
            $this->controller  = has_value( $url, 0 );
            
            if ( !is_null ( $this-> controller ) )
            {
                $this->page       = $this->get_name( $this->controller, $this::PAGE );
                $this->controller = $this->get_name ( $this->controller );
                
                // If has a method, sets method name
                $this->method = $this->get_name ( has_value( $url, 1 ) , $this::METHOD );
            
                // If has more values (controller and method), gets to set parameters
                if ( has_value( $url, 2 ) )
                {
                    unset ( $url[0] );
                    unset ( $url[1] );
                    $this->params = array_values( $url );
                }
            }
        }
    }
    
    /**
     * Requests 404 page from default Controller.
     * 
     * @access private
     */
    private function not_found ()
    {
        $this->controller = new PagesController();
        $this->controller->get_page("404");
        return;
    }
    
    /**
     * Removes invalid characteres from a string to get a valid name.
     * By default it fixes the controller name.
     * 
     * PAGE       -> Removes all charecteres less {a-z, A-Z, -}.
     * CONTROLLER -> Removes all charecteres less {a-z, A-Z}.
     * METHOD     -> Replaces "-" to "_" and 
     *               removes all charecteres less {a-z, A-Z, _}.
     * 
     * @param string $value To fix.
     * @param int $type Type of name to get (by default: controller).
     * @return string|null Fixed value.
     * @access private
     */
    private function get_name ( $value , $type = 1 )
    { 
        if ( !is_null ( $value ) )
        {
            switch ( $type )
            {
                case $this::PAGE:
                    return preg_replace ( "/[^a-zA-Z\-]/i", "", $value );
                case $this::CONTROLLER:
                    return preg_replace ( "/[^a-zA-Z]/i", "", $value ) . "controller";
                case $this::METHOD:
                    return preg_replace ( "/[^a-zA-Z\_]/i", "", str_replace ( "-", "_", $value ) );
            }
        }
        
        return null;
    }
}
