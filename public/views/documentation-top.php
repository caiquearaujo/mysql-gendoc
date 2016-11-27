<div class="ui three steps">
    <div class="completed step">
        <i class="plug icon"></i>
        <div class="content">
            <div class="title"><?php translate ( AppLocale::TYPE_STEP, "connect_title" ); ?></div>
            <div class="description"><?php translate ( AppLocale::TYPE_STEP, "connect_description" ); ?></div>   
        </div>
    </div>
    <div class="active step">
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

<h1 class="ui header"><?php translate ( AppLocale::TYPE_VIEW, "documentation_title" ); ?></h1>

<div class="ui inside container">
    <?php 
    $this->components->alert_box(translate ( AppLocale::TYPE_ALERT, "default_title", false ), "", false); 
    $this->components->info_box(translate ( AppLocale::TYPE_INFO, "default_title", false ), "", false);
    ?>
    <div class="ui three segments">
        <div class="ui teal segment">
            <h2><?php translate ( AppLocale::TYPE_VIEW, "tips_title" ); ?></h2>
            <p><?php translate ( AppLocale::TYPE_VIEW, "dtips_description" ); ?></p>
        </div>
        <div class="ui teal segment">
            <h2><?php translate ( AppLocale::TYPE_VIEW, "tips_column" ); ?></h2>
            <h2><?php translate ( AppLocale::TYPE_VIEW, "tips_keys" ); ?></h2>
        </div>
    </div>
</div>

<button type="button" class="ui primary button" onclick="documentationArray();"><?php translate ( AppLocale::TYPE_BUTTONS, "save_documentation" ); ?></button>
<span id="next-step" class="no-show"><a id="next-step-link"><button class="ui button"><?php translate ( AppLocale::TYPE_BUTTONS, "see_documentation" ); ?></button></a></span>
