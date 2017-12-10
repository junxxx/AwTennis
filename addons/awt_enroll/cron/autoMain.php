<?php
/**
 * File  autoMain.php
 * cron 自动转正脚本
 * Created by https://github.com/junxxx
 * Email: hprjunxxx@gmail.com
 * Author: junxxx
 * Datetime: 2017/8/20 10:32
 */
echo PHP_SAPI;
if (PHP_SAPI != 'cli')
{
    exit;
}
define('IN_SYS', true);
ini_set('display_errors', 1);
error_reporting(E_ALL);
require '../../../framework/bootstrap.inc.php';

$modulename ='awt_enroll';
$site = WeUtility::createModuleSite($modulename);

class ActivityCron {

	private $uniacid = 2;
	private $activityTable = 'enroll_activities';
	private $activityLogTable = 'enroll_activitie_logs';
	private $log = null;
	private $startTime = null;
	private $finishTime = null;

	public function __construct()
	{
	    if (PHP_SAPI != 'cli')
	        exit('Access Denied!');
	    load()->classs('process.lock');
        load()->classs('logging');
        $path = AWT_ENROLL_PATH.'/data/LOGS/cron/';
        $logFile = 'autoMain';
        $this->log = new Log($path, $logFile);
        $this->startTime = microtime();
        $this->log->write("startTime: $this->startTime");
	}

	/*自动转正*/
	public function autoMain()
	{
        $LOCK = new ProcessLock();
        if (!$LOCK->getLock(__FUNCTION__ . 'lock'))
        {
            $this->log->write('file locked');
            exit('file locked');
        }
	    //		$reservePlayers = m('activity')->getCronActivityLogs();
//		print_r($reservePlayers);
//		if (!empty($reservePlayers)){
//			foreach ($reservePlayers as $player){
//
//			}
//		}


        $template = $this->getMsgTemplate();
        $template =$template[0];
        $touser = 'oFxpOw7Ms5PEWDZoNa2xV7amljxU';
        $template_id = $template['template_id'];
        $msg = array(
            'first'    => array('value' => "恭喜您报名成功", "color" => "#4a5077"),
            'keyword1' => array('title' => '活动名称', 'value' => 121, "color" => "#4a5077"),
            'keyword2' => array('title' => '活动说明', 'value' => date('Y-m-d'), "color" => "#4a5077"),
            'keyword3' => array('title' => '活动时间', 'value' => date('Y-m-d'), "color" => "#4a5077"),
            'keyword4' => array('title' => '活动地址', 'value' => date('Y-m-d'), "color" => "#4a5077"),
            'keyword5' => array('title' => '联系电话', 'value' => date('Y-m-d'), "color" => "#4a5077"),
            'remark'   => array('value' => "\r\n\n测试 谢谢", "color" => "#4a5077"),
        );
        $account = $this->getAccount();
        $t = m('message')->sendTplNotice($touser, $template_id, $msg, $url = '', $account);
        var_dump($t);
	}

	public function getMsgTemplate()
    {
        global $_W;
        $_W['uniacid'] = $this->uniacid;

        $template = m('common')->getMsgTemplate();
        if (empty($template)){
            return false;
        }
        return$template;
    }

    public function getAccount()
    {
        load()->model('account');
        $acid = pdo_fetchcolumn("SELECT acid FROM " . tablename('account_wechats') . " WHERE `uniacid`=:uniacid LIMIT 1", array(':uniacid' => $this->uniacid));
        return !empty($acid) ? WeAccount::create($acid) : false;
    }

	public function run()
	{
		$this->autoMain();
		$this->finishTime = microtime();
		$totalTime = $this->finishTime - $this->startTime;

		$this->log->write("finishTime: $this->finishTime\ntotalTime:$totalTime");
	}

}

$cron = new ActivityCron();
$cron->run();
