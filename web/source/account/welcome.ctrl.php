<?php
/**
 * [WeEngine System] Copyright (c) 2014 aiwangsports.com
 * WeEngine is NOT a free software, it under the license terms, visited http://www.aiwangsports.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
if (!empty($_W['uid'])) {
	header('Location: '.url('account/display'));
	exit;
}
header("Location: ".url('user/login'));
exit;
