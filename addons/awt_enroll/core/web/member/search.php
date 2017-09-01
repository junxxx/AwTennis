<?php
/**
 * User: panj
 * Date: 2017/9/1
 * Time: 10:24
 */
if (!defined('IN_IA')) {
    die('Access Denied');
}
global $_W, $_GPC;
$memberTable = 'enroll_member';
$kwd = trim($_GPC['keyword']);
$params = array();
$params[':uniacid'] = $_W['uniacid'];
$condition = " and uniacid=:uniacid";
if (!empty($kwd)) {
    $condition .= " AND ( `nickname` LIKE :keyword or `realname` LIKE :keyword or `mobile` LIKE :keyword )";
    $params[':keyword'] = "%{$kwd}%";
}
$ds = pdo_fetchall('SELECT id,avatar,nickname,openid,realname,mobile FROM ' . tablename($memberTable) . " WHERE 1 {$condition} order by id desc", $params);
include $this->template('web/member/search');