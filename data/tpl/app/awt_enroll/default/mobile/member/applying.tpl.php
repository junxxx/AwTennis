<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<script language="javascript" src="../addons/awt_enroll/static/js/dist/mui/js/mui.min.js"></script>
<!--<script src="http://cdn.bootcss.com/blueimp-md5/1.1.0/js/md5.min.js"></script>-->
<link href="../addons/awt_enroll/static/js/dist/mui/css/mui.min.css" rel="stylesheet"/>
<title>个人中心</title>
<body>
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">申请会员</h1>
</header>
<div class="mui-content">
    <div id="slider" class="mui-slider">
        <div class="mui-scroll-wrapper mui-slider-indicator mui-segmented-control mui-segmented-control-inverted">
            <div class="mui-scroll">
                <a class="mui-control-item mui-active" href="#oldMember">老会员</a>
                <a class="mui-control-item" href="#newMember">新会员</a>
            </div>
        </div>
        <div class="mui-slider-group">
            <div id="oldMember" class="mui-slider-item mui-control-content mui-active">
                <form class="mui-input-group">
                    <div class="mui-input-row">
                        <label for="cardNum">会员卡号</label>
                        <input type="text" name="cardNum" id="cardNum" class="mui-input-clear" placeholder="请输入会员卡号">
                    </div>
                    <div class="mui-button-row">
                        <button type="button" id="om_confirm_btn" class="mui-btn mui-btn-primary">确认</button>
                        <button type="button" id="om_cancel_btn" class="mui-btn mui-btn-danger cancel_btn">取消</button>
                    </div>
                </form>
            </div>
            <div id="newMember" class="mui-slider-item mui-control-content">
                <form class="mui-input-group">
                    <div class="mui-input-row">
                        <label for="username">俱乐部昵称</label>
                        <input type="text" name="username" id="username" class="mui-input-clear" placeholder="请输入昵称">
                    </div>
                    <div class="mui-input-row">
                        <label for="password">密码</label>
                        <input type="password" name="password" id="password" class="mui-input-clear mui-input-password" placeholder="请输入密码">
                    </div>
                    <div class="mui-input-row change">
                        <label>性别</label>
                        <div class="mui-input-row mui-radio mui-inline mui-left">
                            <input type="radio" name="sex" id="male" value="false" checked/>
                            <label for="male">男</label>
                        </div>
                        <div class="mui-input-row mui-radio mui-inline mui-left">
                            <input type="radio" name="sex" id="female" value="true"/>
                            <label for="female">女</label>
                        </div>
                    </div>
                    <div class="mui-input-row">
                        <label for="phone">手机号</label>
                        <input type="text" name="phone" id="phone" class="mui-input-clear" placeholder="请输入手机号"/>
                    </div>
                    <div class="mui-input-row">
                        <label for="forehand">正手</label>
                        <select name="forehand" id="forehand" class="mui-select">
                            <option value="1" selected>右手</option>
                            <option value="2">左手</option>
                            <option value="3">双手</option>
                        </select>
                    </div>
                    <div class="mui-input-row">
                        <label for="backhand">反手</label>
                        <select name="backhand" id="backhand" class="mui-select">
                            <option value="false" selected>双反</option>
                            <option value="true">单反</option>
                        </select>
                    </div>
                    <div class="mui-button-row">
                        <button type="button" id="nm_confirm_btn" class="mui-btn mui-btn-primary">确认</button>
                        <button type="button" id="nm_cancel_btn" class="mui-btn mui-btn-danger cancel_btn">取消</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
<script language="javascript">
    var param = {};

    require(['tpl', 'core', 'md5'], function (tpl, core, md5) {
        $("#om_confirm_btn").bind("click", function () {
           param['behavior'] = 'binding';
           param['cardNum'] = $("#cardNum").val().trim();
           if(param['cardNum'] === ''){
               core.tip.show("请输入会员卡号", 'bottom', 2000);
           }else{
               doAjax(tpl, core, param);
           }
         });
        $("#nm_confirm_btn").bind("click", function () {
            param['behavior'] = 'create';
            param['username'] = $("#username").val().trim();
            param['password'] = $("#password").val();
            param['sex'] = $("input[name='sex'][checked]").val();
            param['phone'] = $("#phone").val().trim();
            param['forehand'] = $("#forehand option:selected").val();
            param['backhand'] = $("#backhand option:selected").val();
            var isValid = true;
            core.tip.show(param['sex']);
            var regUsername = /^[\u4E00-\u9FA5A-Za-z0-9_]{1,15}$/;
            if(!regUsername.test(param['username'])){
                isValid = false;
                core.tip.show("用户名只能包含[汉字/英文字母/数字], 长度1~15位", 'bottom', 3000);
            }
            var regPassword = /^\w{6,8}$/;
            if(isValid && !regPassword.test(param['password'])){
                isValid = false;
                core.tip.show("密码只能包含[数字/英文字母/下划线], 长度6~8位", 'bottom', 3000);
            }else{
                param['password'] = md5(param['password']);
            }
            var regPhone = /^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/;
            if(isValid && !regPhone.test(param['phone'])){
                isValid = false;
                core.tip.show("手机号不合法", 'bottom', 2000);
            }

            if(isValid){
                doAjax(tpl, core, param);
            }

        });

        $(".cancel_btn").bind("click", function(){
            backToCenter();
        });
    });

    function doAjax(tpl, core, param) {
        core.json('member/card',param,function(json) {
            switch(json.status) {
                case 0:
                    core.tip.show(json.result, 'bottom', 1500);
                    // mui.toast(json.result,{duration:'short', type:'div'});
                    break;
                case 1:
//                    var result = json.result;
                    // console.log(result);
//                    switch(result.status){
//                        case 200:
//                                //break;
//                        case 201:
//                            core.tip.show("恭喜您，申请会员成功！");
//                            backToCenter();
//                            break;
//                        // case 400:
//                        //     core.tip.show(result.msg);
//                        //     break;
//                        // case 404:
//                        //     core.tip.show(result.msg);
//                        //     break;
//                        default:
//                            core.tip.show("绑定失败，请重新绑定!");
//                    }
                    core.tip.show(json.result, 'bottom', 1000);
                    //返回个人中心
                    backToCenter();
                    break;
            }

        },true);
    }

    function backToCenter(){
        window.location.href = "<?php  echo $this->createMobileUrl('member/center')?>";
    }
</script>
