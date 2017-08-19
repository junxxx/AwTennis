<?php
/**
 * File  list.php活动报名情况
 * Created by https://github.com/junxxx
 * Email: hprjunxxx@gmail.com
 * Author: junxxx
 * Datetime: 2017/8/19 9:48
 */
defined('IN_IA') or exit('Access Denied!');
global $_W, $_GPC;

$logTable = 'enroll_activitie_logs';
$activityTable = 'enroll_activities';
$memberTable = 'enroll_member';
$uniacid = $_W['uniacid'];

$operation = isset($_GPC['op']) ? $_GPC['op'] : 'display';

if ($operation == 'display'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$where = ' WHERE 1 AND uniacid=:uniacid';
	$params = array(
		':uniacid' => $uniacid,
	);
	$sql = ' SELECT l.*, m.nickname, m.realname, m.mobile, m.avatar, m.gender, a.title, a.headimg, a.location,a.fee  FROM '.tablename($logTable).' l LEFT JOIN '.tablename($memberTable).' m ON l.mid = m.id LEFT JOIN '.tablename($activityTable).' a ON l.aid = a.id ';
	$orderBy = ' ORDER BY id DESC ';
	$sql .= $orderBy;
	$total = count(pdo_fetchall($sql, $params));
	if (empty($_GPC['export'])) {
		$sql .= "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	}
	$list = pdo_fetchall($sql, $params);

	if (!empty($list)) {
		foreach ($list as &$row)
		{
			$row['createtime'] = date('Y-m-d H:i:s',$row['createtime']);
			$row['updatetime'] = date('Y-m-d H:i:s',$row['updatetime']);
			$row['headimg'] = tomedia($row['headimg']);
			/*报名状态status处理*/
			if ($row['status'] == 0 ){
				$row['status'] = '未付款/未确认';
			}
			if ( $row['status'] == 1){
				$row['status'] = '成功';
			}
			if ( $row['status'] == 2){
				$row['status'] = '退赛';
			}
		}
		unset($row);
	}

	if ($_GPC['export'] == 1) {
//		plog('order.op.export', '导出订单');
		$columns = array(
			array('title' => '活动', 'field' => 'title', 'width' => 24),
			array('title' => '报名时间', 'field' => 'createtime', 'width' => 24),
			array('title' => '活动费用', 'field' => 'fee', 'width' => 24),
			array('title' => '报名球员', 'field' => 'nickname', 'width' => 24),
			array('title' => '活动状态', 'field' => 'status', 'width' => 24),
			);

		$exportlist = array();
		foreach ($list as &$r) {
			$exportlist[] = $r;
		}
		unset($r);
		@ini_set('memory_limit', '256M');

		m('excel')->export($exportlist, array("title" => "报名数据-", "columns" => $columns));
	}

//	print_r($list);
	$pager = pagination($total, $pindex, $psize);
}elseif( $operation == 'post'){


}elseif ($operation == 'delete'){

}

load()->func('tpl');
include $this->template('web/enroll/list');