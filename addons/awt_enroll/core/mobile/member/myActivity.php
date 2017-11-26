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
    $activityModel =  m('activity');
    $list = $activityModel->getActivitiesByMid($openid);
    if($list) {
        foreach ($list as &$row) {
            $row['reserve_status'] = $activityModel::$enrollStatus[$row['reserve_status']];
        }
    }

    show_json(1,array('member' => $member, 'list' => $list));
}

include $this->template('member/myActivity');