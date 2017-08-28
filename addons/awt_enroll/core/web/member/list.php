<?php
/**
 * Description: 后台用户管理
 * User: panj
 * Date: 2017/8/28
 * Time: 9:39
 */
defined('IN_IA') or exit('Access Denied!');
global $_W, $_GPC;
$uniacid = $_W['uniacid'];

$memberTable = 'enroll_member';
$memberGroupTable = 'enroll_member_group';
$operation = isset($_GPC['op']) ? trim($_GPC['op']) : 'display';

if ($operation == 'display') {
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $where = ' WHERE 1 AND uniacid=:uniacid';
    $params = array(
        ':uniacid' => $uniacid,
    );
    $sql = ' SELECT m.*, g.groupname FROM '.tablename($memberTable).' m LEFT JOIN '.tablename($memberGroupTable).' g ON m.groupid = g.id  ';
    $orderBy = ' ORDER BY m.id DESC ';
    $sql .= $orderBy;
    $total = count(pdo_fetchall($sql, $params));
    if (empty($_GPC['export'])) {
        $sql .= "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
    }
    $list = pdo_fetchall($sql, $params);

    if (!empty($list)) {
        foreach ($list as &$row)
        {

        }
        unset($row);
    }

    if ($_GPC['export'] == 1) {
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

        m('excel')->export($exportlist, array("title" => "用户数据-", "columns" => $columns));
    }

    //	print_r($list);
    $pager = pagination($total, $pindex, $psize);
}
include $this->template('web/member/list');