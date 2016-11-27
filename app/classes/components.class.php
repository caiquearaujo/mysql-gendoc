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
 * Contains all template components to be called in /public/views/components
 * 
 * @package mysqlgendoc\main
 * @autor Caique M Araujo <caique.msn@live.com>
 * @since 1.0.0 Inicial version.
 * @version 1.0.0
 */
class Components
{    
    /**
     * CSS classes to panels (Semantic-UI).
     */
    const UNIQUE_PANEL    = "segment";
    const MULTIPLE_PANELS = "segments";
    
    /**
     * Loads /public/views/components/show-alert.php
     * 
     * @param string $title Alert title (by default: Ops... something went wrong!).
     * @param string $message Alert message (by default: empty).
     * @param boolean $show Alert visibility (by default: false).
     * @access public
     */
    public function alert_box ( $title = null, $message = "", $show = false )
    { 
        if ( !$title ) { $title = translate ( AppLocale::TYPE_ALERT, "default_title", false ); }
        $show = $this->get_show ( $show );
        require ( ABSPATH . "/public/views/components/show-alert.php" ); 
    }
    
    /**
     * Loads /public/views/components/show-info.php
     * 
     * @param string $title Info title (by default: empty).
     * @param string $message Info message (by default: empty).
     * @param boolean $show Info visibility (by default: false).
     * @access public
     */
    public function info_box ( $title = null, $message = "", $show = false )
    { 
        if ( !$title ) { $title = translate ( AppLocale::TYPE_INFO, "default_title", false ); }
        $show = $this->get_show ( $show );
        require ( ABSPATH . "/public/views/components/show-info.php" );
    }
    
    /**
     * Loads /public/views/components/progress-bar.php
     * ATTENTION: One by page. If you need more, create another method and template
     * that fit your needs.
     * 
     * @access public
     */
    public function progress_bar ()
    { require ( ABSPATH . "/public/views/components/progress-bar.php" ); }
    
    /**
     * Creates a tag label (Semantic-UI).
     * 
     * @param string $name Tag name.
     * @param string $color CSS Class to tag color (Semantic-UI).
     * @access public
     */
    public function tag ( $name, $color )
    { echo "<span class=\"ui tag $color label\">$name</span>"; }
    
    /**
     * Creates a label (Semantic-UI).
     * 
     * @param string $name Label name.
     * @param string $color CSS Class to label color (Semantic-UI).
     * @access public
     */
    public function label ( $name, $color )
    { echo "<span class=\"ui $color label\">$name</span>"; }
    
    /**
     * Creates a divider (Semantic-UI).
     * 
     * @access public
     */
    public function divider ()
    { echo "<div class=\"ui divider\"></div>"; }
    
    public function list_item ( $item_name, $type )
    { require ( ABSPATH . "/public/views/components/list-item.php" ); }


    /**
     * Loads /public/views/components/documentation/form.php
     * To load documentation input for contextual name and textarea for comments.
     * 
     * @param int $id Unique id to the form.
     * @param string $params Parameters to append in input and textarea fields.
     * @param string $doc_name Documentation contextual name.
     * @param string $doc_comm Documentation comment.
     * @access public
     */
    public function documentation_form ( $id, $params = "", $doc_name = null, $doc_comm = null )
    { $this->divider(); require ( ABSPATH . "/public/views/components/documentation/form.php" ); }
    
    /**
     * Loads /public/views/components/documentation/open-panel.php
     * To open a new panel data to documentation.
     * 
     * @param string $name Panel category name.
     * @param string $title Panel title.
     * @param string $type CSS Class to panel type (Semantic-UI).
     * @param string $color CSS Class to panel color (Semantic-UI).
     * @access public
     */
    public function documentation_open_panel ( $name, $title, $type, $color )
    { require ( ABSPATH . "/public/views/components/documentation/open-panel.php" ); }
        
    /**
     * Closes an opened documentation panel.
     * 
     * @access public
     */
    public function documentation_close_panel ()
    { echo "</div>"; }
    
    /**
     * Loads /public/views/components/documentation/open-table.php
     * To open a new table data to documentation.
     * 
     * @access public
     */
    public function documentation_open_table ()
    { require ( ABSPATH . "/public/views/components/documentation/open-table.php"); }
    
    /**
     * Closes an opened documentation table.
     * 
     * @access public
     */
    public function documentation_close_table ()
    { echo "</tbody></table>"; }
    
    /**
     * Opens one line in a documentation table.
     * 
     * @access public
     */
    public function documentation_open_line ()
    { echo "<tr>"; }
    
    /**
     * Opens one column in a documentation table.
     * 
     * @access public
     */
    public function documentation_open_column ()
    { echo "<td>"; }
    
    /**
     * Closes last line opened in a documentation table.
     * 
     * @access public
     */
    public function documentation_close_line ()
    { echo "</tr>"; }
    
    /**
     * Closes last column opened in a documentation table.
     * 
     * @access public
     */
    public function documentation_close_column ()
    { echo "</td>"; }
    
    /**
     * Controls component visibility.
     * 
     * @param boolean $show Visibility.
     * @return string CSS class (main) to append if it is invisible.
     * @access private
     */
    private function get_show ( $show )
    { return $show ? "" : "no-show"; }
}