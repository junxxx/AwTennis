<?php
if(!defined('IN_IA')) {
	die('Access Denied');
}
global $_W, $_GPC;
$uniacid = $_W['uniacid'];
$tablename = 'enroll_articles';
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if( $operation == 'display') {
	$where = ' WHERE 1 AND uniacid=:uniacid';
	$params = array(
		':uniacid' => $uniacid,
	);
	$sql = ' SELECT * FROM '.tablename($tablename).$where;
	$orderBy = ' ORDER BY id DESC ';
	$sql .= $orderBy;
	$list = pdo_fetchall($sql, $params);
}elseif($operation == 'post'){
	$id = intval($_GPC['id']);

	if (checksubmit('submit')) {
		$data = array('uniacid' => $_W['uniacid'], 'title' => trim($_GPC['title']), 'detail' => htmlspecialchars_decode($_GPC['detail']), 'is_display' => intval($_GPC['is_display']), 'createtime' => time());
		if (!empty($id)) {
			pdo_update($tablename, $data, array('id' => $id));
			plog('article.article.edit', "修改文章 ID: {$id}");
		} else {
			pdo_insert($tablename, $data);
			$id = pdo_insertid();
			plog('article.article.add', "新增文章 ID: {$id}");
		}
		message('更新文章成功！', $this->createWebUrl('article/list', array('op' => 'display')), 'success');
	}

	$sql = 'SELECT * FROM ' . tablename($tablename) . 'WHERE id = :id AND uniacid = :uniacid LIMIT 1';
	$params = array(
		':id' => $id,
		':uniacid' => $uniacid,
	);
	$article = pdo_fetch($sql, $params);
}elseif( $operation == 'detail') {

}elseif ( $operation == 'delete'){

}
load()->func('tpl');
include $this->template('web/article/list');