<?php
/**
 * 个人中心
 * Created by PhpStorm.
 * User: panj, Fu
 * Date: 2017/8/15
 * Time: 14:41
 */
//require_once AWT_ENROLL_CORE . 'common/ApiAWTennis.class.php';
//if (!defined('IN_IA')) {
//    die('Access Denied');
//}
//global $_W, $_GPC;
////获取当前用户的openid
////sql table: awt_mc_mapping_fans
//$openid = m('user')->getOpenid();
////通过openid查询用户
////sql table: entroll_member
//$member = m('member')->getMember($openid);
//
////var_dump($member);exit;
//
//if ($_W['isajax']) {
//    //todo 个人全部排名
//
//    $rankInfo = null;
//
//    show_json($status, array('weChatInfo'=>$rankInfo));
//}

include $this->template('member/rankRecord');


