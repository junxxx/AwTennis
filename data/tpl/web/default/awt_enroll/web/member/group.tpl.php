<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/member/tabs', TEMPLATE_INCLUDEPATH)) : (include template('web/member/tabs', TEMPLATE_INCLUDEPATH));?>
<script type="text/javascript" src="resource/js/lib/jquery-ui-1.10.3.min.js"></script>

<?php  if($operation == 'post') { ?>
<style type='text/css'>
    .tab-pane {
        padding: 20px 0 20px 0;
    }
</style>
<div class="main">
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data"
          onsubmit='return formcheck()'>
        <input type="hidden" name="id" value="<?php  echo $item['id'];?>"/>
        <div class="panel panel-default">
            <div class="panel-heading">
                添加分组
            </div>

            <div class="panel-body">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span
                            style="color:red">*</span>分组名称</label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" id='groupname' name="groupname" class="form-control"
                               value="<?php  echo $item['groupname'];?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1"/>
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
                        <input type="button" name="back" onclick='history.back()' <?php if(cv('article.list.add|article.list.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn
                        btn-default" />
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    require(['bootstrap'], function () {
        $('#myTab a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        })
    });

    require(['util'], function (u) {
        $('#cp').each(function () {
            u.clip(this, $(this).text());
        });
    })

    function formcheck() {
        if ($("#groupname").isEmpty()) {
            Tip.focus("#groupname", "请输入分组名称!");
            return false;
        }
        return true;
    }

</script>

<?php  } else if($operation == 'display') { ?>

<div class="main">
    <div class="panel panel-info">
        <div class="panel-heading">用户分组</div>
        <div class="panel-body">

        </div>
    </div>

    <form action="" method="post">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                <table class="table table-hover">
                    <thead class="navbar-inner">
                    <tr>
                        <th style="width:60px;">ID</th>
                        <th>分组名称</th>
                        <th style="">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  if(is_array($list)) { foreach($list as $item) { ?>
                    <tr>
                        <td><?php  echo $item['id'];?></td>
                        <td><?php  echo $item['groupname'];?></td>
                        <td> <?php if(cv('shop.goods.edit|shop.goods.view')) { ?><a
                                href="<?php  echo $this->createWebUrl('member/group', array('id' => $item['id'], 'op' => 'post'))?>"
                                class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top"
                                title="<?php if(cv('shop.goods.edit')) { ?>编辑<?php  } else { ?>查看<?php  } ?>"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<?php  } ?>
                            <?php if(cv('shop.goods.delete')) { ?><a
                                    href="<?php  echo $this->createWebUrl('member/group', array('id' => $item['id'], 'op' => 'delete'))?>"
                                    onclick="return confirm('确认删除此分组？');return false;" class="btn btn-default btn-sm"
                                    data-toggle="tooltip" data-placement="top" title="删除"><i
                                    class="fa fa-times"></i></a><?php  } ?>
                        </td>
                    </tr>
                    <?php  } } ?>
                    <tr>
                        <td colspan='9'>
                            <?php if(cv('shop.goods.add')) { ?>
                            <a class='btn btn-default'
                               href="<?php  echo $this->createWebUrl('member/group',array('op'=>'post'))?>"><i
                                    class='fa fa-plus'></i> 添加分组</a>
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
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>
