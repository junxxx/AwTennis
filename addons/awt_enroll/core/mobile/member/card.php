<?php
/**
 * 绑定或新增
 * Created by PhpStorm.
 * User: panj
 * Date: 2017/8/22
 * Time: 11:18
 */
defined('IN_IA') or exit('Access Denied!');
global $_W, $_GPC;
$operation = isset($_GPC['op']) ? trim($_GPC['op']) : '';

if ($_W['isajax']) {
    /*绑定*/
    if ($operation == 'binding') {

    }elseif ($operation == 'create') {
    /*新增*/
    }
}
include $this->template('member/card');