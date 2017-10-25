<?php
/*手机端ranking 页面*/
if (!defined('IN_IA')) {
	die('Access Denied');
}
global $_W, $_GPC;

if ($_W['isajax']) {
    $url = 'http://info.sports.sina.com.cn/rank/atp.php?sina-fr=bd.ala.top.tennis';
    $html = file_get_contents($url);
    /*去掉http头*/
    preg_match("/Content-Length:.?(\d+)/", $html, $matches);
    @$length = $matches[1];
    $html = substr($html, - $length);

    /*gb2312编码转换为utf-8*/
    $html = mb_convert_encoding($html, 'UTF-8', 'gb2312');

    if (!stripos('"Content-Type"', $html) && !stripos('content="text/html;', $html))
    {
        $html = str_replace('charset=gb2312', 'charset=utf-8', $html);
    }
    $doc = new DOMDocument();
    @$doc->loadHTML('<?xml encoding="UTF-8">'.$html);
    $xpath = new DOMXPath($doc);
    $playerList = $xpath->query('//table[@class="ranktable"]');
    $ranking = array();
    foreach ($playerList as $container)
    {
        $arr = $container->getElementsByTagName('tr');
        $skip = 0;
        foreach ($arr as $item)
        {
            $skip++;
            if ($skip <= 2)
            {
                continue;
            }
            $row = $item->getElementsByTagName('td');
            $player = array();
            foreach ($row as $k => $v)
            {
                $player[] = $v->nodeValue;
            }
            $ranking[] = array(
                'rank' => $player[0],
                'name' => $player[2],
                'country' => $player[3],
                'score' => $player[4],
                'attendMatch' => $player[5],
            );
        }
    }

    show_json(1, array('list' => $ranking));
}
include $this->template('main/ranking');