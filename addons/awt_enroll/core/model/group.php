<?php
/**
 * Created by PhpStorm.
 * User: panj
 * Date: 2017/9/14
 * Time: 11:23
 */
if (!defined('IN_IA')) {
    die('Access Denied');
}
class AWT_Tenroll_Group
{
    private $uniacid = null;
    private $table = 'enroll_member_group';

    public function __construct()
    {
        global $_W;
        $this->uniacid = $_W['uniacid'];
    }

    public function get()
    {
        $where = ' WHERE 1';
        $condition = " and uniacid=:uniacid ";
        $params = array(
            ':uniacid' => $this->uniacid,
        );
        $SQL = ' SELECT * FROM '.tablename($this->table).$where.$condition;
        $data = pdo_fetchall($SQL, $params);
        if (!empty($data)){
            return $data;
        }
        return false;
    }

}