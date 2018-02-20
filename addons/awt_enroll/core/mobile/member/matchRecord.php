<?php
/**
 * 会员卡
 * Created by PhpStorm.
 * User: panj
 * Date: 2017/8/22
 * Time: 11:18
 */
require_once AWT_ENROLL_CORE . 'common/ApiAWTennis.class.php';

defined('IN_IA') or exit('Access Denied!');
global $_W, $_GPC;
//
//if ($_W['isajax']) {
//
//    $message = null;
//
//    //todo 个人比赛记录
//
//    //将api返回的数据传送到前端页面
//    show_json($status, $message);
//}

include $this->template('member/matchRecord');

