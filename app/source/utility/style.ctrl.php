<?php
/**
 * [WeEngine System] Copyright (c) 2014 aiwangsports.com
 * WeEngine is NOT a free software, it under the license terms, visited http://www.aiwangsports.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
header('content-type: text/css');
$src = '';
if(!empty($_W['styles']['imgdir'])) {
	$src = $_W['styles']['imgdir'];
}