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
load()->classs('logging');
$path = AWT_ENROLL_PATH . '/data/LOGS/mobile/enroll/';
$logFile = 'enroll';
$log = new Log($path, $logFile);
if ($_W['isajax']) {
    $activityId = isset($_GPC['id']) ? intval($_GPC['id']) : '';
    if (!empty($activityId)) {
        $activity = m('activity')->getActivityById($activityId);
        $log->write("Begin--------->\n member: " . var_export($member, true));
        $log->write('activity: ' . var_export($activity, true));
        if (empty($activity)) {
            show_json(0, '活动不存在');
        }
        /*开始报名时间检查*/
        $nowTime = time();
        if ($nowTime < $activity['sign_stime']) {
            show_json(0, '还未到报名时间');
        }
        if ($nowTime > $activity['sign_etime']) {
            show_json(0, '已过报名时间，下次早一点哟');
        }
        /*活动是否已经报过名*/
        $enrolled = m('activity')->isEnrolled($member['id'], $activityId);
        if ($enrolled) {
            show_json(0, '已经报过此活动');
        }
        $enrollData = array(
            'uniacid' => $uniacid,
            'aid' => $activityId,
            'mid' => $member['id'],
            'openid' => $openid,
        );

        $qualification = $activity['qualification'];
        /*TODO 是否开启特殊组别*/
        if ($qualification == 1) {
            $attendGroups = unserialize($activity['attend_groups']);
            if (!empty($attendGroups)) {
                if (!in_array($member['groupid'], $attendGroups)) {
                    /*不在参赛组里*/
                    /*挑战位时间是否开启*/
                    if (time() >= $activity['quali_stime'] && time() <= $activity['quali_etime']) {
                        /*在特殊权限保护时间区间内*/
                        /*正选是否已达上限*/
                        $mainIsFull = m('activity')->isFullNums($activityId);
                        if ($mainIsFull) {
                            /*报名状态为替补*/
                            $enrollData['reserve_status'] = AWT_Tenroll_Activity::ENROLL_RESERVE;
//                        show_json(1, '报名成功');
                        } else {
                            /*报名状态为挑战位正式*/
                            $enrollData['reserve_status'] = AWT_Tenroll_Activity::ENROLL_FIRST;
//                        show_json(1, '报名成功');
                        }
                    }
                    /*没有报名资格*/
                    show_json(0, '无报名资格');
                }
            } else {
                //TODO 开启了特殊组别,但是参赛是选的全部组可以参加
                $log->write('开启了特殊组别,但是参赛是选的全部组可以参加');
                /*正选是否已达上限*/
                $mainIsFull = m('activity')->isFullNums($activityId);
                if ($mainIsFull) {
                    /*报名状态为替补*/
                    $enrollData['reserve_status'] = AWT_Tenroll_Activity::ENROLL_RESERVE;
                    //                        show_json(1, '报名成功');
                } else {
                    /*报名状态为挑战位正式*/
                    $enrollData['reserve_status'] = AWT_Tenroll_Activity::ENROLL_FIRST;
                    //                        show_json(1, '报名成功');
                }
            }
        } else {
            /*正选是否已达上限*/
            $mainIsFull = m('activity')->isFullNums($activityId);
            if ($mainIsFull) {
                /*报名状态为替补*/
                $enrollData['reserve_status'] = AWT_Tenroll_Activity::ENROLL_RESERVE;
//                show_json(1,'替补报名成功');
            } else {
                /*TODO 是否开启新人优先*/
                $newFirst = $activity['new_first'];
                if ($newFirst == 1) {
                    /* TODO 上周是否参加过此活动*/
                    $lastWeekPlayed = m('activity')->lastWeekPlayed($member['id'], $activityId);
                    if ($lastWeekPlayed) {
                        /*是否在锁定时间之内*/
                        if (time() < $activity['activity_locktime']) {
                            /*报名状态为替补*/
                            $enrollData['reserve_status'] = AWT_Tenroll_Activity::ENROLL_RESERVE;
//                            show_json(1, '替补报名成功');
                        } else {
                            //TODO 开启新人优先,不是保护时间区间 是作何处理?
                            $log->write('开启新人优先,不是保护时间区间 是作何处理');
                        }
                    } else {
                        /*报名状态为正选*/
                        $enrollData['reserve_status'] = AWT_Tenroll_Activity::ENROLL_FIRST;
                    }
                } else {
                    /*报名状态为正选*/
                    $enrollData['reserve_status'] = AWT_Tenroll_Activity::ENROLL_FIRST;
//                    show_json(1,'报名成功');
                }
            }
        }
        /*报名入库*/
        $time = time();
        $enrollData['status'] = 1;
        $enrollData['createtime'] = $time;
        $enrollData['updatetime'] = $time;
        $log->write('enrollData: ' . var_export($enrollData, true));
        $res = m('activity')->addLogs($enrollData);
        if ($res) {
            show_json(1, '报名成功');
        } else {
            show_json(0, '请稍后再试');
        }
    }
}

