<?php
/**
 * 前台文章详情页
 * Created by PhpStorm.
 * User: panj
 * Date: 2017/8/15
 * Time: 15:09
 */

if (!defined('IN_IA')) {
    die('Access Denied');
}
global $_W, $_GPC;
$uniacid = $_W['uniacid'];
$articleTable = 'enroll_articles';
if ($_W['isajax']) {
    $id = intval($_GPC['id']);
    if (!empty($id))
    {
        $where = ' WHERE 1 AND uniacid=:uniacid AND id=:id AND is_display=:is_display LIMIT 1';
        $params = array(
            ':uniacid' => $uniacid,
            ':id' => $id,
            ':is_display' => 1,
        );
        $sql = 'SELECT * FROM '.tablename($articleTable).$where;
        $article = pdo_fetch($sql, $params);
        if(!empty($article)){
            $article['createtime'] = date('Y-m-d H:i:s',$article['createtime']);
            show_json(1,array('article' => $article));
        }
    }
    show_json(0,'文章不存在');
}
include $this->template('main/aboutus');