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

	public function doMobileAboutus() {
		//这个操作被定义用来呈现 功能封面
		$this->_exec(__FUNCTION__, 'aboutus', false);
	}
	public function doMobileRanking() {
		//这个操作被定义用来呈现 功能封面
		$this->_exec(__FUNCTION__, 'ranking', false);
	}
	public function doMobileEnroll() {
		//这个操作被定义用来呈现 功能封面
		$this->_exec(__FUNCTION__, 'enroll', false);
	}
	public function doWebArticle() {
		//这个操作被定义用来呈现 管理中心导航菜单
		$this->_exec(__FUNCTION__, 'list');
	}
	public function doWebMatch() {
		//这个操作被定义用来呈现 管理中心导航菜单
	}
	public function doWebSetting() {
		//这个操作被定义用来呈现 管理中心导航菜单
	}


}