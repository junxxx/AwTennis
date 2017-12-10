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

    public function getAll()
    {
        $where = ' WHERE 1';
        $condition = " and uniacid=:uniacid ";
        $params = array(
            ':uniacid' => $this->uniacid,
        );
        $sql = ' SELECT * FROM '.tablename($this->table).$where.$condition;
        $data = pdo_fetchall($sql, $params);
        if (!empty($data)){
            return $data;
        }
        return false;
    }

    public function getById($id)
    {
        if (empty($id))
        {
            return false;
        }
        $where = ' WHERE 1 AND id = :id ';
        $condition = " and uniacid=:uniacid ";
        $params = array(
            ':uniacid' => $this->uniacid,
            ':id'      => $id,
        );
        $sql = ' SELECT * FROM ' . tablename($this->table) . $where . $condition;
        $data = pdo_fetch($sql, $params);
        if (!empty($data))
        {
            return $data;
        }

        return false;
    }

    public function getByName($name)
    {
        if (empty($name))
        {
            return false;
        }
        $where = ' WHERE 1 AND groupname = :groupname ';
        $condition = " and uniacid=:uniacid ";
        $params = array(
            ':uniacid'   => $this->uniacid,
            ':groupname' => $name,
        );
        $sql = ' SELECT * FROM ' . tablename($this->table) . $where . $condition;
        $data = pdo_fetch($sql, $params);
        if (!empty($data))
        {
            return $data;
        }

        return false;
    }

    public function insert($data)
    {
        return  pdo_insert($this->table, $data);
    }

    public function deleteById($id)
    {
        if (empty($id))
        {
            return false;
        }
        $condition = array(
            'id' => $id,
            'uniacid' => $this->uniacid,
        );
        return pdo_delete($this->table, $condition);
    }

    public function edit($data)
    {
        if (empty($data) || !is_array($data))
        {
            return false;
        }
        $uData = array(
            'groupname' => $data['groupname'],
            'update_time' => time()
        );
        $condition = array(
            'id' => $data['id'],
            'uniacid' => $this->uniacid,
        );

        return pdo_update($this->table, $uData, $condition);
    }

}