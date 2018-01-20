<?php
/*手机端ranking 页面*/
require_once AWT_ENROLL_CORE . 'common/ApiAWTennis.class.php';

if (!defined('IN_IA')) {
	die('Access Denied');
}
global $_W, $_GPC;

if ($_W['isajax']) {

    $params['entry'] = isset($_GPC['entry'])? intval($_GPC['entry']):1;

    $apiAWTennis = new ApiAWTennis($params);
    $response = $apiAWTennis->getRanking();

    $status = 0;
    $message = null;

    if(is_null($response)){
        $status = 0;
        $message = '获取数据失败';
    }else{
        $status = 1;
        $message = $response;
    }
    show_json($status, $message);
}
include $this->template('main/ranking');