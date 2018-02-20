<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/member/tabs', TEMPLATE_INCLUDEPATH)) : (include template('web/member/tabs', TEMPLATE_INCLUDEPATH));?>
<style type='text/css'>
    .trhead td {
        background: #efefef;
        text-align: center
    }

    .trbody td {
        text-align: center;
        vertical-align: top;
        border-left: 1px solid #ccc;
    }
</style>
<div class="panel panel-default">
    <div class="panel-body">
        <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
            <input type="hidden" name="c" value="site"/>
            <input type="hidden" name="a" value="entry"/>
            <input type="hidden" name="m" value="awt_enroll"/>
            <input type="hidden" name="do" value="member"/>
            <div class="form-group">
                <div class="col-sm-8 col-lg-9 col-xs-12">
                    <div class='input-group'>

                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <div class='input-group'>
                        <div class='input-group-addon'>加入时间
                            <label class='radio-inline' style='margin-top:-7px;'>
                                <input type='radio' value='0' name='searchtime' <?php  if($_GPC['searchtime']=='0') { ?>checked<?php  } ?>>不搜索
                            </label>
                            <label class='radio-inline' style='margin-top:-7px;'>
                                <input type='radio' value='1' name='searchtime' <?php  if($_GPC['searchtime']=='1') { ?>checked<?php  } ?>>搜索
                            </label>
                        </div>
                        <?php  echo tpl_form_field_daterange('time', array('starttime'=>date('Y-m-d H:i',$starttime),'endtime'=>date('Y-m-d H:i', $endtime)),true);?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-7 col-lg-9 col-xs-12">
                    <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
                    <button type="submit" name="export" value="1" class="btn btn-primary">导出 Excel</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div colspan='2' style='text-align:left;'>用户总数: <?php  echo $totalmember;?></div>
<table class='table' style='float:left;border:1px solid #ccc;margin-bottom:5px;table-layout: fixed'>
    <tr class='trhead'>
        <td>昵称</td>
        <td>手机号码</td>
        <td>性别</td>
        <td>加入时间</td>
        <td>组</td>
        <td>积分/余额</td>
        <td>状态</td>
        <td>操作</td>
    </tr>
</table>

<?php  if(is_array($list)) { foreach($list as $item) { ?>
<table class='table' style='float:left;border:1px solid #ccc;margin-top:5px;margin-bottom:5px;table-layout: fixed'>
    <tr class='trbody'>
        <td valign='top' colspan='2' style='border-left:none;text-align: left;' style='width:200px;'>
            <img src="<?php  echo $item['avatar'];?>" style="width: 50px; height: 50px;border:1px solid #ccc;padding:1px;">
            <?php  echo $item['nickname'];?>
        </td>
        <td><?php  echo $item['mobile'];?></td>
        <td style='border-left:none'><?php  if(!empty($g['optiontitle'])) { ?><span
                class="label label-primary"><?php  echo $g['optiontitle'];?></span><?php  } ?>
            <br/><?php  echo $g['goodssn'];?>
        </td>
        <td style='border-left:none'><?php  echo $item['fee'];?></td>
        <td style='border-left:none'><?php  echo $g['total'];?></td>
        <td rowspan="<?php  echo count($item['goods'])?>"> <br/></td>
        <td rowspan="<?php  echo count($item['goods'])?>"> <br/><?php  echo $item['mobile'];?></td>
        <td rowspan="<?php  echo count($item['goods'])?>"><label class='label label-<?php  echo $item['css'];?>'><?php  echo $item['paytype'];?></label></td>
        <td rowspan="<?php  echo count($item['goods'])?>"><?php  echo $item['status'];?>
            <br/>
            <a href="<?php  echo $this->createWebUrl('order', array('op' => 'detail', 'id' => $item['id']))?>">查看详情</a>
        </td>
    </tr>
</table>
<?php  } } ?>
<?php  echo $pager;?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>
