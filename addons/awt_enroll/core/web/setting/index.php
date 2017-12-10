<?php
/**
 * 系统设置
 * User: panj
 * Date: 2017/8/25
 * Time: 17:14
 */
defined('IN_IA') or exit('Access Denied!');
global $_W, $_GPC;
$messageTemTable = 'enroll_message_template';
$settingTable = 'enroll_sysset';
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'addtype') {
    $kw = $_GPC['kw'];
    include $this->template('web/setting/message_type', array('op' => 'addtype'));
    die;
} elseif ($operation == 'display') {
    $list = pdo_fetchall('SELECT * FROM ' . tablename($messageTemTable) . ' WHERE uniacid=:uniacid order by id asc', array(':uniacid' => $_W['uniacid']));
} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);
    if (!empty($_GPC['id'])) {
        $list = pdo_fetch('SELECT * FROM ' . tablename($messageTemTable) . ' WHERE id=:id and uniacid=:uniacid ', array(':id' => $_GPC['id'], ':uniacid' => $_W['uniacid']));
        $data = iunserializer($list['data']);
    }
    if ($_W['ispost']) {
        $id = $_GPC['id'];
        $keywords = $_GPC['tp_kw'];
        $value = $_GPC['tp_value'];
        $color = $_GPC['tp_color'];
        if (!empty($keywords)) {
            $data = array();
            foreach ($keywords as $key => $val) {
                $data[] = array('keywords' => $keywords[$key], 'value' => $value[$key], 'color' => $color[$key]);
            }
        }
        $insert = array(
            'title' => $_GPC['tp_title'],
            'template_id' => trim($_GPC['tp_template_id']),
            'first' => trim($_GPC['tp_first']),
            'firstcolor' => trim($_GPC['firstcolor']),
            'data' => iserializer($data),
            'remark' => trim($_GPC['tp_remark']),
            'remarkcolor' => trim($_GPC['remarkcolor']),
            'url' => trim($_GPC['tp_url']),
            'uniacid' => $_W['uniacid']
        );
        if (empty($id)) {
            pdo_insert($messageTemTable, $insert);
            $id = pdo_insertid();
        } else {
            pdo_update($messageTemTable, $insert, array('id' => $id));
        }
        if (checksubmit('submit')) {
            message('保存成功！', $this->createWebUrl('setting/index'));
        } else {
            if (checksubmit('submitsend')) {
                header('location: ' . $this->createWebUrl('setting/index', array('op' => 'send', 'id' => $id)));
                die;
            }
        }
    }
} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    pdo_delete($messageTemTable, array('id' => $id, 'uniacid' => $_W['uniacid']));
    message('删除成功！', $this->createWebUrl('setting/index'), 'success');
}
load()->func('tpl');
include $this->template('web/setting/message');
