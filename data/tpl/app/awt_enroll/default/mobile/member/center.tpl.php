<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<script language="javascript" src="../addons/awt_enroll/static/js/dist/mui/js/mui.min.js"></script>
<link href="../addons/awt_enroll/static/js/dist/mui/css/mui.min.css" rel="stylesheet"/>
<link href="../addons/awt_enroll/static/css/baseStyle.css" rel="stylesheet"/>
<link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
<title>个人中心</title>
<style>
    :root {
        --top-all: 50px;
    }
    /*body{*/
        /*margin: 0;*/
        /*padding: 0;*/
        /*height: 100%;*/
    /*}*/
    #container {
        width: 100%;
        height: 100%;
        /*overflow: scroll;*/
    }

    .borderBlue {
        /*border: 1px solid steelblue;*/
    }

    .header {
        width: 100%;
        height: 45%;

        /*子元素居中显示*/
        align-items: center;
        display: flex;
        justify-content: center;

        /*filter: blur(2px); !*模糊效果*!*/

        margin: 0;
        padding: 0;
        background: url(../addons/awt_enroll/static/images/header_bg.jpg) no-repeat fixed;

        color: white; /*字体颜色*/

        position: relative;
        z-index: -1;
    }
    .header > .profile-main {
        margin: 0;
        padding: 0;
        width: 105px;
        height: 150px;

        text-align: center;

    }

    .profile-main > .user-profile {
        /*position: absolute;*/
        /*float: left;*/
        /*left: 36.5%;*/
        /*top: 10%;*/

        margin: 0;
        padding: 0;
        width: 100%;
        height: 105px;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
        box-shadow: 2px 2px 20px steelblue;
        z-index: 10;
    }
    .user-profile > img {
        margin: 0;
        padding: 0;

        width: 100%;
        height: 100%;

        /*-webkit-border-radius: 50%;*/
        /*-moz-border-radius: 50%;*/
        border-radius: 100%;
    }
    .weChatName {
        width: 100%;
        position: relative;
        font-weight: bold;
    }
    .location {
        font-weight: lighter;
        font-size: large;
        font-style: italic;
    }

    .header > .personalHonor{
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 50px;
    }

    .personalHonor > .personalRanking {
        position: relative;
        float: left;
        /*left: 0;*/

        width: 33%;
        height: 100%;
        margin: 0;
        padding: 5px;

        text-align: center;
    }

    .personalHonor > .nSingleTitles {
        position: relative;
        float: left;
        /*left: 33%;*/
        width: 34%;
        height: 100%;
        margin: 0;
        padding: 5px;
        text-align: center;
    }

    .personalHonor > .nDoubleTitles {
        position: relative;
        float: left;
        /*left: 66%;*/
        width: 33%;
        height: 100%;
        margin: 0;
        padding: 5px;
        text-align: center;
    }

    /*俱乐部基本信息*/
    .basicClubInfo_main {
        position: relative;
        float: left;

        width: 100%;
        height: 110px;

        margin: 0;
        padding: 5px 10px 5px 10px;

    }

    .basicClubInfo_main > .basicClubInfo_left {
        position: relative;
        float: left;

        border-right: 1px solid lightgrey;

        width: 40%;
        height: 100px;
    }

    .basicClubInfo_main > .basicClubInfo_right {
        position: relative;
        float: right;

        width: 40%;
        height: 100px;
    }
    /*子元素选择器*/
    .basicClubInfo_left > table {
        width: 100%;
        height: 100%;
        text-align: center;
     }

    .basicClubInfo_right > table {
        width: 100%;
        height: 100%;
        text-align: center;
    }
    /*后代选择器*/
    .basicClubInfo_left th, .basicClubInfo_right th {
        width: 50%;
    }

    /*比赛记录和排名信息*/
    .detailClubInfo_main {
        position: relative;
        float: left;

        width: 100%;
        height: 50px;

        margin: 2px 0 0 0;
        padding: 10px;

    }

    /*比赛记录*/
    .detailClubInfo_main > .detailClubInfo_left {
        position:relative;
        float: left;

        width: 100%;
        height: auto;

        margin: 0;
        padding: 0;

        border-right: lightgrey 1px solid;

        text-align: center;
    }

    /*排名信息*/
    .detailClubInfo_main > .detailClubInfo_right {
        position:relative;
        float: left;

        width: 100%;
        height: auto;

        margin: 0;
        padding: 0;

        text-align: center;
    }

    /*我的活动*/
    .myActivity {
        position: relative;
        float: left;

        width: 100%;
        height: 50px;

        margin: 2px 0 0 0;
        padding: 10px;

    }

    /*会员卡号*/
    .cardNum {
        position: relative;
        float: left;
        display: block;

        width: 100%;
        height: 50px;

        margin: 2px 0 0 0;
        padding: 10px;

        /*vertical-align: middle;*/

        /*!*阴影效果*!*/
        /*box-shadow: steelblue 1px 1px 2px;*/
        /*!*字体*!*/
        /*font-family: "Microsoft YaHei", Consolas,  sans-serif;*/
    }

    .cardNum > div {
        position: relative;
        float: left;
        /*display: inline-block;*/

        width: 100%;
        height: auto;

        margin: 0;
        padding: 2px;

        text-align: center;
    }

    /*common style*/
    .shadow_posCenter_font {
        /*阴影效果*/
        box-shadow: steelblue 1px 1px 2px;

        /*子元素居中显示*/
        align-items: center;
        display: flex;
        justify-content: center;

        /*字体*/
        font-family: "Microsoft YaHei", Consolas,  sans-serif;

    }
</style>
<body>
<!--<header class="mui-bar mui-bar-nav">-->
    <!--<h1 class="mui-title">个人中心</h1>-->
<!--</header>-->
<div id="container"></div>
<script id="member_main" type="text/html">
    <div class="header borderBlue">
        <div class="profile-main">
            <div class="user-profile">
                <%if weChatInfo['avatar']%>
                    <img id="profile" src="<% weChatInfo['avatar'] %>">
                <%else%>
                    <img id="profile_default" src="../addons/awt_enroll/static/images/head-pic.jpg">
                <%/if%>
            </div>
            <div class="weChatName borderBlue">
                <% weChatInfo['nickname'] %>
                <%if weChatInfo['gender'] == 0%>
                    <i class="fa fa-mars fa-sm"></i>
                <%else%>
                     <i class="fa fa-venus fa-sm"></i>
                <%/if%>
            </div>
            <div class="location borderBlue">
                <i class="fa fa-map-marker fa-sm"></i>
                <%if weChatInfo['province'] != ""%>
                <% weChatInfo.province %>
                <%else%>
                --
                <%/if%>
                <%if weChatInfo['city'] != ""%>
                <% weChatInfo.city %>
                <%else%>
                --
                <%/if%>
            </div>
        </div>

        <div class="personalHonor borderBlue">
            <div class="personalRanking borderGrey">
                <div class="fontLarge">
                <%if clubInfo && clubInfo['individualRank']['混合单打']['currentRank']%>
                    <% clubInfo['individualRank']['混合单打']['currentRank'] %>
                <%else%>
                    --
                <%/if%>
                </div>
                <div class="fontSmall">当前排名</div>
            </div>
            <div class="nSingleTitles borderGrey">
                <div class="fontLarge">
                    <%if clubInfo && clubInfo['profile']['singleTitles']%>
                        <% clubInfo['profile']['singleTitles'] %>
                    <%else%>
                        0
                    <%/if%>
                </div>
                <div class="fontSmall">单打冠军</div>

            </div>
            <div class="nDoubleTitles borderGrey">
                <div class="fontLarge">
                    <%if clubInfo && clubInfo['profile']['doubleTitles']%>
                        <% clubInfo['profile']['doubleTitles'] %>
                    <%else%>
                        0
                    <%/if%>
                </div>
                <div class="fontSmall">双打冠军</div>
            </div>
        </div>
    </div>

    <%if clubInfo%>
    <div class="basicClubInfo_main shadow_posCenter_font borderBlue">
        <div class="basicClubInfo_left">
            <table>
                <tr>
                    <th>组别:</th>
                    <td><% clubInfo['profile']['grade']%></td>
                    <!--<td>银组</td>-->
                </tr>
                <tr>
                    <th>等级:</th>
                    <td><% clubInfo['profile']['level']%></td>
                </tr>
                <tr>
                    <th>球龄:</th>
                    <td><% clubInfo['profile']['playedYears']%></td>
                </tr>
            </table>
        </div>


        <div class="basicClubInfo_right">
            <table>
                <tr>
                    <th>正手:</th>
                    <td><% clubInfo['profile']['forehand']%></td>
                </tr>
                <tr>
                    <th>反手:</th>
                    <td><% clubInfo['profile']['backhand']%></td>
                </tr>
                <tr>
                    <th>球龄:</th>
                    <td><% clubInfo['profile']['playedYears']%></td>
                </tr>
            </table>
        </div>
    </div>


    <div class="detailClubInfo_main shadow_posCenter_font">
        <div class="detailClubInfo_left">
            比赛记录
        </div>
        <div class="detailClubInfo_right">
            全部排名
        </div>
    </div>

    <div id="myActivity" class="myActivity shadow_posCenter_font">我的活动</div>
    <%/if%>

    <div class="cardNum shadow_posCenter_font">
        <%if clubInfo && clubInfo['profile']['cardNum']%>
            <!--<div>会员卡号</div>-->
            <!--<div><% clubInfo['profile']['cardNum'] %></div>-->
            会员卡号: <% clubInfo['profile']['cardNum'] %>
        <%else%>
            <button id="btn_binding" class="mui-btn-primary">注册会员</button>
        <%/if%>
    </div>
</script>
<script language="javascript">

    require(['tpl', 'core'], function (tpl, core) {
        core.json('member',{},function(json) {
            if(json.status) {
                console.log(json.result);
                $("#container").html(tpl("member_main",json.result));

                $("#myActivity").bind('click', function() {
                   window.location.href = "<?php  echo $this->createMobileUrl('member/myActivity')?>";
                });

                $("#btn_binding").bind("click", function () {
                    window.location.href = "<?php  echo $this->createMobileUrl('member/card')?>";

                });
            }else{
                $("#container").html(json.result.weChatInfo);
            }

        },true);

    })
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
