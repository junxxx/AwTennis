<?php
/**
 * 前台活动报名业务逻辑处理
 * Created by PhpStorm.
 * User: panj
 * Date: 2017/8/16
 * Time: 10:54
 */
defined('IN_IA') or edit('Access Denied!');
global $_W, $_GPC;
$logtable = 'enroll_activitie_logs';
$uniacid = $_W['uniacid'];
$openid = m('user')->getOpenid();
$member = m('member')->getMember($openid);

if ($_W['isajax']) {
    $activityId = isset($_GPC['id']) ? intval($_GPC['id']) : '';
    if (!empty($activityId)){
        $activity = m('activity')->getActivityById($activityId);
        if (!empty($activity)){
            /*TODO 处理报名业务逻辑*/
            /*开始报名时间检查*/
            $nowTime = time();
            if ( $nowTime < $activity['sign_stime']) {
                show_json(0,'还未到报名时间');
            }
            /*正选人数，替补处理*/

            /*活动是否已经报过名*/
            $enrolled = m('activity')->isEnrolled($member['id'], $activityId);
            if (!$enrolled){
                /*TODO 报名资格检查*/
                $checkQualification = m('activity')->checkQualification();
                /*如果有资格*/
                if ($checkQualification){
                    $enrollData = array(
                        'uniacid' => $uniacid,
                        'aid'     => $activityId,
                        'mid'     => $member['id'],
                        'openid'     => $member['openid'],
                        'createtime' => time(),
                        'status' => 0,
                    );
                    pdo_insert($logtable, $enrollData);
                    show_json(1,'报名成功');
                }
                show_json(0,'无报名资格');

            }
            show_json(0,'已经报过此活动');
        }
    }
    show_json(0,'活动不存在');
}

