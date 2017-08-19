<?php
/**
 * 个人中心
 * Created by PhpStorm.
 * User: panj
 * Date: 2017/8/15
 * Time: 14:41
 */
if (!defined('IN_IA')) {
    die('Access Denied');
}
global $_W, $_GPC;

if($_W['isajax']){
    show_json(1);
}


include $this->template('member/center');