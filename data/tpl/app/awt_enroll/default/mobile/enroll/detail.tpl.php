<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<script language="javascript" src="../addons/awt_enroll/static/js/dist/mui/js/mui.min.js"></script>
<link href="../addons/awt_enroll/static/js/dist/mui/css/mui.min.css" rel="stylesheet"/>
<body>
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="javascript:void();" onclick="window.history.back(-1);"></a>
    <h1 class="mui-title">活动详情页</h1>
</header>
<div id='container'></div>

<script id='tpl_detail' type='text/html'>
    <div class="mui-content">
        <div class="mui-card">
            <div class="mui-card-header mui-card-media" style="height:40vw;background-image:url(<%activity.headimg%>)"></div>
            <div class="mui-card-content">
                <div class="mui-card-content-inner">
                    <ul class="mui-table-view">
                        <li class="mui-table-view-cell">
                            活动时间:<%activity.activity_stime%>
                        </li>
                        <li class="mui-table-view-cell">
                            活动地点:<%activity.location%>
                        </li>
                        <li class="mui-table-view-cell">
                            活动费用:<%activity.fee%>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <p style="color: #333;"><%activity.rule%></p>

        <div class="mui-content-padded" style="margin: 5px;text-align: center;">
            <button id='alertBtn' type="button" class="mui-btn mui-btn-blue mui-btn-outlined">立即报名</button>
        </div>
    </div>
</script>
<script id='tpl_empty' type='text/html'>
    <div class="team_no"><br><span style="line-height:18px; font-size:16px;">活动信息不存在</span></div>
</script>

<script language="javascript">
    mui.init();
    var id = "<?php  echo $_GPC['id'];?>";
    require(['tpl', 'core'], function (tpl, core) {
        function getActivitie() {
            core.json('enroll/detail', {id: id, }, function (json) {
                var status = json.status;
                if ( status == 1 ){
                    $('#container').html(tpl('tpl_detail', json.result));
                    document.getElementById("alertBtn").addEventListener('click', function() {
//                        mui.alert('欢迎使用', 'hello', function() {
//                            info.innerText = '你刚关闭了警告框';
//                        });
                        core.json('enroll/enroll',{id:id},function(json){
                            var status = json.status;
                            var result = json.result;
                            console.log(result);

                            mui.alert(result);
                        },true,true);
                    });
                }else {
                    $('#container').html(tpl('tpl_empty'));
                }
            }, true);
        }
        getActivitie();
    })
</script>
</body>
</html>