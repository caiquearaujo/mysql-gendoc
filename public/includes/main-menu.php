<div class="ui fixed blue inverted menu">
    <div class="ui container">
        <a href="<?php get_url (""); ?>" class="header item">
            <img class="logo" src="<?php get_url ("icon.png"); ?>" alt=""/>
            MySQL Gen
        </a>
        <a href="<?php get_url (""); ?>" class="item"><?php translate ( AppLocale::TYPE_BUTTONS, "home" ); ?></a>
        <?php 
        if ( FilesController::has_files() ) :
        ?>
        <a href="<?php get_url("files"); ?>" class="item"><?php translate ( AppLocale::TYPE_BUTTONS, "see_databases" ); ?></a>
        <?php 
        endif;
        ?>
    </div>
</div>