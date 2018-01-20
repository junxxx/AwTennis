<?php

/**
 * Created by PhpStorm.
 * User: peRFect
 * Date: 2017/12/24
 * Time: 16:32
 */

defined('IN_IA') or exit('Access Denied');

class AWT_Tenroll_Apikey
{
    private $tableName = null;

    public function __construct(){
        $this->tableName = 'apikey';
    }

    public function getKey($id = 1){
        $id = intval($id);
        $sql = 'SELECT `key` FROM'.tablename($this->tableName).'WHERE id = :id';
        $key = pdo_fetch($sql, array(':id' => $id));
        if(empty($key)){
            return error("key不存在");
        }else{
            return $key['key'];
        }
    }

}