<?php
/**
 * [WeEngine System] Copyright (c) 2014 aiwangsports.com
 * WeEngine is NOT a free software, it under the license terms, visited http://www.aiwangsports.com/ for more details.
 */

define('IN_GW', true);

if(in_array($action, array('profile', 'device', 'callback', 'appstore', 'sms'))) {
	$do = $action;
	$action = 'redirect';
}
if($action == 'touch') {
	exit('success');
}
