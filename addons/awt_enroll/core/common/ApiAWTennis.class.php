<?php

/**
 * Created by PhpStorm.
 * User: peRFect
 * Date: 2017/12/24
 * Time: 13:45
 */

class ApiAWTennis
{
    //string
    private $url = null;
    private $params = null;
    private $httpMethod = null;
    private $token = null;
    private $sign = null;
    const RANKING_URL = 'http://www.aiwangsports.com/api/ranking';
    const USER_URL = 'http://www.aiwangsports.com/api/user';
    const BINDING_URL = 'http://www.aiwangsports.com/api/user/binding';
    const REGISTERING_URL = 'http://www.aiwangsports.com/api/user/registering';

    /**
     * api constructor.
     * $_url string
     * $_params array
     * $_httpMethod array
     */
    public function __construct($_params, $_httpMethod = 'GET'){

        $this->token = m('apikey')->getKey();

        $this->httpMethod = $_httpMethod;

        //对参数按字典顺序排序
        ksort($_params);
        //对参数进行加密
        foreach ($_params as $name => $value){
            $this->params .= $name."=".$value."&";
        }

        //md5加密参数并将MD5码转化成大写
        $this->sign = strtoupper(md5($this->params.'token='.$this->token));

    }

    /**
     * Function: getRanking
     * Date: 2018/1/7 17:12
     * Duthor: peRFect
     * @return mixed|null
     * Decripstion: 获取排名信息
     */
    public function getRanking(){
        $this->url = self::RANKING_URL;
        //将参数附加在链接后面
        if(!is_null($this->params)){
            $this->url .= "?".$this->params."sign=".$this->sign;
        }

        return self::response();
    }

    /**
     * @param $userId
     * Function: getUser
     * Date: 2018/1/7 17:24
     * Author: peRFect
     * @return mixed|null
     * Decripstion: 获取用户信息
     */
    public function getUser($userId){
        $this->url = self::USER_URL."/".$userId;
        if(!is_null($this->params)){
            $this->url .= "?sign=".$this->sign;
        }
        return self::response();
    }

    /**
     * Function: getBinding
     * Date: 2018/1/7 17:28
     * Author: peRFect
     * @return mixed|null
     * Decripstion: 返回绑定会员后的用户基本信息
     */
    public function getBinding(){
        $this->url = self::BINDING_URL;
        //将参数附加在链接后面
        if(!is_null($this->params)){
            $this->url .= "?".$this->params."sign=".$this->sign;
        }

        return self::response();
    }

    /**
     * Function: getRegistering
     * Date: 2018/1/7 17:28
     * Author: peRFect
     * @return mixed|null
     * Decripstion: 获取新注册的用户基本信息
     */
    public function getRegistering(){
        $this->url = self::REGISTERING_URL;
        //将参数附加在链接后面
        if(!is_null($this->params)){
            $this->url .= "?".$this->params."sign=".$this->sign;
        }

        return self::response();
    }
    /**
     * 返回访问api接口返回的数据
     * date: 12.24 2017
     * author: fu
     * version: 1.0
     * param：
     * return: array
     */
    private function response(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->httpMethod,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
//            echo "cURL Error #:" . $err;
            return null;
        } else {
            return json_decode($response, true);
        }
    }

}