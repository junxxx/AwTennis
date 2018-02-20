<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<script language="javascript" src="../addons/awt_enroll/static/js/dist/mui/js/mui.min.js"></script>
<link href="../addons/awt_enroll/static/js/dist/mui/css/mui.min.css" rel="stylesheet"/>
<title>会员卡</title>
<body>
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">会员卡</h1>
</header>
<div class="mui-content" id="container">
    <div>会员卡号：</div>
    <div class="mui-button-row">
        <button type="button" id="btn_unbinding" class="mui-btn mui-btn-primary">解除会员</button>
    </div>
</div>
</body>
<script language="javascript">
    var param = {};

    require(['tpl', 'core'], function (tpl, core) {
        doAjax(tpl, core, param);
        $("#btn_unbinding").bind("click", function () {
//           ajax(tpl, core, param);
            core.tip.show("解除会员成功");
         });

    });

    function doAjax(tpl, core, param) {
        core.json('member/card',param,function(json) {
            //do something
            switch(json.status) {
                case 0:
                    core.tip.show(json.result);
                    // mui.toast(json.result,{duration:'short', type:'div'});
                    break;
                case 1:
                    var result = json.result;
                    // console.log(result);
                    switch(result.status){
                        case 200:
                                //break;
                        case 201:
                            core.tip.show("恭喜您，申请会员成功！");

                            break;
                        // case 400:
                        //     core.tip.show(result.msg);
                        //     break;
                        // case 404:
                        //     core.tip.show(result.msg);
                        //     break;
                        default:
                            core.tip.show("绑定失败，请重新绑定!");
                    }
                    break;
            }

        },true);
    }

</script>
