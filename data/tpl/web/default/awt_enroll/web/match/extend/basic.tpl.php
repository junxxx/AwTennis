<?php defined('IN_IA') or exit('Access Denied');?><?php  if(!empty($item['id'])) { ?>
<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">活动链接(点击复制)</label>
    <div class="col-sm-9 col-xs-12">
        <p class='form-control-static'><a href='javascript:;' title='点击复制连接' id='cp'><?php  echo $this->createMobileUrl('match/detail',array('id'=>$item['id']))?></a></p>
    </div>
</div>
<?php  } ?>
<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">排序</label>
    <div class="col-sm-9 col-xs-12">
        <?php if( ce('shop.goods' ,$item) ) { ?>
        <input type="text" name="displayorder" id="displayorder" class="form-control" value="<?php  echo $item['displayorder'];?>" />
        <span class='help-block'>数字越大，排名越靠前,如果为空，默认排序方式为创建时间</span>
        <?php  } else { ?>
        <div class='form-control-static'><?php  echo $item['displayorder'];?></div>
        <?php  } ?>
    </div>
</div>
<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>活动标题</label>
    <div class="col-sm-9 col-xs-12">
        <?php if( ce('shop.goods' ,$item) ) { ?>
        <input type="text" name="activitytitle" id="activitytitle" class="form-control" value="<?php  echo $item['title'];?>" />
        <?php  } else { ?>
        <div class='form-control-static'><?php  echo $item['title'];?></div>
        <?php  } ?>
    </div>
</div>
<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>分类</label>
    <div class="col-sm-8 col-xs-12">
        <select class="form-control" name="cateid">
            <?php  if(is_array($categories)) { foreach($categories as $cate) { ?>
            <option  value="<?php  echo $cate['id'];?>" <?php  if($item['cid'] == $cate['id']) { ?>selected='selected'<?php  } ?>><?php  echo $cate['cname'];?></option>
            <?php  } } ?>
        </select>

    </div>
</div>

<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">报名开始时间</label>
    <div class="col-sm-4 col-xs-6">
        <?php echo tpl_form_field_date('sign_stime', !empty($item['sign_stime']) ? date('Y-m-d H:i',$item['sign_stime']) : date('Y-m-d H:i'), 1)?>
    </div>
    <div class="col-sm-4 col-xs-6">
        <?php echo tpl_form_field_date('sign_etime', !empty($item['sign_etime']) ? date('Y-m-d H:i',$item['sign_etime']) : date('Y-m-d H:i'), 1)?>
    </div>
</div>

<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">活动开始时间</label>
    <div class="col-sm-4 col-xs-6">
        <?php echo tpl_form_field_date('activity_stime', !empty($item['activity_stime']) ? date('Y-m-d H:i',$item['activity_stime']) : date('Y-m-d H:i'), 1)?>
    </div>
</div>

<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">活动地点</label>
    <div class="col-sm-9 col-xs-12">
        <input type="text" name="location" id="location" class="form-control" value="<?php  echo $item['location'];?>" />
    </div>
</div>

<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">活动封面图</label>
    <div class="col-sm-9 col-xs-12">
        <?php  echo tpl_form_field_image('headimg', $item['headimg'])?>
        <span class="help-block">建议尺寸: 640 * 640 ，或正方型图片 </span>
    </div>
</div>
<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">其他图片</label>
    <div class="col-sm-9 col-xs-12">
        <?php if( ce('shop.goods' ,$item) ) { ?>
        <?php  echo tpl_form_field_multi_image('thumbs',$piclist)?>
        <span class="help-block">建议尺寸: 640 * 640 ，或正方型图片 </span>
        <?php  } else { ?>
        <?php  if(is_array($piclist)) { foreach($piclist as $p) { ?>
        <a href='<?php  echo tomedia($p)?>' target='_blank'>
            <img src="<?php  echo tomedia($p)?>" style='height:100px;border:1px solid #ccc;padding:1px;float:left;margin-right:5px;' />
        </a>
        <?php  } } ?>
        <?php  } ?>
    </div>
</div>

<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">活动费用</label>
    <div class="col-sm-3 col-xs-6">
        <div class="input-group form-group">
            <input type="text" name="fee" id="fee" class="form-control" value="<?php  echo $item['fee'];?>" />
            <span class="input-group-addon">元</span>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">正选人数</label>
    <div class="col-sm-3 col-xs-6">
        <div class="input-group">
            <input type="text" name="com_nums" id="com_nums" class="form-control" value="<?php  echo $item['com_nums'];?>" />
            <span class="input-group-addon">位</span>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否显示</label>
    <div class="col-sm-9 col-xs-12">
        <label for="isshow1" class="radio-inline"><input type="radio" name="is_show" value="1" id="isshow1" <?php  if($item['is_show'] == 1) { ?>checked="true"<?php  } ?> /> 是</label>        &nbsp;&nbsp;&nbsp;
        <label for="isshow2" class="radio-inline"><input type="radio" name="is_show" value="0" id="isshow2"  <?php  if($item['is_show'] == 0) { ?>checked="true"<?php  } ?> /> 否</label>
        <span class="help-block"></span>
    </div>
</div>