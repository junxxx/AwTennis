<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<script language="javascript" src="../addons/awt_enroll/static/js/dist/mui/js/mui.min.js"></script>
<link href="../addons/awt_enroll/static/js/dist/mui/css/mui.min.css" rel="stylesheet"/>
<title>活动列表</title>
<body>
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">活动列表页</h1>
</header>
<div id='container'></div>

<script id='tpl_list' type='text/html'>
    <%each list as activity%>

    <div class="mui-content">
        <div class="mui-card" onclick="goDetail('<%activity.id%>')">
            <div class="mui-card-header mui-card-media" style="height:40vw;background-image:url(<%activity.headimg%>)"></div>
            <div class="mui-card-content">
                <p class="color-gray">活动时间 <%activity.activity_stime%></p>
                <p><%activity.title%></p>
            </div>
        </div>
    </div>
    <%/each%>
</script>
<script id='tpl_empty' type='text/html'>
    <div class="team_no"><br><span style="line-height:18px; font-size:16px;">还没有相关活动信息~</span></div>
</script>

<script language="javascript">
    var page = 1;
    var current_level = 1;
    require(['tpl', 'core'], function (tpl, core) {

        function bindScroller(){

            //加载更多
            var loaded = false;
            var stop = true;
            var starttime = $('#starttime').val();
            var endtime = $('#endtime').val();
            $(window).scroll(function () {
                if (loaded) {
                    return;
                }
                $('#team_loading').remove();
                totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
                if ($(document).height() <= totalheight) {

                    if (stop == true) {
                        stop = false;
                        $('#container').append('<div id="team_loading"><i class="fa fa-spinner fa-spin"></i> 正在加载...</div>');
                        page++;
                        core.json('enroll/list', {page: page, starttime: starttime, endtime: endtime}, function (morejson) {
                            stop = true;
                            $('#team_loading').remove();
                            $("#container").append(tpl('tpl_list', morejson.result));
                            if (morejson.result.list.length < morejson.result.pagesize) {
                                $("#container").append('<div id="team_loading">已经加载完全部数据</div>');
                                loaded = true;
                                $(window).scroll = null;
                                return;
                            }
                        }, true);
                    }
                }
            });
        }

        function getActivities() {
            var starttime = $('#starttime').val();
            var endtime = $('#endtime').val();
            core.json('enroll/list', {page: page, starttime: starttime, endtime: endtime}, function (json) {
                if (json.result.list.length <= 0) {
                    $('#container').html(tpl('tpl_empty'));
                    return;
                }
                $('#container').html(tpl('tpl_list', json.result));
                bindScroller();
            }, true);
        }
        $('#search').click(function () {
            getActivities();
        });

        getActivities();

    })

    function goDetail(activityId)
    {
        activityId = parseInt(activityId);
        if (activityId <= 0 )
            return;
        var url = "<?php  echo $this->createMobileUrl('enroll/detail')?>&id="+activityId;
        window.location.href = url;
    }
</script>
