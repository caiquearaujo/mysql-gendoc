<div class="ui three steps">
    <div class="completed step">
        <i class="plug icon"></i>
        <div class="content">
            <div class="title"><?php translate ( AppLocale::TYPE_STEP, "connect_title" ); ?></div>
            <div class="description"><?php translate ( AppLocale::TYPE_STEP, "connect_description" ); ?></div>   
        </div>
    </div>
    <div class="completed step">
        <i class="edit icon"></i>
        <div class="content">
            <div class="title"><?php translate ( AppLocale::TYPE_STEP, "write_title" ); ?></div>
            <div class="description"><?php translate ( AppLocale::TYPE_STEP, "write_description" ); ?></div>
        </div>
    </div> 
    <div class="active step">
        <i class="file text icon"></i>
        <div class="content">
            <div class="title"><?php translate ( AppLocale::TYPE_STEP, "export_title" ); ?></div>
            <div class="description"><?php translate ( AppLocale::TYPE_STEP, "export_description" ); ?></div>
        </div>
    </div>
</div>

<h1 class="ui header"><?php translate ( AppLocale::TYPE_PAGE, "htmld_title" ); ?></h1>

<a href="<?php echo HOME_URI; ?>/documentation/clean/<?php echo $this->database_file; ?>"><button class="ui primary button"><?php translate ( AppLocale::TYPE_BUTTONS, "print" ); ?></button></a>
<a target="_blank" href="<?php echo HOME_URI; ?>/documentation/write/<?php echo $this->database_file; ?>"><button class="ui button"><?php translate ( AppLocale::TYPE_BUTTONS, "edit" ); ?></button></a>

<?php $this->components->alert_box(translate ( AppLocale::TYPE_ALERT, "default_title", false ), "", false);  ?>