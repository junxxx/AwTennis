<?php
/**
 * [WeEngine System] Copyright (c) 2014 aiwangsports.com
 * WeEngine is NOT a free software, it under the license terms, visited http://www.aiwangsports.com/ for more details.
 */

defined('IN_IA') or exit( 'Access Denied' );
load()->classs('weixin.account');

class coupon extends WeiXinAccount {
    public $account = null;

    public function __construct($acid = ''){
        $this->account_api = self::create($acid);
        $this->account = $this->account_api->account;
    }

    public function getAccessToken(){
        return $this->account_api->getAccessToken();
    }

    /**
     * 获取卡券ticket
     * @return array|mixed|stdClass
     */
    public function getCardTicket(){
        $cachekey = "cardticket:{$this->account['acid']}";
        $cache = cache_load($cachekey);
        if (!empty( $cache ) && !empty( $cache['ticket'] ) && $cache['expire'] > TIMESTAMP) {
            $this->account['card_ticket'] = $cache;

            return $cache['ticket'];
        }
        load()->func('communication');
        $access_token = $this->getAccessToken();
        if (is_error($access_token)) {
            return $access_token;
        }
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=wx_card";
        $content = ihttp_get($url);
        if (is_error($content)) {
            return error(-1, '调用接口获取微信公众号 card_ticket 失败, 错误信息: ' . $content['message']);
        }
        $result = @json_decode($content['content'], true);
        if (empty( $result ) || intval(( $result['errcode'] )) != 0 || $result['errmsg'] != 'ok') {
            return error(-1, '获取微信公众号 card_ticket 结果错误, 错误信息: ' . $result['errmsg']);
        }
        $record = array();
        $record['ticket'] = $result['ticket'];
        $record['expire'] = TIMESTAMP + $result['expires_in'] - 200;
        $this->account['card_ticket'] = $record;
        cache_write($cachekey, $record);

        return $record['ticket'];
    }

    /**
     * 上传图片到微信
     * @param $logo
     * @return array|mixed|stdClass
     */
    public function LocationLogoupload($logo){
        global $_W;
        if (!strexists($logo, 'http://') && !strexists($logo, 'https://')) {
            $path = rtrim(IA_ROOT . '/' . $_W['config']['upload']['attachdir'], '/') . '/';
            if (empty( $logo ) || !file_exists($path . $logo)) {
                return error(-1, '商户LOGO不存在');
            }
        } else {
            return error(-1, '商户LOGO只能上传本地图片');
        }

        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token={$token}";
        $data = array( 'buffer' => '@' . $path . $logo );
        load()->func('communication');
        $response = ihttp_request($url, $data);
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty( $result )) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty( $result['errcode'] )) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->error_code($result['errcode'])}");
        }

        return $result;
    }


    public function SetTestWhiteList($data){
        global $_W;
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $url = "https://api.weixin.qq.com/card/testwhitelist/set?access_token={$token}";
        load()->func('communication');
        $response = ihttp_request($url, json_encode($data));
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty( $result )) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty( $result['errcode'] )) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->error_code($result['errcode'])}");
        }

        return $result;
    }

    /**
     * 创建门店
     * @param $data
     * @return array|mixed|stdClass
     */
    public function LocationAdd($data){
        if (empty( $data )) {
            return error(-1, '门店信息错误');
        }
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        if (!empty( $data['category'] )) {
            $data['category'] = array( rtrim(implode(',', array_values($data['category'])), ',') );
        }
        $data['categories'] = $data['category'];
        unset( $data['category'] );
        $data['offset_type'] = 1;
        $post = array( 'business' => array( 'base_info' => $data, ), );
        $post = stripslashes(urldecode(ijson_encode($post, JSON_UNESCAPED_UNICODE)));

        $url = "http://api.weixin.qq.com/cgi-bin/poi/addpoi?access_token={$token}";

        $result = $this->requestApi($url, $post);

        return $result;
    }

    /**
     * 修改门店服务信息
     * @param $data
     * @return array|mixed|stdClass
     */
    public function LocationEdit($data){
        if (empty( $data )) {
            return error(-1, '门店信息错误');
        }
        $post = array( 'business' => array( 'base_info' => $data ), );
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $url = "http://api.weixin.qq.com/cgi-bin/poi/updatepoi?access_token={$token}";
        load()->func('communication');
        $response = ihttp_request($url, urldecode(json_encode($post)));
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty( $result )) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty( $result['errcode'] )) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
        }

        return $result;
    }

    /**
     * 删除门店
     * @param $id
     * @return array|mixed|stdClass
     */
    public function LocationDel($id){
        if (empty( $id )) {
            return error(-1, '门店信息错误');
        }
        $post = array( 'poi_id' => $id );
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $url = "http://api.weixin.qq.com/cgi-bin/poi/delpoi?access_token={$token}";
        load()->func('communication');
        $response = ihttp_request($url, json_encode($post));
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty( $result )) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty( $result['errcode'] )) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
        }

        return $result;
    }

    /**
     * 查询门店列表
     * @param array $data
     * @return array|mixed|stdClass
     */
    public function LocationBatchGet($data = array()){
        if (empty( $data['begin'] )) {
            $data['begin'] = 0;
        }
        if (empty( $data['limit'] )) {
            $data['limit'] = 50;
        }
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $url = "http://api.weixin.qq.com/cgi-bin/poi/getpoilist?access_token={$token}";
        load()->func('communication');
        $response = ihttp_request($url, json_encode($data));
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty( $result )) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty( $result['errcode'] )) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
        }

        return $result;
    }

    /**
     * 查询门店信息
     * @param $id
     * @return array|mixed|stdClass
     */
    public function LocationGet($id){
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $data = array( 'poi_id' => $id );
        $url = "http://api.weixin.qq.com/cgi-bin/poi/getpoi?access_token={$token}";
        load()->func('communication');
        $response = ihttp_request($url, json_encode($data));
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty( $result )) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty( $result['errcode'] )) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
        }

        return $result;
    }

    /**
     * 获取颜色接口（已经废弃）
     * @return array|mixed|stdClass
     */
    public function GetColors(){
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $url = "https://api.weixin.qq.com/card/getcolors?access_token={$token}";
        load()->func('communication');
        $response = ihttp_request($url);
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty( $result )) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty( $result['errcode'] )) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
        }

        return $result;
    }

    /**
     * 颜色转换
     * @return bool
     */
    public function locationColor($color){
        $value = '';

        switch($color){
            case 'Color010' :
                $value = '#63b359';
                break;
            case 'Color020' :
                $value = '#2c9f67';
                break;
            case 'Color030' :
                $value = '#509fc9';
                break;
            case 'Color040' :
                $value = '#5885cf';
                break;
            case 'Color050' :
                $value = '#9062c0';
                break;
            case 'Color060' :
                $value = '#d09a45';
                break;
            case 'Color070' :
                $value = '#e4b138';
                break;
            case 'Color080' :
                $value = '#ee903c';
                break;
            case 'Color081' :
                $value = '#f08500';
                break;
            case 'Color082' :
                $value = '#a9d92d';
                break;
            case 'Color090' :
                $value = '#dd6549';
                break;
            case 'Color100' :
                $value = '#cc463d';
                break;
            case 'Color101' :
                $value = '#cf3e36';
                break;
            case 'Color102' :
                $value = '#5E6671';
                break;
            default :
                $value = '#a9d92d';
                break;
        }

        return $value;
    }

    public function isCouponSupported(){
        global $_W;
        $uni_setting = uni_setting($_W['uniacid'], array( 'coupon_type' ));
        if ($_W['account']['level'] != ACCOUNT_SERVICE_VERIFY && $_W['account']['level'] != ACCOUNT_SUBSCRIPTION_VERIFY) {
            return false;
        } else {
            if ($uni_setting['coupon_type'] == SYSTEM_COUPON) {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * 创建卡卷接口
     * @param $card
     * @return array|mixed|stdClass
     */
    public function CreateCard($card){
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $url = "https://api.weixin.qq.com/card/create?access_token={$token}";
        load()->func('communication');

        $card = stripslashes(urldecode(ijson_encode($card, JSON_UNESCAPED_UNICODE)));

        $response = $this->requestApi($url, $card);

        return $response;
    }

    /**
     * 删除卡券接口
     * @param $card_id
     * @return array|mixed|stdClass
     */
    public function DeleteCard($card_id){
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $url = "https://api.weixin.qq.com/card/delete?access_token={$token}";
        load()->func('communication');
        $card = json_encode(array( 'card_id' => $card_id ));
        $response = ihttp_request($url, $card);
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty( $result )) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty( $result['errcode'] )) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
        }

        return $result;
    }

    /**
     * 获取指定会员卡领取用户，填写的信息
     * @param $card_id
     * @param $code
     * @return array|mixed
     */
    public function getUserInfo($card_id, $code){
        global $_W;
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $url = "https://api.weixin.qq.com/card/membercard/userinfo/get?access_token={$token}";
        load()->func('communication');
        $response = ihttp_request($url, json_encode(array('card_id' => $card_id, 'code' => $code)));
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty( $result )) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty( $result['errcode'] )) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->error_code($result['errcode'])}");
        }

        return $result;
    }

    /**
     * 修改库存接口
     * @param $card_id
     * @param $num
     * @return array|mixed|stdClass
     */
    public function ModifyStockCard($card_id, $num){
        $data['card_id'] = trim($card_id);
        $data['increase_stock_value'] = 0;
        $data['reduce_stock_value'] = 0;
        $num = intval($num);
        ( $num > 0 ) && ( $data['increase_stock_value'] = $num );
        ( $num < 0 ) && ( $data['reduce_stock_value'] = abs($num) );
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $url = "https://api.weixin.qq.com/card/modifystock?access_token={$token}";
        load()->func('communication');
        $response = ihttp_request($url, json_encode($data));
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty( $result )) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty( $result['errcode'] )) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
        }

        return $result;
    }

    /**
     * 更新会员卡用户信息
     * @param $data
     * @return array|mixed
     */
    public function updateUser($data){
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $url = "https://api.weixin.qq.com/card/membercard/updateuser?access_token={$token}";
        load()->func('communication');
        $card = stripslashes(urldecode(ijson_encode($data, JSON_UNESCAPED_UNICODE)));
        $response = $this->requestApi($url, $card);

        return $response;
    }

    /**
     * 创建二维码接口
     * @param $card_id
     * @param $sceneid
     * @param string $expire
     * @return array|mixed|stdClass
     */
    public function QrCard($card_id, $sceneid, $expire = ''){
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $url = "https://api.weixin.qq.com/card/qrcode/create?access_token={$token}";
        load()->func('communication');
        $data = array(
            'action_name'    => 'QR_CARD',
            'expire_seconds' => "{$expire}",
            'action_info'    => array(
                'card' => array(
                    'card_id'        => strval($card_id),
                    'code'           => '',
                    'openid'         => '',
                    'is_unique_code' => false,
                    'outer_id'       => $sceneid,
                ),
            ),
        );
        $result = $this->requestApi($url, json_encode($data));

        return $result;
    }

    /**
     * 根据OpenID列表群发
     * @param $coupon
     * @param $openids
     * @return array|mixed|stdClass
     */
    public function sendCoupons($coupon, $openids){
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $post = array(
            'touser'  => $openids,
            "wxcard"  => array( 'card_id' => $coupon ),
            "msgtype" => "wxcard",
        );
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=' . $token;
        $result = $this->requestApi($url, json_encode($post));

        return $result;
    }

    /**
     * 设置卡券失效接口
     * @param $data
     * @return array|mixed|stdClass
     */
    public function UnavailableCode($data){
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $url = "https://api.weixin.qq.com/card/code/unavailable?access_token={$token}";
        load()->func('communication');
        $response = ihttp_request($url, json_encode($data));
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty( $result )) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty( $result['errcode'] )) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
        }

        return $result;
    }

    /**
     * 核销Code接口
     * @param $data
     * @return array|mixed|stdClass
     */
    public function ConsumeCode($data){
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $url = "https://api.weixin.qq.com/card/code/consume?access_token={$token}";
        load()->func('communication');
        $response = ihttp_request($url, json_encode($data));
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty( $result )) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty( $result['errcode'] )) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
        }

        return $result;
    }

    /**
     * 设置自助核销接口
     * @param $data
     * @return array|mixed|stdClass
     */
    public function selfConsume($data){
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $url = "https://api.weixin.qq.com/card/selfconsumecell/set?access_token={$token}";
        load()->func('communication');
        $response = ihttp_request($url, json_encode($data));
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty( $result )) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty( $result['errcode'] )) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
        }

        return $result;

    }

    /**
     * Code解码接口
     * @param $data
     * @return array|mixed|stdClass
     */
    public function DecryptCode($data){
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $url = "https://api.weixin.qq.com/card/code/decrypt?access_token={$token}";
        load()->func('communication');
        $response = ihttp_request($url, json_encode($data));
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty( $result )) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty( $result['errcode'] )) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
        }

        return $result;
    }

    /**
     * 查看卡券详情
     * @param $card_id
     * @return array|mixed|stdClass
     */
    public function fetchCard($card_id){
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $data = array( 'card_id' => $card_id, );
        $url = "https://api.weixin.qq.com/card/get?access_token={$token}";
        load()->func('communication');
        $response = ihttp_request($url, json_encode($data));
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty( $result )) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty( $result['errcode'] )) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
        }

        return $result['card'];
    }

    /**
     * 接口激活
     * @param $card_id
     * @return array|mixed|stdClass
     */
    public function updateCard($card_id){
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $data = array( 'card_id' => $card_id, );
        $url = "https://api.weixin.qq.com/card/membercard/activate?access_token={$token}";
        load()->func('communication');
        $response = ihttp_request($url, json_encode($data));
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty( $result )) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty( $result['errcode'] )) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
        }

        return $result;
    }

    /**
     * 接口激活
     * @param $card_id
     * @return array|mixed|stdClass
     */
    public function activateUserForm($data){
        $token = $this->getAccessToken();
        if (is_error($token)) {
            return $token;
        }
        $url = "https://api.weixin.qq.com/card/membercard/activateuserform/set?access_token={$token}";
        load()->func('communication');
        $response = ihttp_request($url, json_encode($data));
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty( $result )) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty( $result['errcode'] )) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
        }

        return $result;
    }

    public function PayConsumeCode($data){
        $code_error['uniacid'] = $this->account['uniacid'];
        $code_error['acid'] = $this->account['acid'];
        $code_error['type'] = 2;
        $code_error['message'] = $data['encrypt_code'];
        $code_error['dateline'] = time();
        $code_error['module'] = $data['module'];
        $code_error['params'] = $data['card_id'];

        $code = $this->DecryptCode(array( 'encrypt_code' => $data['encrypt_code'] ));
        if (is_error($code)) {
            pdo_insert('core_queue', $code_error);
        } else {
            $sumecode = $this->ConsumeCode(array( 'code' => $code['code'] ));
            if (is_error($sumecode)) {
                pdo_insert('core_queue', $code_error);
            } else {
                pdo_update('coupon_record', array(
                    'status'  => 3,
                    'usetime' => time(),
                ), array(
                    'acid'    => $this->account['acid'],
                    'code'    => $code['code'],
                    'card_id' => $data['card_id'],
                ));
            }
        }

        return true;
    }


    public function SignatureCard($data){
        $ticket = $this->getCardTicket();
        if (is_error($ticket)) {
            return $ticket;
        }
        $data[] = $ticket;
        sort($data, SORT_STRING);

        return sha1(implode($data));
    }


    public function BuildCardExt($id, $openid = ''){
        $acid = $this->account['acid'];
        $card_id = pdo_fetchcolumn('SELECT card_id FROM ' . tablename('coupon') . ' WHERE acid = :acid AND id = :id', array(
            ':acid' => $acid,
            ':id'   => $id,
        ));

        if (empty( $card_id )) {
            return error(-1, '卡券id不合法');
        }
        $time = TIMESTAMP;
        $sign = array(
            $card_id,
            $time,
        );
        $signature = $this->SignatureCard($sign);
        if (is_error($signature)) {
            return $signature;
        }
        $cardExt = array(
            'timestamp' => $time,
            'signature' => $signature,
        );
        $cardExt = json_encode($cardExt);

        return array(
            'card_id'  => $card_id,
            'card_ext' => $cardExt,
           
        );
    }

    public function AddCard($id){
        $card = $this->BuildCardExt($id);
        if (is_error($card)) {
            return $card;
        }
        $url = murl('activity/coupon/mine');

        return <<<EOF
			wx.ready(function(){
				wx.addCard({
					cardList:[
						{
							cardId:'{$card['card_id']}',
							cardExt:'{$card['card_ext']}'
						}
					],
					success: function (res) {
						location.href="{$url}";
					}
				});
			});
EOF;
    }

    public function OpenCard($id, $code){
        $card = $this->BuildCardExt($id);
        if (is_error($card)) {
            return $card;
        }
        $url = murl('activity/coupon/mine');

        return <<<EOF
			wx.ready(function(){
				wx.openCard({
					cardList:[
						{
							cardId : "{$card['card_id']}",
							code : "{$code}"
						}
					],
				});
			});
EOF;
    }

    public function AddCardCustom($id){
        $card = $this->BuildCardExt($id);
        if (is_error($card)) {
            return $card;
        }

        return $card;
    }

    public function ChooseCard($card_id){
        $acid = $this->account['acid'];
        if (empty( $card_id )) {
            return error(-1, '卡券不存在');
        }
        $time = TIMESTAMP;
        $randstr = random(8);
        $sign = array(
            $card_id,
            $time,
            $randstr,
            $this->account['key'],
        );
        $signature = $this->SignatureCard($sign);
        if (is_error($signature)) {
            return $signature;
        }
        $url = murl("wechat/pay/card");

        return <<<EOF
			wx.ready(function(){
				wx.chooseCard({
					shopId: '',
					cardType: '',
					cardId:'{$card_id}',
					timestamp:{$time},
					nonceStr:'{$randstr}',
					signType:'SHA1',
					cardSign:'{$signature}',
					success: function(res) {
						if(res.errMsg == 'chooseCard:ok') {
							eval("var rs = " + res.cardList);
							$.post('{$url}', {'card_id':rs[0].card_id}, function(data){
								var data = $.parseJSON(data);
								if(!data.errno) {
									var card = data.error;
									if(card.type == 'discount') {

									}
								} else {
									u.message('卡券不存在', '', 'error');
								}
							});
						} else {
							u.message('使用卡券失败', '', 'error');
						}
					}
				});
			});
EOF;
    }


    public function BatchAddCard($data){
        $acid = $this->account['acid'];
        $condition = '';
        if (!empty( $data['type'] )) {
            $condition .= " AND type = '{$data['type']}'";
        } else {
            $ids = array();
            foreach ($data as $da) {
                $da = intval($da);
                if ($da > 0) {
                    $ids[] = $da;
                }
            }
            if (empty( $ids )) {
                $condition = '';
            } else {
                $ids_str = implode(', ', $ids);
                $condition .= " AND id IN ({$ids_str})";
            }
        }

        $card = array();
        if (!empty( $condition )) {
            $card = pdo_fetchall('SELECT id, card_id FROM ' . tablename('coupon') . " WHERE acid = {$acid} " . $condition);
        }
        foreach ($card as $ca) {
            $time = TIMESTAMP;
            $sign = array(
                $ca['card_id'],
                $time,
            );
            $signature = $this->SignatureCard($sign);
            if (is_error($signature)) {
                return $signature;
            }
            $post[] = array(
                'cardId'  => trim($ca['card_id']),
                'cardExt' => array(
                    'timestamp' => $time,
                    'signature' => $signature,
                ),
            );
        }
        if (!empty( $post )) {
            $card_json = json_encode($post);
            echo <<<EOF
			<script>
				wx.ready(function(){
					wx.addCard({
						cardList : {$card_json}, // 需要添加的卡券列表
						success: function (res) {

							 alert(JSON.stringify(res));
							var cardList = res.cardList; // 添加的卡券列表信息
						}
					});
				});

			</script>
EOF;
        } else {
            echo <<<EOF
			<script>

			</script>
EOF;
        }
    }
}

define('COUPON_CODE_TYPE_TEXT', 1);
define('COUPON_CODE_TYPE_QRCODE', 2);
define('COUPON_CODE_TYPE_BARCODE', 3);

define('COUPON_TIME_TYPE_RANGE', 1);
define('COUPON_TIME_TYPE_FIX', 2);
define('COUPON_DATE_TYPE_PERMANENT', 3);

class Card {
    public  $card_id                 = '';
    public  $logo_url                = '';
    public  $brand_name              = '';
    public  $code_type               = 'CODE_TYPE_BARCODE';
    public  $title                   = '';
    public  $sub_title               = '';
    public  $color                   = 'Color082';
    public  $notice                  = '';
    public  $service_phone           = '';
    public  $description             = '';
    public  $sku                     = array( 'quantity' => 50000 );
    public  $date_info               = array( 'type' => COUPON_TIME_TYPE_RANGE );
    public  $location_id_list        = array();
    public  $get_limit               = 10;
    public  $can_share               = true;
    public  $can_give_friend         = true;
    public  $use_custom_code         = false;
    public  $bind_openid             = false;
    public  $source                  = '';
    public  $status                  = '';
    public  $promotion_url_name      = '';
    public  $promotion_url_sub_title = '';
    public  $promotion_url           = '';
    public  $custom_url_name         = '';
    public  $custom_url_sub_title    = '';
    public  $custom_url              = '';
    public  $center_title            = '';
    public  $center_sub_title        = '';
    public  $center_url              = '';
    private $type                    = '';

    public $types      = array(
        COUPON_TYPE_DISCOUNT => 'DISCOUNT',
        COUPON_TYPE_CASH => 'CASH',
        COUPON_TYPE_GROUPON => 'GROUPON',
        COUPON_TYPE_GIFT => 'GIFT',
        COUPON_TYPE_GENERAL => 'GENERAL_COUPON',
        COUPON_TYPE_SCENIC => "SCENIC_TICKET",
        COUPON_TYPE_MEMBER => "MEMBER_CARD",
        COUPON_TYPE_MOVIE => "MOVIE_TICKET",
    );

    public $code_types = array(
        COUPON_CODE_TYPE_TEXT    => 'CODE_TYPE_TEXT',
        COUPON_CODE_TYPE_QRCODE  => 'CODE_TYPE_QRCODE',
        COUPON_CODE_TYPE_BARCODE => 'CODE_TYPE_BARCODE',
    );

    static public function create($type){
        $card_class = array(
            COUPON_TYPE_DISCOUNT => 'Discount',
            COUPON_TYPE_CASH     => 'Cash',
            COUPON_TYPE_GENERAL  => 'General',
            COUPON_TYPE_GIFT     => 'Gift',
            COUPON_TYPE_GROUPON  => 'Groupon',
            COUPON_TYPE_MEMBER   => 'Member',
        );

        if (empty( $card_class[$type] )) {
            return error(-1, '卡券类型错误');
        }

        $classname = $card_class[$type] . 'Card';
        $card = new $classname();
        $card->type = $type;
        return $card;
    }

    public function setDateinfoRange($starttime, $endtime){
        $this->date_info = array(
            'type'            => 'DATE_TYPE_FIX_TIME_RANGE',
            'begin_timestamp' => strtotime($starttime),
            'end_timestamp'   => strtotime($endtime),
        );

        return true;
    }

    public function setDateinfoFix($begin, $term){
        $this->date_info = array(
            'type'             => 'DATE_TYPE_FIX_TERM',
            'fixed_term'       => $term,
            'fixed_begin_term' => $begin,
        );

        return true;
    }

    public function setCodetype($type){
        $this->code_type = $this->code_types[$type];

        return true;
    }

    public function setLocation($location){
        $store = pdo_getall('activity_stores', array( 'id' => $location ), array( 'location_id' ), 'location_id');
        if (!empty( $store )) {
            $this->location_id_list = array_keys($store);
        }
    }

    public function setCenterMenu($title, $subtitle, $url){
        $this->center_title = $title;
        $this->center_sub_title = $subtitle;
        $this->center_url = urlencode($url);

        return true;
    }

    public function setCustomMenu($title, $subtitle, $url){
        $this->custom_url_name = $title;
        $this->custom_url_sub_title = $subtitle;
        $this->custom_url = urlencode($url);

        return true;
    }

    public function setPromotionMenu($title, $subtitle, $url){
        $this->promotion_url_name = $title;
        $this->promotion_url_sub_title = $subtitle;
        $this->promotion_url = urlencode($url);

        return true;
    }

    public function setQuantity($quantity){
        $this->sku = $sku = array( 'quantity' => intval($quantity) );
    }

    public function validate(){
        if (empty( $this->logo_url )) {
            return error(7, '未设置商户logo');
        }
        if (empty( $this->brand_name )) {
            return error(8, '未设置商户名称');
        }
        if (empty( $this->title )) {
            return error(9, '未设置卡券标题');
        }
        if (empty( $this->service_phone )) {
            return error(11, '客服电话不能为空');
        }
        if (empty( $this->description )) {
            return error(12, '使用须知不能为空');
        }

        return true;
    }

    public function getBaseinfo(){
        $fields = array(
            'logo_url',
            'brand_name',
            'code_type',
            'title',
            'sub_title',
            'color',
            'notice',
            'service_phone',
            'description',
            'date_info',
            'sku',
            'get_limit',
            'use_custom_code',
            'bind_openid',
            'can_share',
            'can_give_friend',
            'location_id_list',
            'center_title',
            'center_sub_title',
            'center_url',
            'custom_url_name',
            'custom_url',
            'custom_url_sub_title',
            'promotion_url_name',
            'promotion_url',
            'promotion_url_sub_title',
            'source',
        );
        $baseinfo = array();
        foreach ($this as $filed => $value) {
            if (in_array($filed, $fields)) {
                $baseinfo[$filed] = $value;
            }
        }

        return $baseinfo;
    }

    private function getAdvinfo(){
        return array();
    }

    function getCardData(){
        $carddata = array( 'base_info' => $this->getBaseinfo(), );
        $carddata = array_merge($carddata, $this->getCardExtraData());
        $card = array(
            'card' => array(
                'card_type'                           => $this->types[$this->type],
                strtolower($this->types[$this->type]) => $carddata,
            ),
        );

        return $card;
    }

    function getCardArray(){
        $data = array(
            'card_id'                 => $this->card_id,
            'type'                    => $this->type,
            'logo_url'                => urldecode($this->logo_url),
            'code_type'               => array_search($this->code_type, $this->code_types),
            'brand_name'              => $this->brand_name,
            'title'                   => $this->title,
            'sub_title'               => $this->sub_title,
            'color'                   => $this->color,
            'notice'                  => $this->notice,
            'description'             => $this->description,
            'quantity'                => $this->sku['quantity'],
            'use_custom_code'         => intval($this->use_custom_code),
            'bind_openid'             => intval($this->bind_openid),
            'can_share'               => intval($this->can_share),
            'can_give_friend'         => intval($this->can_give_friend),
            'get_limit'               => $this->get_limit,
            'service_phone'           => $this->service_phone,
            'status'                  => $this->status,
            'is_display'              => '1',
            'is_selfconsume'          => '0',
            'promotion_url_name'      => urldecode($this->promotion_url_name),
            'promotion_url'           => urldecode($this->promotion_url),
            'promotion_url_sub_title' => urldecode($this->promotion_url_sub_title),
            'source'                  => $this->source,
        );
        $data['date_info'] = array(
            'time_type'        => $this->date_info['type'] == 'DATE_TYPE_FIX_TIME_RANGE' ? 1 : 2,
            'time_limit_start' => date('Y.m.d', $this->date_info['begin_timestamp']),
            'time_limit_end'   => date('Y.m.d', $this->date_info['end_timestamp']),
            'deadline'         => $this->date_info['fixed_begin_term'],
            'limit'            => $this->date_info['fixed_term'],
        );
        $data['date_info'] = iserializer($data['date_info']);
        $data['extra'] = iserializer($this->getCardExtraData());

        return $data;
    }
}


/**
 * 折扣券
 * Class DiscountCard
 */
class DiscountCard extends Card {
    public $discount = 0;

    public function validate(){
        $error = parent::validate();
        if (is_error($error)) {
            return $error;
        }
        if (empty( $this->discount )) {
            return error(1, '未设置折扣券折扣');
        }

        return true;
    }

    public function getCardExtraData(){
        return array( 'discount' => $this->discount, );
    }
}

/**
 * 代金券
 * Class CashCard
 */
class CashCard extends Card {
    public $least_cost  = 0;
    public $reduce_cost = 0;

    public function validate(){
        $error = parent::validate();
        if (is_error($error)) {
            return $error;
        }
        if (!isset( $this->least_cost )) {
            return error(2, '未设置代金券起用金额');
        }
        if (empty( $this->least_cost )) {
            return error(3, '未设置代金券减免金额');
        }

        return true;
    }

    public function getCardExtraData(){
        return array(
            'least_cost'  => $this->least_cost,
            'reduce_cost' => $this->reduce_cost,
        );
    }
}

/**
 * 兑换券
 * Class GiftCard
 */
class GiftCard extends Card {
    public $gift = '';

    public function validate(){
        $error = parent::validate();
        if (is_error($error)) {
            return $error;
        }
        if (empty( $this->gift )) {
            return error(4, '未设置礼品券兑换内容');
        }

        return true;
    }

    public function getCardExtraData(){
        return array( 'gift' => $this->gift, );
    }
}

/**
 * 团购券
 * Class GrouponCard
 */
class GrouponCard extends Card {
    public $deal_detail = '';

    public function validate(){
        $error = parent::validate();
        if (is_error($error)) {
            return $error;
        }
        if (empty( $this->deal_detail )) {
            return error(5, '未设置团购券详情内容');
        }

        return true;
    }

    public function getCardExtraData(){
        return array( 'deal_detail' => $this->deal_detail, );
    }
}

/**
 * 优惠券
 * Class GeneralCard
 */
class GeneralCard extends Card {
    public $default_detail = '';

    public function validate(){
        $error = parent::validate();
        if (is_error($error)) {
            return $error;
        }
        if (empty( $this->default_detail )) {
            return error(6, '未设置优惠券优惠详情');
        }

        return true;
    }

    public function getCardExtraData(){
        return array( 'default_detail' => $this->default_detail, );
    }
}

class MemberCard extends Card {

    public $background_pic_url = '';        // 商家自定义会员卡背景图。

    public $supply_bonus = true;            // 显示积分，填写true或false，如填写true，积分相关字段均为必填。(必须)
    public $bonus_url    = '';                 // 设置跳转外链查看积分详情。仅适用于积分无法通过激活接口同步的情况下使用该字段。

    public $supply_balance = false;            // 是否支持储值，填写true或false。如填写true，储值相关字段均为必填。(必须)
    public $balance_url    = '';               // 设置跳转外链查看余额详情。仅适用于余额无法通过激活接口同步的情况下使用该字段。

    public $prerogative = '';               // 会员卡特权说明。(必须)

    public $wx_activate = false;            //设置为true时会员卡支持一键开卡，不允许同时传入activate_url字段，否则设置wx_activate失效。

    public $auto_activate = false;          // 设置为true时用户领取会员卡后系统自动将其激活，无需调用激活接口，详情见自动激活。
    public $activate_url  = '';              // 激活会员卡的url。(必须)

    public $discount = '';                  // 折扣，该会员卡享受的折扣优惠,填10就是九折。

    public $need_push_on_view = false;

    public  $promotion_url_name      = '';
    public  $promotion_url_sub_title = '';
    public  $promotion_url           = '';
    public  $custom_url_name         = '';
    public  $custom_url_sub_title    = '';
    public  $custom_url              = '';
    public  $center_title            = '';
    public  $center_sub_title        = '';
    public  $center_url              = '';

    public $bonus_cleared = '积分永不清零';

    // 自定义会员信息类目，会员卡激活后显示。
    // 等级等其他字段。
    public $custom_cell1 = array();

    // 自定义会员信息类目，更多入口。
    public $custom_field1 = array();
    public $custom_field2 = array();
    public $custom_field3 = array();

    public $name_types = array(
        1 => 'FIELD_NAME_TYPE_LEVEL',
        // 等级
        2 => 'FIELD_NAME_TYPE_COUPON',
        // 优惠券
        3 => 'FIELD_NAME_TYPE_STAMP',
        // 印花
        4 => 'FIELD_NAME_TYPE_DISCOUNT',
        // 折扣
        5 => 'FIELD_NAME_TYPE_ACHIEVEMEN',
        // 成就
        6 => 'FIELD_NAME_TYPE_MILEAGE'
        // 里程
    );

    // 积分规则。(文字说明)
    public $bonus_rules = '';

    // 储值说明。(文字说明)
    public $balance_rules = '';

    // 微信买单，积分规则。
    public $bonus_rule = array();

    public $use_all_locations = true;   // 会员卡支持门店

    // 商家服务类型
    public $business_service = array();

    // 开始快速支付
    public $pay_info = array();

    // 会员激活字段
    public $required_form = array();
    public $optional_form = array();

    //// 其他
    public $bonus;
    public $record_bonus;
    public $balance;
    public $record_balance;
    public $notify_optional = array(
        'is_notify_bonus' => false,
        'is_notify_balance' => false,
        'is_notify_custom_field1' => false
    );

    /**
     * 显示积分，是否显示积分，与外链URL。
     * @param bool $swtich
     * @param string $url
     * @return bool
     */
    public function setSupplyBonus($swtich = true, $url = ''){
        $this->supply_bonus = $swtich ? true : false;
        $this->bonus_url = $url;

        return true;
    }

    /**
     * 显示金额，是否显示金额，与外链URL。
     * 开启时，必须填入URL。
     * @param bool $swtich
     * @param string $url
     * @return bool
     */
    public function setSupplyBalance($swtich = true, $url = ''){
        $this->supply_balance = $swtich ? true : false;
        $this->balance_url = $url;

        return true;
    }

    /**
     * 自定义会员信息类目（限制三个）
     * @param $level
     * @param $name_type
     * @param $url
     * @return bool
     */
    public function setCustomField($level, $name_type, $url){
        $data = array(
            'name_type' => $this->name_types[$name_type],
            'url'       => $url,
        );

        switch ($level) {
            case 1 :
                $this->custom_field1 = $data;
                break;
            case 2 :
                $this->custom_field2 = $data;
                break;
            case 3 :
                $this->custom_field3 = $data;
                break;
        }

        return true;
    }

    /**
     * 自定义会员信息类目，会员卡激活后显示。（限制三个）
     * @param $level
     * @param $name
     * @param $tips
     * @param $url
     * @return bool
     */
    public function setCustomCell($name, $tips, $url){
        $data = array(
            'name' => $name,
            'tips' => $tips,
            'url'  => $url,
        );

        $this->custom_cell1 = $data;
        return true;

        /*switch ($level) {
            case 1 :
                $this->custom_cell1 = $data;
                break;
            case 2 :
                $this->custom_cell2 = $data;
                break;
            case 3 :
                $this->custom_cell3 = $data;
                break;
        }

        return true;*/
    }

    /**
     * 积分规则。用于微信买单功能。
     * @param $key
     * @param $value
     * @return bool
     */
    public function setBonusRule($key, $value){
        $keys = array(
            'cost_money_unit',
            // 消费金额。以分为单位。
            'increase_bonus',
            // 对应增加的积分。
            'max_increase_bonus',
            // 用户单次可获取的积分上限。

            'init_increase_bonus',
            // 初始设置积分。

            'cost_bonus_unit',
            // 每使用5积分。
            'reduce_money',
            // 抵扣xx元，（这里以分为单位）

            'least_money_to_use_bonus',
            // 抵扣条件，满xx元（这里以分为单位）可用。
            'max_reduce_bonus',
            // 抵扣条件，单笔最多使用xx积分。
        );

        if (!in_array($key, $keys)) {
            return false;
        }

        $this->bonus_rule[$key] = $value;

        return true;
    }

    public function setBusinessService($value){
        if(!empty($value)){
            $this->business_service[] = $value;
        }
    }

    /**
     * 设置门店支持
     * @param $location
     */
    public function setLocation($location){
        $store = pdo_getall('activity_stores', array( 'id' => $location ), array( 'location_id' ), 'location_id');
        if (!empty( $store )) {
            $this->location_id_list = array_keys($store);
            $this->use_all_locations = false;   // 若设置支持门店，则所有门店支持自动关闭。
        }
    }

    public function setPayInfo($swtich = true){

        if($swtich){
            $this->pay_info['swipe_card'] = array(
                'is_swipe_card' => true
            );
        }else{
            $this->pay_info['swipe_card'] = array(
                'is_swipe_card' => false
            );
        }

    }

    public function getBaseinfo(){

        $fields = array(
            'logo_url',
            'code_type',
            'brand_name',
            'title',
            'color',
            'notice',
            'description',
            'sku',
            'date_info',
            'use_custom_code',
            'bind_openid',
            'service_phone',

            'location_id_list',
            'use_all_locations',
            'center_title',
            'center_sub_title',
            'center_url',

            'custom_url_name',
            'custom_url',
            'custom_url_sub_title',

            'promotion_url_name',
            'promotion_url',
            'promotion_url_sub_title',

            'get_limit',

            'can_share',
            'can_give_friend',
            'need_push_on_view',
            'pay_info'
        );
        $baseinfo = array();
        foreach ($this as $filed => $value) {
            if (in_array($filed, $fields)) {
                $baseinfo[$filed] = $value;
            }
        }

        return $baseinfo;
    }

    /**
     * 拓展字段
     * @return array
     */
    public function getCardExtraData(){
        $extraData = array(
            'background_pic_url' => $this->background_pic_url,
            'wx_activate'        => $this->wx_activate,
            'activate_url'       => urldecode($this->activate_url),
            'auto_activate'      => $this->auto_activate,
            'supply_bonus'       => $this->supply_bonus,
            'bonus_url'          => $this->bonus_url,
            'supply_balance'     => $this->supply_balance,
            'balance_url'        => $this->balance_url,
            'prerogative'        => $this->prerogative,
            'bonus_rule'         => $this->bonus_rule,
            'custom_cell1'       => $this->custom_cell1,
            'advanced_info'      => array(
                'business_service' => $this->business_service,
            ),
            'discount'           => $this->discount,
        );

        return $extraData;
    }

    function getCardArray(){
        $data = array(
            'card_id'                 => $this->card_id,
            'background_pic_url'      => urldecode($this->background_pic_url),
            'logo_url'                => urldecode($this->logo_url),
            'code_type'               => array_search($this->code_type, $this->code_types),
            'brand_name'              => $this->brand_name,
            'title'                   => $this->title,
            //'sub_title'               => $this->sub_title,
            'color'                   => $this->color,
            'notice'                  => $this->notice,
            'description'             => $this->description,
            //'quantity'                => $this->sku['quantity'],
            'sku'                     => iserializer($this->sku),
            'date_info'               => iserializer($this->date_info),
            'use_custom_code'         => intval($this->use_custom_code),
            'bind_openid'             => intval($this->bind_openid),
            'can_share'               => intval($this->can_share),
            'can_give_friend'         => intval($this->can_give_friend),
            'need_push_on_view'       => intval($this->need_push_on_view),
            'get_limit'               => $this->get_limit,
            'service_phone'           => $this->service_phone,
            'business_service'        => iserializer($this->business_service),
            'custom_url_name'         => $this->custom_url_name,
            'custom_url'              => urldecode($this->custom_url),
            'custom_url_sub_title'    => $this->custom_url_sub_title,
            'promotion_url_name'      => $this->promotion_url_name,
            'promotion_url'           => urldecode($this->promotion_url),
            'promotion_url_sub_title' => $this->promotion_url_sub_title,
            'use_all_locations'       => 1,
            'center_title'            => $this->center_title,
            'center_sub_title'        => $this->center_sub_title,
            'center_url'              => $this->center_url,
            'prerogative'             => $this->prerogative,
            'auto_activate'           => intval($this->auto_activate),
            'wx_activate'             => intval($this->wx_activate),
            'supply_bonus'            => intval($this->supply_bonus),
            'bonus_url'               => urldecode($this->bonus_url),
            'supply_balance'          => intval($this->supply_balance),
            'balance_url'             => urldecode($this->balance_url),
            'custom_field1'           => iserializer($this->custom_field1),
            'custom_field2'           => iserializer($this->custom_field2),
            'custom_field3'           => iserializer($this->custom_field3),
            'bonus_cleared'           => $this->bonus_cleared,
            'bonus_rules'             => $this->bonus_rules,
            'balance_rules'           => $this->balance_rules,
            'activate_url'            => urldecode($this->activate_url),
            'custom_cell1'            => iserializer($this->custom_cell1),
            'bonus_rule'              => iserializer($this->bonus_rule),
            'discount'                => $this->discount,
            'pay_info'                => iserializer($this->pay_info),
            'status'                  => $this->status,
        );

        return $data;
    }

    /**
     * 设置激活必填项
     * @param null $value
     */
    public function setRequiredForm($value = null){
        if(empty($this->required_form)){
            $this->required_form['can_modify'] = false;
        }

        if(!empty($value)){
            $this->required_form['common_field_id_list'] = $value;
        }
    }

    /**
     * 设置激活选填项
     * @param null $value
     */
    public function setOptionalForm($value = null){

        if(empty($this->optional_form)){
            $this->optional_form['can_modify'] = false;
        }

        if(!empty($value)){
            $this->optional_form['common_field_id_list'] = $value;
        }
    }

    public function activateUserForm($cardid){
        return array(
            'card_id' => $cardid,
            'required_form' => $this->required_form,
            'optional_form' => $this->optional_form,
        );
    }

    /**
     * 设置积分变更及记录
     * @param $bonus
     * @param $record
     * @param bool $notify
     */
    public function setRecordBonus($bonus, $record, $notify = true){
        $this->bonus = $bonus;  // 积分变动值
        $this->record_bonus = $record; // 积分变更记录
        $this->notify_optional['is_notify_bonus'] = $notify;
    }

    /**
     * 设置余额变更及记录
     * @param $balance  // 增减值
     * @param $record   // 增减值
     * @param $notify
     */
    public function setRecordBalance($balance, $record, $notify = true){
        $this->balance = $balance;  // 当前变动值
        $this->record_balance = $record; // 积分变更记录
        $this->notify_optional['is_notify_balance'] = $notify;
    }

    public function updateUser($openid, $card_id, $code, $sendWechat = true, $changeCredit = true){
        global $_W;

        $info = array();

        load()->model('mc');
        $member = mc_fetch($openid);

        $info['card_id'] = $card_id;
        $info['code'] = $code;

        if(isset($this->bonus)){
            if($changeCredit){
                $status = mc_credit_update(mc_openid2uid($openid), 'credit1', $this->bonus, array(1, $this->record_bonus, 'system', 0, 0, 2) );
                $info['bonus'] = $member['credit1'] + $this->bonus;
            }

            $info['bonus'] = $member['credit1'];
            $info['record_bonus'] = "$this->record_bonus";
        }

        if(isset($this->balance)){
            if($changeCredit){
                $status = mc_credit_update(mc_openid2uid($openid), 'credit2', $this->balance, array(1, $this->record_balance, 'system', 0, 0, 2) );
                $info['balance'] = ($member['credit2'] + $this->balance) * 100;
            }

            $info['balance'] = $member['credit2'] * 100;
            $info['record_balance'] = "$this->record_balance";
        }

        if($sendWechat){
            $info['notify_optional'] = $this->notify_optional;

            $cardInfo = pdo_get('member_wechat_card', array('uniacid' => $_W['uniacid'], 'card_id' => $card_id));

            if(empty($cardInfo['supply_bonus'])){
                unset($info['bonus'], $info['record_bonus']);
            }

            if(empty($cardInfo['supply_balance'])){
                unset($info['balance'], $info['record_balance']);
            }

            $api = new coupon();
            return $api->updateUser($info);
        }

        return $info;
    }

    public function holdCard($openid){
        global $_W;

        return pdo_get('wechat_card_record', array('uniacid' => $_W['uniacid'], 'openid' => $openid, 'status' => 2));
    }

}