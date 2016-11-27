<div id="result" style="padding: 10px;">
        
</div>

<button type="button" class="ui primary button" onclick="documentationArray();"><?php translate ( AppLocale::TYPE_BUTTONS, "save_documentation" ); ?></button>
<span id="next-step" class="no-show"><a id="next-step-link"><button class="ui button"><?php translate ( AppLocale::TYPE_BUTTONS, "see_documentation" ); ?></button></a></span>

<!-- BEGIN Script area to documentation -->
<script language="javascript">
    
    function documentationArray ()
    {
        var forms = {}, names = {}, docs = {};
        
        var index = 0;
        
        $("input[name='name']").each( function (i) 
        {
            var name = {};
            
            name["value"] = $(this).val();
            
            if ( $(this).attr("data-table") )
            {
                name["table"] = $(this).attr("data-table");
            }
            
            if ( $(this).attr("data-column") )
            {
                name["column"] = $(this).attr("data-column");
            }
            
            if ( $(this).attr("data-key") )
            {
                name["key"] = $(this).attr("data-key");
            }
            
            names[index] = name;
            index++;
        });
        
        index = 0;
        
        $("textarea[name='doc']").each( function (i) 
        {
            var doc = {};
            
            doc["value"] = $(this).val();
            
            if ( $(this).attr("data-table") )
            {
                doc["table"] = $(this).attr("data-table");
            }
            
            if ( $(this).attr("data-column") )
            {
                doc["column"] = $(this).attr("data-column");
            }
            
            if ( $(this).attr("data-key") )
            {
                doc["key"] = $(this).attr("data-key");
            }
            
            docs[index] = doc;
            index++;
        });
        
        forms["names"] = names;
        forms["docs"] = docs;
        
        var json = JSON.stringify(forms);
        
        $.post("<?php echo HOME_URI; ?>/documentation/save/<?php echo $this->database_file; ?>", {data:json} , function (r)
        {
            $("#result").html(r);
        });
    }
    
</script>
<!-- END Script area to documentation -->