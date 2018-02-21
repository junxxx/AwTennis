<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title>关于我们</title>
<style type="text/css">
    body {margin:0px; background:#efefef; font-family:'微软雅黑'; -moz-appearance:none;overflow-x:hidden}
    a {text-decoration:none;}
    .notice_topbar {height:40px; padding:3px;  background:#f9f9f9; border-bottom:1px solid #e8e8e8; font-size:16px; line-height:40px; text-align:center; color:#666;}
    .notice_topbar a {height:40px;margin-left:10px; width:15px; display:block; float:left; outline:0px; color:#999; font-size:24px;}

    .notice_addnav {height:44px; padding:5px;  border-bottom:1px solid #f0f0f0; border-top:1px solid #f0f0f0; margin-top:14px; line-height:42px; color:#666; background:#fff;}
    .notice_list {height:50px; padding:5px; border-bottom:1px solid #f0f0f0; border-top:1px solid #f0f0f0; margin-top:14px; background:#fff;}
    .notice_list .ico {height:50px; width:50px;  float:left; color:#999; }
    .notice_list .ico img { width:45px;height:45px;margin-top:3px;}
    .notice_list .info {height:50px; width:100%; float:left; margin-left:-50px;}
    .notice_list .info .inner { margin-left:55px;}
    .notice_list .info .inner .addr {height:20px; width:100%; color:#666; line-height:26px; font-size:14px; overflow:hidden;}
    .notice_list .info .inner .user {height:30px; width:100%; color:#999; line-height:30px; font-size:12px; overflow:hidden;}
    .notice_list .info .inner .user span {color:#444; font-size:16px;}

    .notice_addnav {height:40px; width:94%; padding:0 3%; border-bottom:1px solid #f0f0f0; border-top:1px solid #f0f0f0; margin-top:14px; line-height:40px; color:#666; }

    .notice_detail { width:100%;position:absolute;top:0;}
    .notice_main {height:auto;width:94%; padding:0px 3%; border-bottom:1px solid #f0f0f0; border-top:1px solid #f0f0f0; background:#fff;}
    .notice_main .title {height:auto;; width:94%; padding:0px 3%;line-height:22px;word-break: break-all;font-size:16px;color:#333;padding:5px;}
    .notice_main .time {height:30px; width:94%; padding:0px 3%; border-bottom:1px solid #f0f0f0; line-height:22px;word-break: break-all;font-size:14px;color:#666;}
    .notice_main .detail { height:100%;width:94%; padding:0px 3%; }
    .notice_main .detail img { width:100%;}
    .notice_sub1 {height:44px; width:94%; margin:14px 3% 0px; background:#ff4f4f; border-radius:4px; text-align:center; font-size:16px; line-height:44px; color:#fff;}
    .notice_sub2 {height:44px; width:94%; margin:14px 3% 0px; background:#ddd; border-radius:4px; text-align:center; font-size:16px; line-height:44px; color:#666; border:1px solid #d4d4d4;}

    .notice_no {height:100px; width:100%; margin:50px 0px 60px; color:#ccc; font-size:12px; text-align:center;}
    .notice_no_menu {height:40px; width:100%; text-align:center;}
    .notice_no_nav {height:38px;padding:10px; width:100px; background:#eee; border:1px solid #d4d4d4; border-radius:5px; text-align:center; line-height:38px; color:#666;}
    #notice_loading { width:94%;padding:10px;color:#666;text-align: center;}
</style>
<script type="text/javascript" src="../addons/awt_enroll/static/js/dist/area/cascade.js"></script>
<div id='container'></div>

<script id='tpl_notice_main' type='text/html'>
    <div class="page_topbar">
        <a href="javascript:;" class="back" onclick="history.back()"><i class="fa fa-angle-left"></i></a>
        <div class="title"><%title%></div>
    </div>

    <div id='notices'></div>
    <div id='detail_container'></div>
</script>

<script id='tpl_notice_data' type='text/html'>
    <div class="notice_detail">
        <div class="page_topbar">
            <a href="<?php  echo $this->createMobileUrl('article')?>" class="back" ><i class="fa fa-angle-left"></i></a>
            <div class="title"><%article.title%></div>
        </div>
        <div class="notice_main" >
            <div class="title"><%article.title%></div>
            <div class="time"><%article.createtime%></div>
            <div class="detail"><%=article.detail%></div>
        </div>
    </div>
</script>
<script id='tpl_empty' type='text/html'>
    <div class="notice_no"><i class="fa fa-volume-up" style="font-size:100px;"></i><br><span style="line-height:18px; font-size:16px;">暂时没有任何内容</span>
    </div>
</script>
<script language="javascript">
    var id = "<?php  echo $_GPC['id'];?>";
    require(['tpl', 'core'], function (tpl, core) {
        $('#container').html(tpl('tpl_notice_main'));

        core.json('aboutus/detail', {id:id}, function (json) {
            if(json.status == 0) {
                core.tip.show(json.result,'middle',4000);
            }else{
                var result = json.result;
                $('#detail_container').html(tpl('tpl_notice_data',result));
            }

        }, true);
    })
</script>
