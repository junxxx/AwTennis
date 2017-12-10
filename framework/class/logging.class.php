<?php
/**
 * File  logging.class.php
 * Created by https://github.com/junxxx
 * Email: hprjunxxx@gmail.com
 * Author: junxxx
 * Datetime: 2017/8/20 12:03
 */
defined('IN_IA') or exit('Access Denied');

class Log {
	private $path = null;
	private $fileName = null;

	public function __construct( $path, $fileName)
	{
		$this->path = $path;
		$this->fileName = date('Ymd').$fileName.'.log';
	}

	public function write($message)
	{
		$path = $this->path;
		$filename = $path.$this->fileName;
		load()->func('file');
		mkdirs(dirname($filename));
		$content = date('Y-m-d H:i:s') . ":\n------------\n";
		if(is_string($message)) {
			$content .= "String:\n{$message}\n";
		}
		if(is_array($message)) {
			$content .= "Array:\n";
			foreach($message as $key => $value) {
				$content .= sprintf("%s : %s ;\n", $key, $value);
			}
		}
		$content .= "\n";

		$fp = fopen($filename, 'a+');
		fwrite($fp, $content);
		fclose($fp);
	}
}