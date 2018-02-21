<?php

/**
 * Created by PhpStorm.
 * User: peRFect
 * Date: 2017/12/24
 * Time: 13:45
 */
class ApiHttp
{
    //string
    private $url = null;
    private $params = "";
    private $httpMethod = null;
    private $token = "";

    /**
     * api constructor.
     * $_url string
     * $_params array
     * $_httpMethod array
     */
    public function __construct($_url, $_params, $_httpMethod){
        $this->token = m('apikey')->getKey(1);
        echo $this->token;

        $this->url = $_url;
        $this->httpMethod = $_httpMethod;

        //对参数按字典顺序排序
        ksort($_params);
        //对参数进行加密
        foreach ($_params as $name => $value){
            $this->params .= $name."=".$value."&";
        }

        //md5加密参数并将MD5码转化成大写
        $sign = strtoupper(md5($this->params.'token='.$this->token));

        //将参数附加在链接后面
        $this->url .= "?".$this->params."sign=".$sign;

//        echo $this->url;
    }


    /**
     * 返回访问api接口返回的数据
     * date: 12.24 2017
     * author: fu
     * version: 1.0
     * param：
     * return: array
     */
    public function response(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->httpMethod,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "postman-token: 697b4ffb-4462-2171-ccde-3189b0fad49a"
            ),
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