<?php
/*手机端aboutus 页面*/
if (!defined('IN_IA')) {
	die('Access Denied');
}
global $_W, $_GPC;
$uniacid = $_W['uniacid'];
$articleTable = 'enroll_articles';
if ($_W['isajax']) {
	$title = '关于我们';
	$where = ' WHERE 1 AND uniacid=:uniacid AND title=:title AND is_display=:is_display LIMIT 1';
	$params = array(
		':uniacid' => $uniacid,
		':title' => $title,
		':is_display' => 1,
	);
	$sql = 'SELECT * FROM '.tablename($articleTable).$where;
	$aboutUs = pdo_fetch($sql, $params);
	if(!empty($aboutUs)){
		$aboutUs['createtime'] = date('Y-m-d H:i:s',$aboutUs['createtime']);
		$aboutUs['detail'] =  htmlspecialchars($aboutUs['detail']);
		show_json(1,$aboutUs);
	}
	show_json(0,'敬请期待!!!');
}
include $this->template('main/aboutus');