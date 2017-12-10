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
    const ENROLL_FIRST = 0;
    const ENROLL_RESERVE = 1;

    public static $enrollStatus = array(
        self::ENROLL_FIRST => '正选',
        self::ENROLL_RESERVE => '替补',
    );


    private $uniacid = null;
    private $nowTime = null;
    private $table = 'enroll_activities';
    private $logstable = 'enroll_activities_logs';
    private $where = null;
    private $params = array();

    public function __construct()
    {
        global $_W;
        $this->uniacid = $_W['uniacid'];
        $this->nowTime = time();
        $this->where = ' WHERE uniacid=:uniacid ';
        $this->params = array(':uniacid' => $this->uniacid);
    }

    public function addLogs($data)
    {
        return pdo_insert($this->logstable, $data);
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

    /*获取报名时间大于当前时间的所有需要自动转正的报名替补记录*/
    public function getCronActivityLogs()
    {
        $where =  ' WHERE l.uniacid =:uniacid AND l.reserve_status =:reserve_status  AND a.sign_stime >=:sign_stime AND a.activity_locktime >=:activity_locktime';
        $params = array(
            ':uniacid' => $this->uniacid,
            ':reserve_status' => 1,
            ':sign_stime' => $this->nowTime,
            ':activity_locktime' => $this->nowTime,
        );
        $sql = ' SELECT a.sign_stime, a.activity_locktime, a.com_nums, l.id, l.uniacid, l.mid, l.openid, l.status, l.reserve_status  FROM '.tablename($this->table).$where;
        return pdo_fetchall($sql, $params);
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

    /*活动正选人数是否已经满员*/
    public function isFullNums($Id)
    {
        if(intval($Id)){
            $where = ' WHERE a.uniacid=:uniacid  AND a.id =:id AND l.reserve_status =:status';
            $params = array(
                ':uniacid' => $this->uniacid,
                ':id' => $Id,
                ':status' => 0,     /*正选状态*/
            );
            $sql = 'SELECT count(l.id) count, a.com_nums num FROM '.tablename($this->logstable).' l LEFT JOIN '.tablename($this->table).' a ON l.aid = a.id '.$where;
            $record = pdo_fetch($sql,$params);
            return $record['count'] >= $record['num'];

        }
        return false;
    }

    /**
     * 获取会员的活动记录
     * date 2017-11-26
     * @param string $mid mid or openid
     * @param int $type 状态，正选还是替补
     * @return mixed
    */
    public function getActivitiesByMid($mid, $type)
    {
        if(!$mid)
            return false;

        if(is_string($mid)) {
            $where = " WHERE 1 AND l.openid=:openid ";
            $param = array(
                ':openid' => $mid,
            );
        } else {
            $where = " WHERE 1 AND l.mid=:mid ";
            $param = array(
                ':mid' => $mid,
            );
        }

        $type = intval($type);

        if($type && array_key_exists($type, self::$enrollStatus)) {
            $where .= " AND l.reserve_status=:type ";
            $param[':type'] = $type;
        }
        $sql = " SELECT l.*, a.* FROM ".tablename($this->logstable) . " AS l LEFT JOIN " .tablename($this->table). " AS a ON l.aid = a.id ". $where;
        return pdo_fetchall($sql, $param);
    }
}