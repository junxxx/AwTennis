<?php defined('IN_IA') or exit('Access Denied');?>                <div class="form-group">
                    
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>自定义键名</label>

                      <?php if( ce('tmessage' ,$list) ) { ?>
                    <div class="col-sm-1" style='width:200px;'>
                        <input type="text" name="tp_kw[]" class="form-control" value="<?php  echo $list2['keywords'];?>" placeholder="键值，例：keywords<?php  echo $kw;?>" />
                    </div>
                    <div class="col-sm-3">
                        <input type="text" name="tp_value[]" class="form-control" value="<?php  echo $list2['value'];?>" placeholder="请在此输入对应的值" />
                    </div>
                    <div class="col-sm-2" >
                        <?php  echo tpl_form_field_color('tp_color[]', $list2['color'])?>
                    </div>
                     <a class="btn btn-default" href='javascript:;' onclick='removeType(this)'><i class='icon icon-remove fa fa-times'></i> 删除</a>
                     <?php  } else { ?>
                       <div class="col-sm-9 col-xs-12">
                             <div class='form-control-static'><?php  echo $list2['keywords'];?>: 内容: <?php  echo $list2['value'];?>  颜色: <?php  echo $list2['color'];?></div>
                      </div>
                     <?php  } ?>
                </div>

  