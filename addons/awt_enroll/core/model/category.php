<?php
/**
 * Created by PhpStorm.
 * User: panj
 * Date: 2017/8/31
 * Time: 11:23
 */
if (!defined('IN_IA')) {
    die('Access Denied');
}
class AWT_Tenroll_Category
{
    private $uniacid = null;
    private $table = 'enroll_activity_cate';

    public function __construct()
    {
        global $_W;
        $this->uniacid = $_W['uniacid'];
    }

    public function getCategories()
    {
        $where = ' WHERE 1';
        $condition = " and uniacid=:uniacid ";
        $params = array(
            ':uniacid' => $this->uniacid,
        );
        $SQL = ' SELECT * FROM '.tablename($this->table).$where.$condition;
        $categories = pdo_fetchall($SQL, $params);
        if (!empty($categories)){
            return $categories;
        }
        return false;
    }

}