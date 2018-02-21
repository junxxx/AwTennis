<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">权限控制是否开启</label>
    <div class="col-sm-6 col-xs-6">
        <label class="radio-inline"><input type="radio" name="qualification" value="0" <?php  if(empty($item['qualification']) || $item['qualification'] == 0) { ?>checked="true"<?php  } ?>  /> 关闭</label>
        <label class="radio-inline"><input type="radio" name="qualification" value="1" <?php  if($item['qualification'] == 1) { ?>checked="true"<?php  } ?>   /> 开启</label>
    </div>
</div>
<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">特殊权限保护时间</label>
    <div class="col-sm-4 col-xs-6">
        <?php echo tpl_form_field_date('quali_stime', !empty($item['quali_stime']) ? date('Y-m-d H:i',$item['quali_stime']) : date('Y-m-d H:i'), 1)?>
    </div>
    <div class="col-sm-4 col-xs-6">
        <?php echo tpl_form_field_date('quali_etime', !empty($item['quali_etime']) ? date('Y-m-d H:i',$item['quali_etime']) : date('Y-m-d H:i'), 1)?>
    </div>
</div>

<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">用户组参赛权限</label>
    <div class="col-sm-9 col-xs-12 chks" >
        <label class="checkbox-inline">
            <input type="checkbox" class='chkall' name="attendGroups" value="" <?php  if($item['attend_groups']=='' ) { ?>checked="true"<?php  } ?>  /> 全部用户组
        </label>
        <?php  if(is_array($groups)) { foreach($groups as $group) { ?>
        <label class="checkbox-inline">
            <input type="checkbox" class='chksingle'  name="attendGroups[]" value="<?php  echo $group['id'];?>" <?php  if($item['attend_groups']!=''  && in_array($group['id'], $item['attend_groups']) && is_array($item['attend_groups'])) { ?>checked="true"<?php  } ?>  /> <?php  echo $group['groupname'];?>
        </label>
        <?php  } } ?>
    </div>
</div>

<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">挑战位人数</label>
    <div class="col-sm-4 col-xs-6">
        <input type="text" name="challengerNum" id="challengerNum" class="form-control" value="<?php  echo $item['challenger_num'];?>" />
    </div>
</div>

<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">新人优先是否开启</label>
    <div class="col-sm-6 col-xs-6">
        <label class="radio-inline"><input type="radio" name="newFirst" value="0" <?php  if(empty($item['new_first']) || $item['new_first'] == 0) { ?>checked="true"<?php  } ?>  /> 关闭</label>
        <label class="radio-inline"><input type="radio" name="newFirst" value="1" <?php  if($item['new_first'] == 1) { ?>checked="true"<?php  } ?>   /> 开启</label>
    </div>
</div>
<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">报名锁定时间</label>
    <div class="col-sm-4 col-xs-6">
        <?php echo tpl_form_field_date('activity_locktime', !empty($item['activity_locktime']) ? date('Y-m-d H:i',$item['activity_locktime']) : date('Y-m-d',1), 1)?>不选择表示无锁定时间
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
