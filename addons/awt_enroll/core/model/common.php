<?php
/**
 * File  common.php
 * Created by https://github.com/junxxx
 * Email: hprjunxxx@gmail.com
 * Author: junxxx
 * Datetime: 2017/8/15 23:12
 */
if (!defined('IN_IA')) {
	die('Access Denied');
}
class AWT_Tenroll_Common
{

	public function getAccount()
	{
		global $_W;
		load()->model('account');
		if (!empty($_W['acid'])) {
			return WeAccount::create($_W['acid']);
		} else {
			$acid = pdo_fetchcolumn("SELECT acid FROM " . tablename('account_wechats') . " WHERE `uniacid`=:uniacid LIMIT 1", array(':uniacid' => $_W['uniacid']));
			return WeAccount::create($acid);
		}
		return false;
	}
}