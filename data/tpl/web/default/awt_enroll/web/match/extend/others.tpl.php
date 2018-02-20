<?php defined('IN_IA') or exit('Access Denied');?><script language='javascript'>
    $('.chkall').click(function(){
        var checked =$(this).get(0).checked;
        if(checked) {
            $(this).closest('div').find(':checkbox[class!="chkall"]').removeAttr('checked');
        }
    });
    $('.chksingle').click(function(){
        $(this).closest('div').find(':checkbox[class="chkall"]').removeAttr('checked');
    })

</script>
