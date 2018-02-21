<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/match/tabs', TEMPLATE_INCLUDEPATH)) : (include template('web/match/tabs', TEMPLATE_INCLUDEPATH));?>
<script type="text/javascript" src="resource/js/lib/jquery-ui-1.10.3.min.js"></script>

<?php  if($operation == 'post') { ?>
<style type='text/css'>
    .tab-pane {padding:20px 0 20px 0;}
</style>
<div class="main">
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php  if(empty($item['id'])) { ?>添加活动<?php  } else { ?>编辑活动<?php  } ?>
            </div>
            <div class="panel-body">
                <ul class="nav nav-arrow-next nav-tabs" id="myTab">
                    <li class="active" ><a href="#tab_basic">基本信息</a></li>
                    <li><a href="#tab_qualification">参赛权限设置</a></li>
                    <li><a href="#tab_judge">随场裁判设置</a></li>
                    <li><a href="#tab_others">其它设置</a></li>

                </ul>

                <div class="tab-content">
                    <div class="tab-pane  active" id="tab_basic"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/match/extend/basic', TEMPLATE_INCLUDEPATH)) : (include template('web/match/extend/basic', TEMPLATE_INCLUDEPATH));?></div>
                    <div class="tab-pane" id="tab_qualification"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/match/extend/qualification', TEMPLATE_INCLUDEPATH)) : (include template('web/match/extend/qualification', TEMPLATE_INCLUDEPATH));?></div>
                    <div class="tab-pane" id="tab_judge"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/match/extend/judge', TEMPLATE_INCLUDEPATH)) : (include template('web/match/extend/judge', TEMPLATE_INCLUDEPATH));?></div>
                    <div class="tab-pane" id="tab_others"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/match/extend/others', TEMPLATE_INCLUDEPATH)) : (include template('web/match/extend/others', TEMPLATE_INCLUDEPATH));?></div>
                </div>
            </div>
        </div>
        <div class="form-group col-sm-12">
            <?php if( ce('shop.goods' ,$item) ) { ?>
            <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" onclick="return formcheck()" />
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
            <?php  } ?>
            <input type="button" name="back" onclick='history.back()' <?php if(cv('shop.goods.add|shop.goods.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />

        </div>
    </form>
</div>

<script type="text/javascript">
    var category = <?php  echo json_encode($children)?>;
    window.optionchanged = false;
    require(['bootstrap'],function(){
        $('#myTab a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        })
    });

    require(['util'],function(u){
        $('#cp').each(function(){
            u.clip(this, $(this).text());
        });
    })

    function formcheck() {
        if ($("#activitytitle").isEmpty()) {
            $('#myTab a[href="#tab_basic"]').tab('show');
            Tip.focus("#activitytitle", "请输入活动标题!");
            return false;
        }


        <?php  if(empty($id)) { ?>
        if ($.trim($(':input[name="headimg"]').val()) == '') {
            $('#myTab a[href="#tab_basic"]').tab('show');
            Tip.focus(':input[name="headimg"]', '请上传缩略图.');
            return false;
        }
        <?php  } ?>

        var full = checkoption();
        if (!full) {
            return false;
        }
        if (optionchanged) {
            $('#myTab a[href="#tab_option"]').tab('show');
            alert('规格数据有变动，请重新点击 [刷新规格项目表] 按钮!');
            return false;
        }
        return true;
    }

    function checkoption() {

        var full = true;
        if ($("#hasoption").get(0).checked) {
            $(".spec_title").each(function (i) {
                if ($(this).isEmpty()) {
                    $('#myTab a[href="#tab_option"]').tab('show');
                    Tip.focus(".spec_title:eq(" + i + ")", "请输入规格名称!", "top");
                    full = false;
                    return false;
                }
            });
            $(".spec_item_title").each(function (i) {
                if ($(this).isEmpty()) {
                    $('#myTab a[href="#tab_option"]').tab('show');
                    Tip.focus(".spec_item_title:eq(" + i + ")", "请输入规格项名称!", "top");
                    full = false;
                    return false;
                }
            });
        }
        if (!full) {
            return false;
        }
        return full;
    }

</script>

<?php  } else if($operation == 'display') { ?>

<div class="main">
    <div class="panel panel-info">
        <div class="panel-heading">筛选</div>
        <div class="panel-body">
            <form action="./index.php" method="get" class="form-horizontal" role="form">
                <input type="hidden" name="c" value="site" />
                <input type="hidden" name="a" value="entry" />
                <input type="hidden" name="m" value="awt_enroll" />
                <input type="hidden" name="do" value="match" />
                <input type="hidden" name="p"  value="match" />
                <input type="hidden" name="op" value="display" />
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">关键字</label>
                    <div class="col-xs-12 col-sm-8 col-lg-9">
                        <input class="form-control" name="keyword" id="" type="text" value="<?php  echo $_GPC['keyword'];?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">状态</label>
                    <div class="col-xs-12 col-sm-8 col-lg-9">
                        <select name="status" class='form-control'>
                            <option value="1" <?php  if($_GPC['is_show'] != '0') { ?> selected<?php  } ?>>显示</option>
                            <option value="0" <?php  if($_GPC['is_show'] == '0') { ?> selected<?php  } ?>>不显示</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-1 control-label">分类</label>
                    <div class="col-sm-8 col-xs-12">

                    </div>
                    <div class="col-xs-12 col-sm-2 col-lg-2">
                        <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
                    </div>
                </div>

                <div class="form-group">
                </div>
            </form>
        </div>
    </div>

    <form action="" method="post">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                <table class="table table-hover">
                    <thead class="navbar-inner">
                    <tr>
                        <th style="width:60px;">ID</th>
                        <th style="width:80px;">排序</th>
                        <th>活动标题</th>
                        <th >分类</th>
                        <th >类型</th>
                        <th >活动地点</th>
                        <th style='width:80px;'>参赛费用</th>
                        <th style='width:146px;'>上次更新时间</th>
                        <th >状态</th>
                        <th style="">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  if(is_array($list)) { foreach($list as $item) { ?>
                    <tr>

                        <td><?php  echo $item['id'];?></td>
                        <td>
                            <?php if(cv('shop.goods.edit')) { ?>
                            <input type="text" class="form-control" name="displayorder[<?php  echo $item['id'];?>]" value="<?php  echo $item['displayorder'];?>">
                            <?php  } else { ?>
                            <?php  echo $item['displayorder'];?>
                            <?php  } ?>
                        </td>
                        <td>
                            <?php  echo $item['title'];?>
                        </td>
                        <td><?php  echo $item['catename'];?></td>
                        <td><?php  echo $item['type'];?></td>
                        <td><?php  echo $item['location'];?></td>
                        <td><?php  echo $item['fee'];?></td>
                        <td><?php  echo $item['updatetime'];?></td>
                        <td>
                            <label data='<?php  echo $item['is_show'];?>' class='label  label-default <?php  if($item['is_show']==1) { ?>label-info<?php  } ?>' <?php if(cv('shop.goods.edit')) { ?>onclick="setProperty(this,<?php  echo $item['id'];?>,'is_show')"<?php  } ?>><?php  if($item['is_show']==1) { ?>显示<?php  } else { ?>不显示<?php  } ?></label>
                        </td>
                        <td>
                            <?php if(cv('shop.goods.edit|shop.goods.view')) { ?><a href="<?php  echo $this->createWebUrl('match/match', array('id' => $item['id'], 'op' => 'post'))?>"class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="<?php if(cv('shop.goods.edit')) { ?>编辑<?php  } else { ?>查看<?php  } ?>"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<?php  } ?>
                            <?php if(cv('shop.goods.delete')) { ?><a href="<?php  echo $this->createWebUrl('match/match', array('id' => $item['id'], 'op' => 'delete'))?>" onclick="return confirm('确认删除此活动？');return false;" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="删除"><i class="fa fa-times"></i></a><?php  } ?>
                        </td>
                    </tr>
                    <?php  } } ?>
                    <tr>
                        <td colspan='9'>
                            <?php if(cv('shop.goods.add')) { ?>
                            <a class='btn btn-default' href="<?php  echo $this->createWebUrl('match/match',array('op'=>'post'))?>"><i class='fa fa-plus'></i> 添加活动</a>
                            <?php  } ?>
                            <?php if(cv('shop.goods.edit')) { ?>
                            <input name="submit" type="submit" class="btn btn-primary" value="提交排序">
                            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                            <?php  } ?>

                        </td>
                    </tr>

                    </tr>
                    </tbody>
                </table>
                <?php  echo $pager;?>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    require(['bootstrap'], function ($) {
        $('.btn').hover(function () {
            $(this).tooltip('show');
        }, function () {
            $(this).tooltip('hide');
        });
    });

    var category = <?php  echo json_encode($children)?>;
    function setProperty(obj, id, type) {
        $(obj).html($(obj).html() + "...");
        $.post("<?php  echo $this->createWebUrl('match/match')?>"
                , {'op':'setgoodsproperty',id: id, type: type, data: obj.getAttribute("data")}
                , function (d) {
                    $(obj).html($(obj).html().replace("...", ""));

                    if (type == 'is_show') {
                        $(obj).html(d.data == '1' ? '显示' : '不显示');
                    }
                    $(obj).attr("data", d.data);
                    if (d.result == 1) {
                        $(obj).toggleClass("label-info");
                    }
                }
                , "json"
        );
    }

</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>
