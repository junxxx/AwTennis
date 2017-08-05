<?php
/**
 * [WeEngine System] Copyright (c) 2014 aiwangsports.com
 * WeEngine is NOT a free software, it under the license terms, visited http://www.aiwangsports.com/ for more details.
 */

define('IN_GW', true);
if ($controller == 'system' && $action == 'content_provider') {
	$system_activie = 2;
} else {
	$system_activie = 1;
}
