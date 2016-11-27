<?php

if ( $type == "file" )
{ 
    $name        = "<a href=\"".HOME_URI."/documentation/write/".$item_name."\" style=\"color:#000\">".$item_name.translate ( AppLocale::TYPE_BUTTONS, "edit_file", false )."</a>"; 
    $description = "<a href=\"".HOME_URI."/documentation/html/".$item_name."\">".translate ( AppLocale::TYPE_BUTTONS, "click_here", false )."</a>".translate ( AppLocale::TYPE_BUTTONS, "tosee", false ); 
}

?>
<div class="item">
    <i class="<?php echo $type; ?> icon"></i>
    <div class="content">
        <div class="header"><?php echo $name; ?></div>
        <div class="description"><?php echo $description; ?></div>
    </div>
</div>