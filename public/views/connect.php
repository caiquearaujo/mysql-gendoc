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
    <div style="padding: 40px 15px; text-align: center; max-width: 720px; margin: 0 auto;">
        <h1><?php translate ( AppLocale::TYPE_STEP, "connect_title" ); ?></h1>
        <p class="lead"><?php translate ( AppLocale::TYPE_STEP, "connect_description" ); ?></p>
        <?php 
        $this->components->progress_bar(); 
        $this->components->info_box("", "", false);
        ?>
        <div id="next-step" class="no-show">
            <a id="next-step-link"><button type="button" class="ui green button"><?php translate ( AppLocale::TYPE_BUTTONS, "write_documentation" ); ?></button></a>
        </div>
    </div>
</div>