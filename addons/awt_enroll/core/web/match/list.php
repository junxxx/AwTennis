<?php
/**
 * File  list.php
 * Created by https://github.com/junxxx
 * Email: hprjunxxx@gmail.com
 * Author: junxxx
 * Datetime: 2017/8/13 12:34
 */
defined('IN_IA') or exit('Access Denied!');
$operation = isset($_GPC['op']) ? $_GPC['op'] : 'display';
global $_W, $_GPC;

include $this->template('web/match/list');