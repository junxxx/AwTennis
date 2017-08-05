<?php
/**
 * 爱网网球报名系统模块订阅器
 *
 * @author junxxx & future
 * @url http://bbs.aiwangsports.com/
 */
defined('IN_IA') or exit('Access Denied');

class Awt_enrollModuleReceiver extends WeModuleReceiver {
	public function receive() {
		$type = $this->message['type'];
		//这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看爱网文档来编写你的代码
	}
}