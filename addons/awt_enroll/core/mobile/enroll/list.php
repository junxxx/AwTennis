<?php
/*手机端活动列表页面*/
if (!defined('IN_IA')) {
	die('Access Denied');
}
global $_W, $_GPC;
$uniacid = $_W['uniacid'];

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$tablename = 'enroll_activities';


if ($_W['isajax']) {
	if ($operation == 'display') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 5;
		$condition = " and uniacid=:uniacid and is_show=:is_show ";
		$params = array(':uniacid' => $uniacid, ':is_show' => 1);

		$list = pdo_fetchall("select * from " . tablename($tablename) . " where 1 {$condition} order by createtime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
		$total = pdo_fetchcolumn('select count(*) from ' . tablename($tablename) . " where 1 {$condition}", $params);

		foreach ($list as &$row) {
			$row['headimg'] = tomedia($row['headimg']);
			$row['sign_stime'] = date('Y-m-d H:i',$row['sign_stime']);
			$row['activity_stime'] = date('Y-m-d H:i',$row['activity_stime']);
		}
		unset($row);
		show_json(1, array('total' => $total, 'list' => $list, 'pagesize' => $psize));
	}
	show_json(0,'敬请期待!!!');
}
include $this->template('enroll/list');