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
 * Verifies forms data.
 * 
 * @param {jQuery Object} $f Form node.
 */
function validateForm ( $f )
{
    clearInputsAlert( $f );
    /** @var {boolean} ready Are there fields to fill or not? */
    var ready = true;
    
    $f.find("input").each(function ()
    {
        // Stores input, its required value and name
        var $i       = $(this),
            required = $i.attr("data-required"),
            name     = $i.attr("name");
        
        // If it is required, then stops sending and show a message
        if (required === "true" && $i.val() === "")
        { 
            $("#"+name+"-required").removeClass("no-show");
            $i.focus();
            ready = false;
            return false;
        }
    });
    
    if ( ready )
    { $f.submit(); }
}

/**
 * Cleans input objects that has a required alert active.
 * 
 * @param {jQuery Object} $f Form node.
 * @returns All input objects will be cleaned.
 */
function clearInputsAlert ( $f )
{
    $f.find("input").each(function ()
    {
        var name = $(this).attr("name"),
            $a   = $("#"+name+"-required");
        
        if ( !$a.hasClass("no-show") )
        { $("#"+name+"-required").addClass("no-show"); }
    });
}

/**
 * Toggles data-require attr in the object.
 * 
 * @param {string} e Object id.
 * @returns data-required attr to object.
 */
function toggleRequired ( e )
{
    var $i = $("#"+e), required = $i.attr("data-required");
    
    if ( required === "true" )
    { $i.attr("data-required", "false"); }
    else
    { $i.attr("data-required", "true"); }
}

/**
 * Shows an alert in the alert area.
 * 
 * @param {string} message Message to be shown. 
 */
function showAlert ( message )
{
    // Display a message inside an alert area
    $("#alert-area").removeClass("no-show");
    $("#alert-message").html(message);
}

/**
 * Shows an info in the info area.
 * 
 * @param {string} message Message to be shown. 
 */
function showInfo( message )
{
    // Display a message inside an info area
    $("#info-area").removeClass("no-show");
    $("#info-message").html(message); 
}
 
/**
 * Shows an alert in the alert area.
 * 
 * @param {string} message Message to be shown. 
 */
function writeProgressBar ( message )
{ $("#progress-message").html(message); }


/**
 * Updates progress bar.
 * 
 * @param {int} p Progress value [0..100]
 * @param {string} status Class status name to progress-bar (Semantic-UI).
 */
function updateProgressBar ( p, status )
{
    p = parseInt(p);
    // Sets values for progress bar
    $("#progress-bar").attr("data-percent", p);
    $("#progress-bar").removeClass();
    $("#progress-bar").addClass("ui progress "+status);
    $("#percent-bar").css("width", p + "%");
    $("#percent").html(p);
}

/**
 * Redirects page to a new page after 5 seconds.
 * 
 * @param {string} url Page url.
 */
function redirect ( url )
{
    showInfo("You will be redirected in 5 seconds..."); 
    window.setTimeout( function () { window.location.href = url; }, 5000 );
}

/**
 * Enables next step button
 * 
 * @param {string} url Page url.
 */
function nextStep ( url )
{
    $("#next-step").removeClass("no-show");
    $("#next-step-link").attr("href", url);
}