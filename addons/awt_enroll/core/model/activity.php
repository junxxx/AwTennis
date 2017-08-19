<?php
/**
 * Created by PhpStorm.
 * User: panj
 * Date: 2017/8/16
 * Time: 11:23
 */
if (!defined('IN_IA')) {
    die('Access Denied');
}
class AWT_Tenroll_Activity
{
    private $uniacid = null;
    private $table = 'enroll_activities';
    private $logstable = 'enroll_activitie_logs';

    public function __construct()
    {
        global $_W;
        $this->uniacid = $_W['uniacid'];
    }

    public function getActivityById( $id )
    {
        if (!empty($id)){
            $where = ' WHERE 1';
            $condition = " and uniacid=:uniacid and is_show=:is_show and id=:id ";
            $params = array(
                ':uniacid' => $this->uniacid,
                ':is_show' => 1,
                ':id'      => $id,
            );
            $SQL = ' SELECT * FROM '.tablename($this->table).$where.$condition;
            $activity = pdo_fetch($SQL, $params);
            if (!empty($activity)){
                return $activity;
            }
        }
        return false;
    }

    /*检查会员是否报过某项活动*/
    public function isEnrolled( $mid, $aid )
    {
        if (!empty($mid) && !empty($aid)){
            $where = ' WHERE 1 ';
            $condition = ' AND aid=:aid AND uniacid=:uniacid AND mid=:mid ';
            $SQL = 'SELECT id FROM '.tablename($this->logstable) . $where . $condition;
            $params = array(
                ':aid'      => $aid,
                ':uniacid' => $this->uniacid,
                ':mid'     => $mid,
            );
            return (pdo_fetch($SQL, $params) !== false);
        }
        return false;
    }

    /*检查会员的活动报名资格*/
    public function checkQualification()
    {
        /*TODO */
        return true;
    }
}