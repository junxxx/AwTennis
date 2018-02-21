<?php
/**
 * Created by PhpStorm.
 * User: peRFect
 * Date: 2017/12/24
 * Time: 15:56
 */

defined('IN_IA') or exit('Access Denied');

function api_key($id = 1){
    $id = intval($id);
    $sql = 'SELECT `key` FROM'.tablename('apikey').'WHERE id = :id';
    $key = pdo_fetchcolumn($sql, array([':id' => $id]));
    if(empty($key)){
        return error("key不存在");
    }else{
        return $key;
    }
}