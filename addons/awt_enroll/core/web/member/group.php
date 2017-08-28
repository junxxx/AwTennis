<?php
/**
 * User: panj
 * Date: 2017/8/28
 * Time: 9:52
 */
defined('IN_IA') or exit('Access Denied!');
global $_W, $_GPC;

$tablename = 'enroll_member_group';
$uniacid = $_W['uniacid'];

$operation = isset($_GPC['op']) ? $_GPC['op'] : 'display';

if ($operation == 'display') {
    $where = ' WHERE 1 AND uniacid=:uniacid';
    $params = array(
        ':uniacid' => $uniacid,
    );
    $sql = ' SELECT * FROM ' . tablename($tablename) . $where;
    $orderBy = ' ORDER BY id DESC ';
    $sql .= $orderBy;
    $list = pdo_fetchall($sql, $params);

} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);

    if (checksubmit('submit')) {
        $data = array(
            'uniacid' => $uniacid,
            'groupname' => trim($_GPC['groupname']),
        );
        if (!empty($id)) {
            pdo_update($tablename, $data, array('id' => $id));
            plog('member.group.edit', "修改用户分组 ID: {$id}");
        } else {
            pdo_insert($tablename, $data);
            $id = pdo_insertid();
            plog('match.cate.add', "新增用户分组 ID: {$id}");
        }
        message('更新用户分组成功！', $this->createWebUrl('member/group', array('op' => 'display')), 'success');
    }

    $sql = 'SELECT * FROM ' . tablename($tablename) . 'WHERE id = :id AND uniacid = :uniacid LIMIT 1';
    $params = array(
        ':id' => $id,
        ':uniacid' => $uniacid,
    );
    $item = pdo_fetch($sql, $params);

} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    if (!empty($id)) {
        $sql = 'SELECT id FROM ' . tablename($tablename) . 'WHERE id = :id AND uniacid = :uniacid LIMIT 1';
        $params = array(
            ':id' => $id,
            ':uniacid' => $uniacid,
        );
        $category = pdo_fetch($sql, $params);
        if (empty($category)) {
            message('抱歉，分组不存在或是已经被删除！', $this->createWebUrl('member/group', array('op' => 'display')), 'error');
        }
        /*TODO 判断分组下面是否有用户，如果有，则不能删除分组*/
        $data = array(
            'id' => $id,
            'uniacid' => $uniacid,
        );
        pdo_delete($tablename, $data);
        message('用户分组删除成功！', $this->createWebUrl('member/group', array('op' => 'display')), 'success');
    }
}

load()->func('tpl');
include $this->template('web/member/group');