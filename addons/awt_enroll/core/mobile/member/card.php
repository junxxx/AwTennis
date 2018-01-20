<?php
/**
 * 会员卡
 * Created by PhpStorm.
 * User: panj
 * Date: 2017/8/22
 * Time: 11:18
 */
require_once AWT_ENROLL_CORE . 'common/ApiAWTennis.class.php';

defined('IN_IA') or exit('Access Denied!');
global $_W, $_GPC;

function updateMem($status, $clubMemberid){
    //将用户memberId保存到微信端数据库表wxt_enroll_member
    if($status == 200 || $status == 201){
        //会员绑定成功
        $update_data['memberid'] = intval($clubMemberid);
        //获取当前用户的openid
        //sql table: awt_mc_mapping_fans
        $openid = m('user')->getOpenid();
        //通过openid查询用户
        //sql table: entroll_member
        $member = m('member')->getMember($openid);

        try{
            if(!m('member')->updateMember($member['id'],$update_data)){
                throw new Exception();
            }
        }catch(Exception $e){
            m('member')->updateMember($member['id'], $update_data);
        }
    }
}

if ($_W['isajax']) {
    $operation = isset($_GPC['behavior']) ? trim($_GPC['behavior']) : '';
    $status = 0;
    $message = null;
    /*绑定*/
    if ($operation == 'binding') {
        //获取页面表单的请求参数
        $param['cardNum'] = trim($_GPC['cardNum']);
//        echo $param['cardNum'];
        //通过api接口获取数据
        $apiAWTennis = new ApiAWTennis($param);
        $apiResponse = $apiAWTennis->getBinding();

        //处理api接口返回的结果
        if(is_null($apiResponse)){
            $message = '会员申请失败，请稍后重试';
        }else{
            $status = 1;
            $message = $apiResponse;

            updateMem($apiResponse['status'], $apiResponse['data'][0]['id']);
        }

//        exit();
    }else if($operation == 'create') {
    /*新增*/
        //获取表单请求参数
        $param['name'] = trim($_GPC['username']);
        $param['password'] = trim($_GPC['password']);
        $param['sex'] = trim($_GPC['sex']);
        $param['phone'] = trim($_GPC['phone']);
        $param['forehand'] = trim($_GPC['forehand']);
        $param['backhand'] = trim(($_GPC['backhand']));

        $apiAWTennis = new ApiAWTennis($param);
        $apiResponse = $apiAWTennis->getRegistering();

        if(is_null($apiResponse)){
            $message = "会员注册失败，请稍后重试";
        }else{
            $status = 1;
            $message = $apiResponse;

            updateMem($apiResponse['status'], $apiResponse['data'][0]['id']);
        }
    }
    //将api返回的数据传送到前端页面
    show_json($status, $message);
}

//获取当前用户的openid
//sql table: awt_mc_mapping_fans
$openid = m('user')->getOpenid();
//通过openid查询用户
//sql table: entroll_member
$member = m('member')->getMember($openid);

if(is_null($member['memberid'])){
    //未注册会员
    include $this->template('member/applying');
}else{
    //已注册会员
    include $this->template('member/card');
}

