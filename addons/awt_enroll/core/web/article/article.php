<?php
if(!defined('IN_IA')) {
	die('Access Denied');
}
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if( $operation == 'display') {

}elseif($operation == 'post'){

}elseif( $operation == 'detail') {

}elseif ( $operation == 'delete'){

}
load()->func('tpl');
include $this->template('web/article/list');