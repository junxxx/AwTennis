<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
    <li <?php  if($_GPC['p'] == 'list' || empty($_GPC['p'])) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('article/list')?>">文章管理</a></li>
</ul>
<?php  if($operation == 'display') { ?>
<form action="" method="post">
    <div class="panel panel-default">
        <div class="panel-body table-responsive">
            <table class="table table-hover">
                <thead class="navbar-inner">
                <tr>
                    <th style="width:30px;">ID</th>
                    <th>标题</th>
                    <th>状态</th>
                    <th >操作</th>
                </tr>
                </thead>
                <tbody>
                <?php  if(is_array($list)) { foreach($list as $row) { ?>
                <tr>
                    <td><?php  echo $row['id'];?></td>
                    <td><?php  echo $row['title'];?></td>
                    <td>
                        <?php  if($row['is_display']==1) { ?>
                        <span class='label label-success'>显示</span>
                        <?php  } else { ?>
                        <span class='label label-danger'>不显示</span>
                        <?php  } ?>
                    </td>
                    <td style="text-align:left;">
                        <?php if(cv('article.article.view|article.article.edit')) { ?><a href="<?php  echo $this->createWebUrl('article/list', array('op' => 'post', 'id' => $row['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="<?php if(cv('article.list.edit')) { ?>修改<?php  } else { ?>查看<?php  } ?>"><i class="fa fa-edit"></i></a><?php  } ?>
                        <?php if(cv('article.article.delete')) { ?><a href="<?php  echo $this->createWebUrl('article/list', array('op' => 'delete', 'id' => $row['id']))?>"class="btn btn-default btn-sm"
                                                         onclick="return confirm('确认删除此文章?')"
                                                         title="删除"><i class="fa fa-times"></i></a><?php  } ?>
                    </td>
                </tr>
                <?php  } } ?>
                <tr>
                    <td colspan='6'>
                        <?php if(cv('article.article.add')) { ?>
                        <a class='btn btn-default' href="<?php  echo $this->createWebUrl('article/list',array('op'=>'post'))?>"><i class='fa fa-plus'></i> 添加文章</a>
                        <?php  } ?>

                        <?php if(cv('article.article.edit')) { ?>
                        <input name="submit" type="submit" class="btn btn-primary" value="提交排序">
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                        <?php  } ?>

                    </td>
                </tr>
                </tbody>
            </table>
            <?php  echo $pager;?>
        </div>
    </div>
</form>

<?php  } else if($operation == 'post') { ?>

<div class="main">
    <form     action="" method="post" class="form-horizontal form" enctype="multipart/form-data" onsubmit='return formcheck()'>
    <input type="hidden" name="id" value="<?php  echo $article['id'];?>" />
    <div class="panel panel-default">
        <div class="panel-heading">
            文章设置
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">链接(点击复制)</label>
                <div class="col-sm-9 col-xs-12">
                    <p class='form-control-static'>
                        <a href='javascript:;' title='点击复制连接' id='cpdetail'><?php  echo $this->createMobileUrl('aboutus/detail',array('id' => $article['id']))?></a>
                    </p>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>文章标题</label>
                <div class="col-sm-9 col-xs-12">
                    <input type="text" id='title' name="title" class="form-control" value="<?php  echo $article['title'];?>" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">文章内容</label>
                <div class="col-sm-9 col-xs-12">
                    <?php  echo tpl_ueditor('detail',$article['detail'])?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否显示</label>
                <div class="col-sm-9 col-xs-12">
                    <label class='radio-inline'>
                        <input type='radio' name='is_display' value=1' <?php  if($article['is_display']==1) { ?>checked<?php  } ?> /> 显示
                    </label>
                    <label class='radio-inline'>
                        <input type='radio' name='is_display' value=0' <?php  if($article['is_display']==0) { ?>checked<?php  } ?> /> 不显示
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                <div class="col-sm-9 col-xs-12">
                    <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1"  />
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    <input type="button" name="back" onclick='history.back()' <?php if(cv('article.list.add|article.list.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
                </div>
            </div>
        </div>
    </div>
    </form>
</div>

<script language='javascript'>
    require(['bootstrap'], function ($) {
        $('.btn').hover(function () {
            $(this).tooltip('show');
        }, function () {
            $(this).tooltip('hide');
        });
    });
    require(['util'],function(u){
        $('#cpdetail').each(function(){
            u.clip(this, $(this).text());
        });
    })
    function formcheck() {
        if ($("#title").isEmpty()) {
            Tip.focus("title", "请填写文章标题!");
            return false;
        }
        return true;
    }
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>