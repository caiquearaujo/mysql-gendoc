<div id="<?php echo $id; ?>f" class="ui fluid form">
    <div class="field">
        <label for="server"><?php translate ( AppLocale::TYPE_DOCUMENTATION, "doc_name" ); ?>:</label>
        <input type="text" id="<?php echo $id; ?>0" value="<?php if ( $doc_name !== null ) { echo $doc_name; } ?>" name="name" <?php echo $params; ?> placeholder="<?php translate ( AppLocale::TYPE_DOCUMENTATION, "doc_name_form" ); ?>">
    </div>
    <div class="ui divider"></div>
    <div class="field">
        <label for="user"><?php translate ( AppLocale::TYPE_DOCUMENTATION, "doc_comm" ); ?>:</label>
        <textarea id="<?php echo $id; ?>1" name="doc" <?php echo $params; ?> placeholder="<?php translate ( AppLocale::TYPE_DOCUMENTATION, "doc_comm_form" ); ?>"><?php if ( $doc_comm !== null ) { echo $doc_comm; } ?></textarea>
    </div>
</div>