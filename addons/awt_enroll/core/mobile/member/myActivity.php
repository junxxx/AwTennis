<?php
/**
 * 我的活动
 * Created by PhpStorm.
 * User: panj
 * Date: 2017/8/15
 * Time: 14:41
 */
if (!defined('IN_IA')) {
    die('Access Denied');
}
global $_W, $_GPC;
$openid = m('user')->getOpenid();
$member = m('member')->getMember($openid);

if($_W['isajax']){
    show_json(1,array('member' => $member));
}


include $this->template('member/myActivity');