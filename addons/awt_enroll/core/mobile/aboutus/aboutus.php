<?php
/*手机端aboutus 页面*/
if (!defined('IN_IA')) {
	die('Access Denied');
}
global $_W, $_GPC;

if ($_W['isajax']) {
	show_json(0,'敬请期待!!!');
}
include $this->template('main/aboutus');