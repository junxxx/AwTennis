<?php
/*手机端活动详情页面*/
if (!defined('IN_IA')) {
	die('Access Denied');
}
global $_W, $_GPC;
$uniacid = $_W['uniacid'];

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$tablename = 'enroll_activities';

if ($_W['isajax']) {
	if ($operation == 'display') {
        $id = intval($_GPC['id']);
        if (!empty($id)){
            $activity = m('activity')->getActivityById($id);
            if ( !empty($activity)){
                $activity['headimg'] = tomedia($activity['headimg']);
                $activity['sign_stime'] = date('Y-m-d H:i',$activity['sign_stime']);
                $activity['activity_stime'] = date('Y-m-d H:i',$activity['activity_stime']);
                show_json(1,array('activity' => $activity));
            }
        }
        show_json(0,'活动不存在');
    }
}
include $this->template('enroll/detail');