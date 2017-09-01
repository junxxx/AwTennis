<?php
/**
 * 积分系统接口类
 * User: panj
 * Date: 2017/8/25
 * Time: 16:17
 */
if (!defined('IN_IA')) {
    die('Access Denied');
}
class AWT_Tenroll_Awtennis {

    private static $apiUrl = '';
    private static $key = 'testkey12123';
    private $salt = '';

    private $user = array();

    public function __construct()
    {

    }

    public function encrypt()
    {
        /*
         * 1、将请求参数 按照键名对关联数组进行升序排序
         * 2、把参数按键值对组成字符串key=value
         * 3、在字符串末尾加上私钥key
         * 4、将最终字符串进行MD5加密，字符串转为大写，得到签名
        */
        ksort($package);
        $string1 = '';
        foreach($package as $key => $v) {
            if (empty($v)) {
                continue;
            }
            $string1 .= "{$key}={$v}&";
        }
        $string1 .= "key={$wechat['key']}";
        $sign = strtoupper(md5($string1));
    }

    /*获取积分排行榜*/
    public function getRank()
    {

    }

    /*获取用户信息*/
    public function getUser()
    {
    }

    /*新增用户*/
    public function addUser()
    {

    }
}