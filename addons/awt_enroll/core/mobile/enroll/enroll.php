<?php
/**
 * 前台活动报名业务逻辑处理
 * Created by PhpStorm.
 * User: panj
 * Date: 2017/8/16
 * Time: 10:54
 */
defined('IN_IA') or exit('Access Denied!');
global $_W, $_GPC;
$logtable = 'enroll_activitie_logs';
$uniacid = $_W['uniacid'];
$openid = m('user')->getOpenid();
$member = m('member')->getMember($openid);

if ($_W['isajax']) {
    $activityId = isset($_GPC['id']) ? intval($_GPC['id']) : '';
    if (!empty($activityId)){
        $activity = m('activity')->getActivityById($activityId);
        if (empty($activity)){
            show_json(0, '活动不存在');
        }
        /*开始报名时间检查*/
        $nowTime = time();
        if ($nowTime < $activity['sign_stime']){
            show_json(0, '还未到报名时间');
        }
        if ($nowTime > $activity['sign_etime']){
            show_json(0, '已过报名时间，下次早一点哟');
        }
        /*活动是否已经报过名*/
        $enrolled = m('activity')->isEnrolled($member['id'], $activityId);
        if ($enrolled){
            show_json(0, '已经报过此活动');
        }
        $qualification = $activity['qualification'];
        /*TODO 是否开启特殊组别*/
        if ($qualification == 1)
        {
            $attendGroups = unserialize($activity['attend_groups']);
            /*TODO 用户是否在参赛组里 这里要判断比赛是否是设置成所有组参加*/
            if (!in_array($member['groupid'], $attendGroups)) {
                /*不在参赛组里*/
                /*挑战位时间是否开启*/
                if (time() >= $activity['quali_stime'] && time() <= $activity['quali_etime']){
                    /*在特殊权限保护时间区间内*/
                    /*正选是否已达上限*/
                    $mainIsFull = m('activity')->isFullNums($activityId);
                    if ($mainIsFull) {
                        /*报名状态为替补*/
                        show_json(1, '报名成功');
                    }
                    /*报名状态为挑战位正式*/
                    show_json(1, '报名成功');
                }
                /*没有报名资格*/
                show_json(0, '无报名资格');
            }
        }
        /*正选是否已达上限*/
        $mainIsFull = m('activity')->isFullNums($activityId);
        if ($mainIsFull){
            /*报名状态为替补*/
            show_json(1,'报名成功');
        }
        /*TODO 是否开启新人优先*/
        $newFirst = $activity['new_first'];
        if ($newFirst == 1){
            /* TODO 上周是否参加过此活动*/
            $lastWeekPlayed = m('activity')->lastWeekPlayed($member['id'], $activityId);
            if ($lastWeekPlayed){
                /*是否在锁定时间之内*/
                if (time() < $activity['activity_locktime']){
                    /*报名状态为替补*/
                    show_json(1, '报名成功');
                }
            }
        }
        /*报名状态为正选*/
        show_json(1,'报名成功');
    }
}

