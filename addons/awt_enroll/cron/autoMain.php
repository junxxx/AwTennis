<?php
/**
 * File  autoMain.php
 * cron 自动转正脚本
 * Created by https://github.com/junxxx
 * Email: hprjunxxx@gmail.com
 * Author: junxxx
 * Datetime: 2017/8/20 10:32
 */
/*TODO 运行模式过滤,安全过滤*/
define('IN_SYS', true);
ini_set('display_errors', 1);
error_reporting(E_ALL);
require '../../../framework/bootstrap.inc.php';
require IA_ROOT . '/web/common/bootstrap.sys.inc.php';
load()->web('common');
load()->web('template');

$modulename ='awt_enroll';
$site = WeUtility::createModuleSite($modulename);

class ActivityCron {

	private $uniacid = 2;
	private $activityTable = 'enroll_activities';
	private $activityLogTable = 'enroll_activitie_logs';

	public function __construct()
	{
	    if (PHP_SAPI != 'cli')
	        exit('Access Denied!');
	}

	/*自动转正*/
	public function autoMain()
	{
		load()->classs('logging');
		$path = AWT_ENROLL_PATH.'/data/LOGS/cron/';
		$logFile = 'autoMain';
		$log = new Log($path, $logFile);
		$message = 'test logfile';
		$log->write($message);

		$reservePlayers = m('activity')->getCronActivityLogs();
		print_r($reservePlayers);
		if (!empty($reservePlayers)){
			foreach ($reservePlayers as $player){

			}
		}
	}
	

	public function run()
	{
		$this->autoMain();
	}

}

$cron = new ActivityCron();
$cron->run();
