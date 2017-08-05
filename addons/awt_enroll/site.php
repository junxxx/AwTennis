<?php
/**
 * 爱网网球报名系统模块微站定义
 *
 * @author junxxx & future
 * @url http://bbs.aiwangsports.com/
 */
defined('IN_IA') or exit('Access Denied');

class Awt_enrollModuleSite extends WeModuleSite {

	public function doMobileAboutus() {
		//这个操作被定义用来呈现 功能封面
	}
	public function doMobileRanking() {
		//这个操作被定义用来呈现 功能封面
	}
	public function doMobileEnroll() {
		//这个操作被定义用来呈现 功能封面
	}
	public function doWebArticle() {
		//这个操作被定义用来呈现 管理中心导航菜单
		echo 'article';
	}
	public function doWebMatch() {
		//这个操作被定义用来呈现 管理中心导航菜单
	}
	public function doWebSetting() {
		//这个操作被定义用来呈现 管理中心导航菜单
	}

}