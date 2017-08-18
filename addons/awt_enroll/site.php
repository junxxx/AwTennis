<?php
/**
 * 爱网网球报名系统模块微站定义
 *
 * @author junxxx & future
 * @url http://bbs.aiwangsports.com/
 */
defined('IN_IA') or exit('Access Denied');
require IA_ROOT . '/addons/awt_enroll/defines.php';
require AWT_ENROLL_INC . 'functions.php';
require AWT_ENROLL_INC . 'core.php';
//require AWT_ENROLL_INC . 'plugin/plugin.php';
//require AWT_ENROLL_INC . 'plugin/plugin_model.php';
require_once AWT_ENROLL_INC_MODEL . 'cache.class.php';

class Awt_enrollModuleSite extends Core {

	/*前台关于我们展示*/
	public function doMobileAboutus() {
		$this->_exec(__FUNCTION__, 'aboutus', false);
	}

	/*前台积分榜*/
	public function doMobileRanking() {
		$this->_exec(__FUNCTION__, 'ranking', false);
	}

	/*前台活动报名入口*/
	public function doMobileEnroll() {
		$this->_exec(__FUNCTION__, 'list', false);
	}

    /*前台个人中心入口*/
    public function doMobileMember() {
        $this->_exec(__FUNCTION__, 'center', false);
    }

	/*后台文章管理*/
	public function doWebArticle() {
		$this->_exec(__FUNCTION__, 'list');
	}

	/*后台活动管理*/
	public function doWebMatch() {
		$this->_exec(__FUNCTION__, 'match');
	}
	public function doWebSetting() {
		//这个操作被定义用来呈现 管理中心导航菜单
	}


	public function doMobileSui() {
	    include $this->template('sui/demo');
    }
    public function doMobileMui() {
        include $this->template('mui/demo');
    }

	public function doMobileNotice(){
		$openid = 'oFxpOw7Ms5PEWDZoNa2xV7amljxU';
		$tpid = 'P3JR-nf7AaMlEUaROs1fStJpRvCbQnOlv6XsFkZNzG4';
		$msg = array(
			'first' => array(
				'value' => "赛管理员您好，已有班级成功报名",
				"color" => "#4a5077"
			),
			'orderProductPrice' =>
				array('title' => '退款金额', 'value' => '￥100 元', "color" => "#4a5077"),
			'orderProductName' => array('title' => '商品详情', 'value' => 12, "color" => "#4a5077"),
			'orderName' => array('title' => '订单编号', 'value' => 123, "color" => "#4a5077"),
			'remark' => array('value' => "\r\n模板消息测试", "color" => "#4a5077")
		);
		$t = m('message')->sendTplNotice($openid, $tpid, $msg);
		var_dump($t);
	}

}