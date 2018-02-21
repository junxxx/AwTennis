<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<script language="javascript" src="../addons/awt_enroll/static/js/dist/mui/js/mui.min.js"></script>
<link href="../addons/awt_enroll/static/js/dist/mui/css/mui.min.css" rel="stylesheet"/>
<title>我的活动</title>
<style>
    .credit_no {height:100px; width:100%; margin:50px 0px 60px; color:#ccc; font-size:12px; text-align:center;}
    .order_topbar {height:44px; width:100%; background:#fff; border-bottom:1px solid #e3e3e3;}
    .order_topbar .nav {height:44px; width:33%;line-height:44px; text-align:center; font-size:14px; float:left; color:#666;}
    .order_topbar .on {height:42px; color:#f15353; border-bottom:2px solid #f15353;}
</style>
<body>
<div id="container"></div>
<script id="activity_main" type="text/html">
    <div class="mui-content">
        <div class="order_topbar">
            <div class="nav <?php  if($_GPC['type']=='0') { ?>on<?php  } ?>" data-type="0">正选</div>
            <div class="nav <?php  if($_GPC['type']=='1') { ?>on<?php  } ?>" data-type="1">替补</div>
            <div class="nav <?php  if($_GPC['type']=='2') { ?>on<?php  } ?>"  data-type=2>退赛</div>
        </div>
        <div id="list"></div>
    </div>
</script>
<script id="activity_empty" type="text/html">
    <div class="credit_no"><i class="fa fa-file-text-o" style="font-size:100px;"></i><br><span style="line-height:18px; font-size:16px;">暂时没有任何活动记录~</span></div>
</script>
<script id="activity_list" type="text/html">
    <ul class="mui-table-view">
        <%each list as log%>
        <li class="mui-table-view-cell mui-media">
            <a href="javascript:;">
                <img class="mui-media-object mui-pull-left" src="<%log.headimg%>">
                <div class="mui-media-body">
                    <%log.title%>
                    <p class='mui-ellipsis'><%log.activity_stime%><br><%log.location%></p>
                </div>
            </a>
        </li>
        <%/each%>
    </ul>
</script>
<script language="javascript">
    require(['tpl', 'core'], function (tpl, core) {
        var type = $('.mui-control-item').data('type');

        core.json('member/myActivity',{'type':"<?php  echo $_GPC['type'];?>"},function(json) {
            if(json.status) {
                $("#container").html(tpl("activity_main"));
                $('.nav').click(function() {
                    var type = $(this).data('type');
                    location.href = core.getUrl('member/myActivity', {type: type});
                })
                var list = json.result.list;
                if (list.length <= 0)
                {
                    $("#list").html(tpl("activity_empty",json.result));
                }
                $("#list").html(tpl("activity_list",json.result));
            }
        },true);
    })
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
