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

$type = isset($_GPC['type']) ? $_GPC['type'] : '';
// TODO 退赛的状态，还需另外处理

if ($_W['isajax']) {
    $activityModel = m('activity');
    $list = $activityModel->getActivitiesByMid($openid, $type);
    if ($list) {
        foreach ($list as &$row) {
            $row['headimg'] = tomedia($row['headimg']);
            $row['reserve_status'] = $activityModel::$enrollStatus[$row['reserve_status']];
            $row['activity_stime'] = date('Y-m-d H:i', $row['activity_stime']);
        }
    }

    show_json(1, array('member' => $member, 'list' => $list));
}

include $this->template('member/myActivity');
