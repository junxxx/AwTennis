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


}