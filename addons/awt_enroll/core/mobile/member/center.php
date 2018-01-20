<?php
/**
 * 个人中心
 * Created by PhpStorm.
 * User: panj, Fu
 * Date: 2017/8/15
 * Time: 14:41
 */
require_once AWT_ENROLL_CORE . 'common/ApiAWTennis.class.php';
if (!defined('IN_IA')) {
    die('Access Denied');
}
global $_W, $_GPC;
//获取当前用户的openid
//sql table: awt_mc_mapping_fans
$openid = m('user')->getOpenid();
//通过openid查询用户
//sql table: entroll_member
$member = m('member')->getMember($openid);

//var_dump($member);exit;

if ($_W['isajax']) {
    //todo 需要判断用户是否已经绑定了俱乐部会员
    $status = 0;
    $weChatInfo = null;
    $clubInfo = null;
    if(is_null($member)||empty($member)){
        $weChatInfo = "获取用户信息失败，请重试";
    }else{
        $status = 1;
        $weChatInfo = $member;
        if(!is_null($member['memberid'])){
            //用户已注册会员
            $params["id"]=$member['memberid'];
            $apiAWTennis = new ApiAWTennis($params);
            $apiResponse = $apiAWTennis->getUser($member['memberid']);

            if(is_null($apiResponse)){
                $clubInfo = "获取用户信息失败，请重试";
            }else{
                $clubInfo = $apiResponse;
            }
        }
    }
    show_json($status, array('weChatInfo'=>$weChatInfo, 'clubInfo'=>$clubInfo));
}

include $this->template('member/center');


