<!-- BEGIN Script area to homepage -->
<script type="text/javascript">
    $(document).ready( function () 
    {
        $("#send").click( function () { validateForm( $("#form") ); } );
        $("#no-password").click( function () { toggleRequired("password"); } );
    });
</script>
<!-- END Script area to homepage -->

<h1 class="ui header">MySQL-GenDoc</h1>
<p><?php translate ( AppLocale::TYPE_VIEW, "welcome" ); ?></p>

<?php 
if ( FilesController::has_files() ) :
?>
<div class="ui inside container">
    <a href="<?php get_url("files"); ?>"><button class="ui primary big button"><?php translate ( AppLocale::TYPE_BUTTONS, "see_files" ); ?></button></a>
</div>
<?php 
endif;
?>

<div class="ui three steps">
    <div class="active step">
        <i class="plug icon"></i>
        <div class="content">
            <div class="title"><?php translate ( AppLocale::TYPE_STEP, "connect_title" ); ?></div>
            <div class="description"><?php translate ( AppLocale::TYPE_STEP, "connect_description" ); ?></div>   
        </div>
    </div>
    <div class="disable step">
        <i class="edit icon"></i>
        <div class="content">
            <div class="title"><?php translate ( AppLocale::TYPE_STEP, "write_title" ); ?></div>
            <div class="description"><?php translate ( AppLocale::TYPE_STEP, "write_description" ); ?></div>
        </div>
    </div> 
    <div class="disable step">
        <i class="file text icon"></i>
        <div class="content">
            <div class="title"><?php translate ( AppLocale::TYPE_STEP, "export_title" ); ?></div>
            <div class="description"><?php translate ( AppLocale::TYPE_STEP, "export_description" ); ?></div>
        </div>
    </div>
</div>

<div class="ui inside container">
    <div class="ui three segments">
    <div class="ui teal segment">
        <h2><?php translate ( AppLocale::TYPE_VIEW, "howto_title" ); ?></h2>
        <p><?php translate ( AppLocale::TYPE_VIEW, "howto_description" ); ?></p>
    </div>
    <div class="ui green segment">
        <h2><?php translate ( AppLocale::TYPE_VIEW, "needs_title" ); ?></h2>
        <p><?php translate ( AppLocale::TYPE_VIEW, "needs_description" ); ?></p>
    </div>
    <div class="ui olive segment">
        <h2><?php translate ( AppLocale::TYPE_VIEW, "all_title" ); ?></h2> 
        <p><?php translate ( AppLocale::TYPE_VIEW, "all_description" ); ?></p>
    </div>
    </div>
</div>

<div class="ui inside container">
    
    <h3 class="ui top attached header"><?php translate ( AppLocale::TYPE_FORM, "connect" ); ?></h3>
    <div class="ui attached segment red">
        <form id="form" class="ui fluid form" method="post" action="<?php get_url ( "connect" );?>"/>
            <div class="field">
                <label for="server"><?php translate ( AppLocale::TYPE_FORM, "server_connect" ); ?></label>
                <input data-required="true" type="text" id="server" name="server" placeholder="<?php translate ( AppLocale::TYPE_FORM, "server_place" ); ?>">
                <div id="server-required" class="no-show ui pointing red basic label"><?php translate ( AppLocale::TYPE_FORM, "server_fill" ); ?></div>
            </div>
            <div class="ui divider"></div>
            <div class="field">
                <label for="user"><?php translate ( AppLocale::TYPE_FORM, "user_connect" ); ?></label>
                <input data-required="true" type="text" id="user" name="user" placeholder="<?php translate ( AppLocale::TYPE_FORM, "user_place" ); ?>">
                <div id="user-required" class="no-show ui pointing red basic label"><?php translate ( AppLocale::TYPE_FORM, "user_fill" ); ?></div>
            </div>
            <div class="ui divider"></div>
            <div class="field">
                <label for="password"><?php translate ( AppLocale::TYPE_FORM, "password_connect" ); ?></label>
                <input data-required="true" type="password" id="password" name="password" placeholder="<?php translate ( AppLocale::TYPE_FORM, "password_place" ); ?>">
                <div id="password-required" class="no-show ui pointing red basic label"><?php translate ( AppLocale::TYPE_FORM, "password_fill" ); ?></div>
                <div class="ui checkbox" style="block">
                    <input type="checkbox" name="no-password" id="no-password">
                    <label for="no-password"><?php translate ( AppLocale::TYPE_FORM, "nopassword_connect" ); ?></label>
                </div>
            </div>
            <div class="ui divider"></div>
            <div class="field">
                <label for="port"><?php translate ( AppLocale::TYPE_FORM, "port_connect" ); ?></label>
                <input disabled="disabled" data-required="true" type="number" id="port" name="port" value="3306">
                <div id="port-required" class="no-show ui pointing red basic label"><?php translate ( AppLocale::TYPE_FORM, "port_fill" ); ?></div>
            </div>
            <div class="ui divider"></div>
            <div class="field">
                <label for="database"><?php translate ( AppLocale::TYPE_FORM, "database_connect" ); ?></label>
                <input data-required="true" type="text" id="database" name="database" placeholder="<?php translate ( AppLocale::TYPE_FORM, "database_place" ); ?>">
                <div id="database-required" class="no-show ui pointing red basic label"><?php translate ( AppLocale::TYPE_FORM, "database_fill" ); ?></div>
            </div>
            <div class="ui divider"></div>
        </form>
        <button id="send" type="button" class="ui primary button"><?php translate ( AppLocale::TYPE_BUTTONS, "connect" ); ?></button>
        <?php 
        if ( FilesController::has_files() ) :
        ?>
        <a href="<?php get_url("files") ?>"><button class="ui button"><?php translate ( AppLocale::TYPE_BUTTONS, "see_databases" ); ?></button></a>
        <?php 
        endif;
        ?>
    </div>
    
    <div class="ui red segment">
        <h2><?php translate ( AppLocale::TYPE_VIEW, "notes_title" ); ?></h2> 
        <p><?php translate ( AppLocale::TYPE_VIEW, "notes_description" ); ?></p>
        <h2>Copyright (C) 2016 Caique M Araujo</h2>
        <p>
           This program is free software: you can redistribute it and/or modify
           it under the terms of the GNU General Public License as published by
           the Free Software Foundation, either version 3 of the License, or
           (at your option) any later version.
        </p>
        <p>
           This program is distributed in the hope that it will be useful,
           but WITHOUT ANY WARRANTY; without even the implied warranty of
           MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
           GNU General Public License for more details.
        </p>
        <p>
           You should have received a copy of the GNU General Public License
           along with this program.  If not, see < http://www.gnu.org/licenses/ >.
        </p>
        <p>
           <strong>@autor</strong> Caique M Araujo < caique.msn@live.com ><br>
           <strong>@version</strong> 1.0.0<br>
           <strong>@country</strong> Brazil
        </p>
    </div>
    
</div>