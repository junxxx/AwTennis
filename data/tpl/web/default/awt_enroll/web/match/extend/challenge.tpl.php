<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">挑战位是否开启</label>
    <div class="col-sm-6 col-xs-6">
        <?php if( ce('shop.goods' ,$item) ) { ?>
        <label class="radio-inline"><input type="radio" name="cash" value="1" <?php  if(empty($item['cash']) || $item['cash'] == 1) { ?>checked="true"<?php  } ?>  /> 关闭</label>
        <label class="radio-inline"><input type="radio" name="cash" value="2" <?php  if($item['cash'] == 2) { ?>checked="true"<?php  } ?>   /> 开启</label>
        <?php  } else { ?>
        <div class='form-control-static'><?php  if(empty($item['cash']) || $item['cash'] == 1) { ?>关闭<?php  } else { ?>开启<?php  } ?></div>
        <?php  } ?>
    </div>
</div>
<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">用户组参赛权限</label>
    <div class="col-sm-9 col-xs-12 chks" >
        <?php if( ce('shop.goods' ,$item) ) { ?>
        <label class="checkbox-inline">
            <input type="checkbox" class='chkall' name="showgroups" value="" <?php  if($item['showgroups']=='' ) { ?>checked="true"<?php  } ?>  /> 全部用户组
        </label>
        <label class="checkbox-inline">
            <input type="checkbox" class='chksingle'  name="showgroups[]" value="0" <?php  if($item['showgroups']!='' && is_array($item['showgroups']) && in_array('0', $item['showgroups'])) { ?>checked="true"<?php  } ?>  /> 无分组
        </label>
        <?php  if(is_array($groups)) { foreach($groups as $group) { ?>
        <label class="checkbox-inline">
            <input type="checkbox" class='chksingle'  name="showgroups[]" value="<?php  echo $group['id'];?>" <?php  if($item['showgroups']!=''  && in_array($group['id'], $item['showgroups']) && is_array($item['showgroups'])) { ?>checked="true"<?php  } ?>  /> <?php  echo $group['groupname'];?>
        </label>
        <?php  } } ?>

        <?php  } else { ?>
        <div class='form-control-static'>
            <?php  if($item['showgroups']=='') { ?>
            全部用户等级
            <?php  } else { ?>
            <?php  if($item['showgroups']!='' && is_array($item['showgroups']) && in_array('0', $item['showgroups'])) { ?>
            <?php echo empty($shop['levelname'])?'普通等级':$shop['levelname']?>;
            <?php  } ?>
            <?php  if(is_array($levels)) { foreach($levels as $level) { ?>
            <?php  if($item['showgroups']!='' && is_array($item['showgroups'])  && in_array($level['id'], $item['showgroups'])) { ?>
            <?php  echo $level['levelname'];?>;
            <?php  } ?>
            <?php  } } ?>
            <?php  } ?>
        </div>

        <?php  } ?>

    </div>
</div>
<script language='javascript'>
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
