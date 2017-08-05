<?php

defined('IN_IA') or exit( 'Access Denied' );

//ini_set('display_errors', true);
//error_reporting(E_ALL);

global $_W, $_GPC;

load()->model('mc');

$type = intval($_GPC['type']);
$coupon_title = activity_coupon_type_label($type);
$coupon_title = $coupon_title[0];

if (checksubmit('submit')) {
    $coupon = Card::create($type);

    // base_info start
    // 背景图片
    if(empty($_GPC['background_pic_url']) && empty($_GPC['color'])){
        message('会员卡背景，至少为图片或颜色。', '', 'error');
    }

    // 背景图片
    !empty($_GPC['background_pic_url']) && $coupon->background_pic_url = 'BackgroundPicUrl';
    // 背景颜色
    !empty($_GPC['color']) && $coupon->color = $_GPC['color'];

    // 会员卡LOGO
    empty($_GPC['logo_url']) && message('会员卡LOGO不可以为空。', '', 'error');
    $coupon->logo_url = urlencode(trim($_GPC['logo_url']));

    // 商户名称
    if(empty($_GPC['brand_name']) || strlen($_GPC['brand_name']) > 36 ){
        message('商户名字不允许为空，且字数上限为12个汉字。', '', 'error');
    }
    $coupon->brand_name = substr(trim($_GPC['brand_name']), 0, 36);

    // 会员卡副标题
    $coupon->title = substr(trim($_GPC['title']), 0, 27);
    // 使用提醒
    $coupon->notice = trim($_GPC['notice']);
    // 商家号码
    $coupon->service_phone = trim($_GPC['service_phone']);
    // 使用说明（须知）
    $coupon->description = trim($_GPC['description']);

    // 有效时间
    if (intval($_GPC['time_type']) == COUPON_TIME_TYPE_RANGE) {
        $coupon->setDateinfoRange($_GPC['time_limit']['start'], $_GPC['time_limit']['end']);
    } elseif(intval($_GPC['time_type']) == COUPON_TIME_TYPE_FIX) {
        $coupon->setDateinfoFix($_GPC['deadline'], $_GPC['limit']);
    }else if(intval($_GPC['time_type']) == COUPON_DATE_TYPE_PERMANENT){
        $coupon->date_info = array('type' => 'DATE_TYPE_PERMANENT');
    }

    // 商品信息
    // 卡券库存的数量，不支持填写0，上限为100000000。
    //$coupon->sku = array( 'quantity' => 0 );
    $coupon->setQuantity(0);
    // 获取上限
    $coupon->get_limit = 1;
    // 自定义CODE
    $coupon->use_custom_code = false;
    // 赠送好友
    $coupon->can_give_friend = false;

    // 自定义按钮标题，副标题，跳转URL。
    $coupon->setCenterMenu('center_title', 'center_sub_title', 'center_url');

    // base_info end
    // 积分设置
    $coupon->setSupplyBonus(true, 'SupplyBonusURL');
    // 余额设置
    $coupon->setSupplyBalance(true, 'SupplyBalanceURL');

    // 会员卡特权说明。
    $coupon->prerogative = trim($_GPC['prerogative']);
    $coupon->auto_activate = true;

//    $coupon->setCustomField(1, 1, 'F1_URL');
//    $coupon->setCustomField(2, 2, 'F2_URL');
//
//    $coupon->setCustomCell(1, 'CELL_NAME_1', 'CELL_TIPS_1', 'C1_URL');
//    $coupon->setCustomCell(2, 'CELL_NAME_2', 'CELL_TIPS_2', 'C2_URL');

    // 消费{cost_money_unit}元得{increase_bonus}分，单笔最多可获得{max_increase_bonus}积分。
    $coupon->setBonusRule('cost_money_unit', max(0, trim($_GPC['cost_money_unit'])) );
    $coupon->setBonusRule('increase_bonus', max(0, trim($_GPC['increase_bonus'])) );

    $coupon->setBonusRule('max_increase_bonus', max(0, trim($_GPC['max_increase_bonus'])) );

    // 起始积分为{init_increase_bonus}.
    $coupon->setBonusRule('init_increase_bonus', $_GPC['init_increase_bonus']);

    // 每{cost_bonus_unit}积分，抵扣{reduce_money}元钱。
    $coupon->setBonusRule('cost_bonus_unit', $_GPC['cost_bonus_unit']);
    $coupon->setBonusRule('reduce_money', $_GPC['increase_bonus']);

    // 满{least_money_to_use_bonus}元才可用积分抵扣，单次最多抵扣{max_reduce_bonus}积分。
    $coupon->setBonusRule('least_money_to_use_bonus', $_GPC['least_money_to_use_bonus']);
    $coupon->setBonusRule('max_reduce_bonus', $_GPC['max_reduce_bonus']);

    // 会员可享受折扣（100 - {discount} * 10）。
    $coupon->discount = 100 - trim($_GPC['discount']) * 10 ;

    var_dump($coupon->getCardData());
    exit();
    $coupon_api->CreateCard();
}

//echo '<pre>';
//var_dump($coupon->getCardData());
template('activity/coupon-test');
exit();


if(checksubmit('submit')){

    $coupon = Card::create($type);
    $coupon->logo_url = empty( $_GPC['logo_url'] ) ? urlencode($setting['logourl']) : urlencode(trim($_GPC['logo_url']));
    $coupon->brand_name = $_GPC['brand_name'];
    $coupon->title = substr(trim($_GPC['title']), 0, 27);
    $coupon->sub_title = trim($_GPC['sub_title']);
    $coupon->color = empty( $_GPC['color'] ) ? 'Color082' : $_GPC['color'];
    $coupon->notice = $_GPC['notice'];
    $coupon->service_phone = $_GPC['service_phone'];
    $coupon->description = $_GPC['description'];
    $coupon->get_limit = intval($_GPC['get_limit']);
    $coupon->can_share = intval($_GPC['can_share']) ? true : false;
    $coupon->can_give_friend = intval($_GPC['can_give_friend']) ? true : false;

    if (intval($_GPC['time_type']) == COUPON_TIME_TYPE_RANGE) {
        $coupon->setDateinfoRange($_GPC['time_limit']['start'], $_GPC['time_limit']['end']);
    } else {
        $coupon->setDateinfoFix($_GPC['deadline'], $_GPC['limit']);
    }

    if (!empty( $_GPC['promotion_url_name'] ) && !empty( $_GPC['promotion_url'] )) {
        $coupon->setPromotionMenu($_GPC['promotion_url_name'], $_GPC['promotion_url_sub_title'], $_GPC['promotion_url']);
    }

    if (!empty( $_GPC['location-select'] )) {
        $location_list = explode('-', $_GPC['location-select']);
        if (!empty( $location_list )) {
            $coupon->setLocation($location_list);
        }
    }

    $coupon->setCustomMenu('立即使用', '', murl('entry', array( 'm' => 'paycenter', 'do' => 'consume' ), true, true));
    $coupon->setQuantity($_GPC['quantity']);
    $coupon->setCodetype($_GPC['code_type']);
    $coupon->discount = intval($_GPC['discount']);
    $coupon->least_cost = $_GPC['least_cost'] * 100;
    $coupon->reduce_cost = $_GPC['reduce_cost'] * 100;
    $coupon->gift = $_GPC['gift'];
    $coupon->deal_detail = $_GPC['deal_detail'];
    $coupon->default_detail = $_GPC['default_detail'];

    $check = $coupon->validate();

    var_dump($coupon->getCardData());
}else{
    template('activity/coupon-test');
}

