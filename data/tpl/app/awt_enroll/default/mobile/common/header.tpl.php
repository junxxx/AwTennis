<?php defined('IN_IA') or exit('Access Denied');?><!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <link href="../addons/awt_enroll/static/css/font-awesome.min.css" rel="stylesheet">
        <link href="../addons/awt_enroll/static/font/iconfont.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">
        <meta name="format-detection" content="telephone=no" />
        <script>var require = {urlArgs: 'v=<?php  echo date('YmdHis');?>'};</script>
        <script language="javascript" src="../addons/awt_enroll/static/js/require.js"></script>
        <script language="javascript" src="../addons/awt_enroll/static/js/app/config.js"></script>
        <script language="javascript" src="../addons/awt_enroll/static/js/dist/jquery-1.11.1.min.js"></script>
        <script language="javascript" src="../addons/awt_enroll/static/js/dist/jquery.gcjs.js"></script>
       
        <link rel="stylesheet" type="text/css" href="../addons/awt_enroll/template/mobile/default/static/css/style.css">



        <script>
            var _hmt = _hmt || [];
            (function() {
                var hm = document.createElement("script");
                hm.src = "//hm.baidu.com/hm.js?585e0487e8586a704e59b42b01b750e1";
                var s = document.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(hm, s);
            })();
        </script>

    </head>
    <body>
<script language="javascript">
    require(['core','tpl'],function(core,tpl){
        core.init({
            siteUrl: "<?php  echo $_W['siteroot'];?>",
            baseUrl: "<?php  echo $this->createMobileUrl('ROUTES')?>"
        });
       
    })
</script>
<?php  if(is_array($this->header)) { ?>
<div class="follow_topbar">
    <div class="headimg">
        <img src="<?php  echo $this->header['icon']?>">
    </div>
    <div class="info">
        <div class="i"><?php  echo $this->header['text']?></div>
        <div class="i">关注公众号，享专属服务</div>
    </div>
    <div class="sub" onclick="location.href='<?php  echo $this->header['url']?>'">立即关注</div></div>
<div style='height:44px;'>&nbsp;</div>
<?php  } ?>