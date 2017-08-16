<?php
/**
 * 前台活动报名业务逻辑处理
 * Created by PhpStorm.
 * User: panj
 * Date: 2017/8/16
 * Time: 10:54
 */
defined('IN_IA') or edit('Access Denied!');
global $_W, $_GPC;
$openid = m('user')->getOpenid();
$member = m('member')->getMember($openid);

if ($_W['isajax']) {
    $activityId = isset($_GPC['id']) ? intval($_GPC['id']) : '';
    if (!empty($activityId)){
        $activity = m('activity')->getActivityById($activityId);
        if (!empty($activity)){
            /*TODO 处理报名业务逻辑*/
            show_json(1,array('member' => $member, 'activity' => $activity));
        }
    }
    show_json(0,'活动不存在');
}

