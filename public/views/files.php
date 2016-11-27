<div class="ui three steps">
    <div class="active step">
        <i class="file icon"></i>
        <div class="content">
            <div class="title"><?php translate ( AppLocale::TYPE_STEP, "file_title" ); ?></div>
            <div class="description"><?php translate ( AppLocale::TYPE_STEP, "file_description" ); ?></div>   
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

<h1><?php translate ( AppLocale::TYPE_VIEW, "file_title" ); ?></h1>

<?php $this->components->info_box ( translate ( AppLocale::TYPE_INFO, "files_title", false ), 
                                        translate ( AppLocale::TYPE_INFO, "files_description", false ), true ); ?>

<div class="ui list">
    <?php $this->show_files(); ?>
</div>