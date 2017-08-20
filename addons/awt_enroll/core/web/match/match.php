<?php
/**
 * File  list.php
 * Created by https://github.com/junxxx
 * Email: hprjunxxx@gmail.com
 * Author: junxxx
 * Datetime: 2017/8/13 12:34
 */
defined('IN_IA') or exit('Access Denied!');
global $_W, $_GPC;

$tablename = 'enroll_activities';
$uniacid = $_W['uniacid'];

$operation = isset($_GPC['op']) ? $_GPC['op'] : 'display';

if ($operation == 'display'){
	$where = ' WHERE 1 AND uniacid=:uniacid';
	$params = array(
		':uniacid' => $uniacid,
	);
	$sql = ' SELECT * FROM '.tablename($tablename).$where;
	$orderBy = ' ORDER BY id DESC ';
	$sql .= $orderBy;
	$list = pdo_fetchall($sql, $params);

	if (!empty($list)) {
		foreach ($list as &$row)
		{
			$row['createtime'] = date('Y-m-d H:i:s',$row['createtime']);
			$row['updatetime'] = date('Y-m-d H:i:s',$row['updatetime']);
		}
		unset($row);
	}

}elseif( $operation == 'post'){
	$id = intval($_GPC['id']);

	if (checksubmit('submit')) {
		$sign_stime = strtotime($_GPC['sign_stime']);
		$activity_stime = strtotime($_GPC['activity_stime']);
		$activity_locktime = strtotime($_GPC['activity_locktime']);
		/*location活动位置还没有加进来*/
		$data = array(
			'uniacid' => $uniacid,
			'title' => trim($_GPC['activitytitle']),
			'headimg' => $_GPC['headimg'],
			'sign_stime' => $sign_stime,
			'activity_stime' => $activity_stime,
			'activity_locktime' => $activity_locktime,
			'location' => trim($_GPC['location']),
			'rule' => $_GPC['rule'],
			'com_nums' => $_GPC['com_nums'],
			'fee' => $_GPC['fee'],
			'is_show' => $_GPC['is_show'],
			'displayorder' => $_GPC['displayorder'],
		);
		if (!empty($id)) {
			$data['updatetime'] = time();
			pdo_update($tablename, $data, array('id' => $id));
			plog('article.article.edit', "修改活动 ID: {$id}");
		} else {
			$data['createtime'] = time();
			$data['updatetime'] = time();
			pdo_insert($tablename, $data);
			$id = pdo_insertid();
			plog('article.article.add', "新增活动 ID: {$id}");
		}
		message('更新活动成功！', $this->createWebUrl('match/match', array('op' => 'display')), 'success');
	}

	$sql = 'SELECT * FROM ' . tablename($tablename) . 'WHERE id = :id AND uniacid = :uniacid LIMIT 1';
	$params = array(
		':id' => $id,
		':uniacid' => $uniacid,
	);
	$item = pdo_fetch($sql, $params);

}elseif ($operation == 'delete'){

}elseif ($operation == 'setgoodsproperty'){
	/*显示或不显示状态切换*/
	$id = intval($_GPC['id']);
	$data = $_GPC['data'];
	$data = $data == 1 ? 0 : 1;
	$updatedata['is_show'] = $data;
	$updatedata['updatetime'] = time();
	$params = array(
		'id' => $id,
		'uniacid' => $uniacid,
	);
	pdo_update($tablename,$updatedata,$params);
	$ret = array(
		'result' => 1,
		'data'  => $updatedata['is_show']
	);
	exit(json_encode($ret));
}

load()->func('tpl');
include $this->template('web/match/list');