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
                ':id' => $id
            );
            $SQL = ' SELECT * FROM '.tablename($this->table).$where.$condition;
            $activity = pdo_fetch($SQL, $params);
            if (!empty($activity)){
                return $activity;
            }
        }
        return false;
    }
}