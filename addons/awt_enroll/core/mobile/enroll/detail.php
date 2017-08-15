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
            $where = ' WHERE 1';
            $condition = " and uniacid=:uniacid and is_show=:is_show and id=:id ";
            $params = array(
                ':uniacid' => $uniacid,
                ':is_show' => 1,
                ':id' => $id
            );
            $SQL = ' SELECT * FROM '.tablename($tablename).$where.$condition;
            $activity = pdo_fetch($SQL, $params);
            if(empty($activity)){
                show_json(0,'活动不存在');
            }
            $activity['headimg'] = tomedia($activity['headimg']);
            $activity['sign_stime'] = date('Y-m-d H:i',$activity['sign_stime']);
            $activity['activity_stime'] = date('Y-m-d H:i',$activity['activity_stime']);
            show_json(1,array('activity' => $activity));
        }
	}
}
include $this->template('enroll/detail');