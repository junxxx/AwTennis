<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;

$_W['page']['title'] = '会员卡管理 - 会员中心';
$dos = array('display', 'manage', 'delete', 'coupon', 'submit', 'modal', 'record', 'notice', 'editor', 'sign','ajax', 'wechat');
$do = in_array($do, $dos) ? $do : 'other';
load()->model('mc');
load()->model('activity');
activity_coupon_type_init();
$setting = pdo_fetch("SELECT * FROM ".tablename('mc_card')." WHERE uniacid = '{$_W['uniacid']}'");
if($do == 'ajax') {
	$op = trim($_GPC['op']);
	$sql = 'SELECT `uniacid` FROM ' . tablename('mc_card') . " WHERE `uniacid` = :uniacid";
	$setting = pdo_fetch($sql, array(':uniacid' => $_W['uniacid']));
	if($op == 'status') {
		if(empty($setting)) {
			$open = array(
				'uniacid' => $_W['uniacid'],
				'title' => '我的会员卡',
				'format_type' => 1,
				'fields' => iserializer(array(
					array('title' => '姓名', 'require' => 1, 'bind' => 'realname'),
					array('title' => '手机', 'require' => 1, 'bind' => 'mobile'),
				)),
				'status' => 1,
			);
			pdo_insert('mc_card', $open);
		}
		if (false === pdo_update('mc_card', array('status' => intval($_GPC['status'])), array('uniacid' => $_W['uniacid']))) {
			exit('error');
		}
	} elseif($op == 'other') {
		if(empty($setting)) {
			exit('还没有开启会员卡,请先开启会员卡');
		}
		$field = trim($_GPC['field']);
		if(!in_array($field, array('recommend_status', 'sign_status'))) {
			exit('非法操作');
		}
		pdo_update('mc_card', array($field => intval($_GPC['status'])), array('uniacid' => $_W['uniacid']));
	}
	exit('success');
}

if($do == 'editor') {
	uni_user_permission_check('mc_card_editor');
	if (!empty($_GPC['wapeditor'])) {
		$params = $_GPC['wapeditor']['params'];
		if (empty($params)) {
			message('请您先设计手机端页面.', '', 'error');
		}
		$params = json_decode(ihtml_entity_decode($params), true);
		if (empty($params)) {
			message('请您先设计手机端页面.', '', 'error');
		}
		if (!empty($params)) {
			foreach ($params as $key => &$value) {
				$params_new[$value['id']] = $value;
				if ($value['id'] == 'cardRecharge') {
					$recharges_key = $key;
				}
				if ($value['id'] == 'cardBasic') {
					$value['params']['description'] = str_replace(array("\r\n", "\n"), '<br/>', $value['params']['description']);
					$value['originParams']['description'] = str_replace(array("\r\n", "\n"), '<br/>', $value['originParams']['description']);
				}
			}
		}
		if (!empty($params[$recharges_key])) {
			foreach ($params[$recharges_key]['params']['recharges'] as &$row) {
				if ($row['backtype'] == '0') {
					$row['backunit'] = '元';
				} else {
					$row['backunit'] = '积分';
				}
			}
		}
		$html = htmlspecialchars_decode($_GPC['wapeditor']['html'], ENT_QUOTES);
		$html = str_replace(array("{\$_W['uniacid']}", "{\$_W['acid']}"), array($_W['uniacid'], $_W['acid']), $html);
		$basic = $params_new['cardBasic']['params'];
		$activity = $params_new['cardActivity']['params'];
		$nums = $params_new['cardNums']['params'];
		$times = $params_new['cardTimes']['params'];
		$recharges = $params_new['cardRecharge']['params'];
		$title = trim($basic['title']) ? trim($basic['title']) : message('名称不能为空');
		$format_type = 1;
		$format = trim($basic['format']);
		if(!empty($basic['fields'])) {
			foreach($basic['fields'] as $field) {
				if(!empty($field['title']) && !empty($field['bind'])) {
					$fields[] = $field;
				}
			}
		}
		if($basic['background']['type'] == 'system') {
			$image = pathinfo($basic['background']['image']);
			$basic['background']['image'] = $image['filename'];
		}
		if (!empty($recharges['recharges'])) {
			foreach ($recharges['recharges'] as $row) {
				if ($recharges['recharge_type'] == 1 && ($row['condition'] <= 0 || $row['back'] <= 0)) {
					message('充值优惠设置数值不能为负数或零', referer(), 'error');
				}
			}
		}
		if ($activity['grant_rate'] < 0) {
			message('付款返积分比率不能为负数', referer(), 'error');
		}
		$update = array(
			'title' => $title,
			'format_type' => $basic['format_type'],
			'format' => $format,
			'color' => iserializer($basic['color']),
			'background' => iserializer(array(
				'background' => $basic['background']['type'],
				'image' => $basic['background']['image'],
			)),
			'logo' => $basic['logo'],
			'description' => trim($basic['description']),
			'grant_rate' => intval($activity['grant_rate']),
			'offset_rate' => intval($basic['offset_rate']),
			'offset_max' => intval($basic['offset_max']),
			'fields' => iserializer($fields),
			'grant' => iserializer(
				array(
					'credit1' => intval($basic['grant']['credit1']),
					'credit2' => intval($basic['grant']['credit2']),
			'coupon' => $basic['grant']['coupon'],
				)
			),
			'discount_type' => intval($activity['discount_type']),
			'nums_status' => intval($nums['nums_status']),
			'nums_text' => trim($nums['nums_text']),
			'times_status' => intval($times['times_status']),
			'times_text' => trim($times['times_text']),
			'params' => json_encode($params),
			'html' => $html
		);
		$grant = iunserializer($update['grant']);
		if ($grant['credit1'] < 0 || $grant['credit2'] < 0) {
			message('领卡赠送积分或余额不能为负数', referer(), 'error');
		}
		if ($update['offset_rate'] < 0 || $update['offset_max'] < 0) {
			message('抵现比率的数值不能为负数或零', referer(), 'error');
		}
		if($update['discount_type'] != 0 && !empty($activity['discounts'])) {
			$update['discount'] = array();
			foreach($activity['discounts'] as $discount) {
				if ($update['discount_type'] == 1) {
					if (!empty($discount['condition_1']) || !empty($discount['discount_1'])) {
						if ($discount['condition_1'] < 0 || $discount['discount_1'] < 0) {
							message('消费优惠设置数值不能为负数', referer(), 'error');
						}
					}
				} else {
					if (!empty($discount['condition_2']) || !empty($discount['discount_2'])) {
						if ($discount['condition_2'] < 0 || $discount['discount_2'] < 0) {
							message('消费优惠设置数值不能为负数', referer(), 'error');
						}
					}
				}
				$groupid = intval($discount['groupid']);
				if($groupid <= 0) continue;
				$update['discount'][$groupid] = array(
					'condition_1' => trim($discount['condition_1']),
					'discount_1' => trim($discount['discount_1']),
					'condition_2' => trim($discount['condition_2']),
					'discount_2' => trim($discount['discount_2']),
				);
			}
			$update['discount'] = iserializer($update['discount']);
		}
		if($update['nums_status'] != 0 && !empty($nums['nums'])) {
			$update['nums'] = array();
			foreach($nums['nums'] as $row) {
				if ($row['num'] <= 0 || $row['recharge'] <= 0) {
					message('充值返次数设置不能为负数或零', referer(), 'error');
				}
				$num = floatval($row['num']);
				$recharge = trim($row['recharge']);
				if($num <= 0 || $recharge <= 0) continue;
				$update['nums'][$recharge] = array(
					'recharge' => $recharge,
					'num' => $num
				);
			}
			$update['nums'] = iserializer($update['nums']);
		}
		if($update['times_status'] != 0 && !empty($times['times'])) {
			$update['times'] = array();
			foreach($times['times'] as $row) {
				if ($row['time'] <= 0 || $row['recharge'] <= 0) {
					message('充值返时长设置不能为负数或零', referer(), 'error');
				}
				$time = intval($row['time']);
				$recharge = trim($row['recharge']);
				if($time <= 0 || $recharge <= 0) continue;
				$update['times'][$recharge] = array(
					'recharge' => $recharge,
					'time' => $time
				);
			}
			$update['times'] = iserializer($update['times']);
		}
		if (!empty($setting)) {
			pdo_update('mc_card', $update, array('uniacid' => $_W['uniacid']));
		} else {
			$update['status'] = '1';
			$update['uniacid'] = $_W['uniacid'];
			pdo_insert('mc_card', $update);
		}
		message('会员卡设置成功！', url('mc/card/editor'), 'success');
	}
	$unisetting = uni_setting_load('creditnames');
	$fields_temp = mc_acccount_fields();
	$fields = array();
	foreach($fields_temp as $key => $val) {
		$fields[$key] = array(
			'title' => $val,
			'bind' => $key
		);
	}
	$params = json_decode($setting['params'], true);
	if (!empty($params)) {
		foreach ($params as $key => &$value) {
			if ($value['id'] == 'cardBasic') {
				$value['params']['description'] = str_replace("<br/>", "\n", $value['params']['description']);
			}
			$card_params[$key] = $value;
			$params_new[$value['id']] = $value;
		}
	}
	$setting['params'] = json_encode($card_params);
	$discounts_params = $params_new['cardActivity']['params']['discounts'];
	$discounts_temp = array();
	if(!empty($discounts_params)) {
		foreach($discounts_params as $row) {
			$discounts_temp[$row['groupid']] = $row;
		}
	}
	$discounts = array();
	foreach($_W['account']['groups'] as $group) {
		$discounts[$group['groupid']] = array(
			'groupid' => $group['groupid'],
			'title' => $group['title'],
			'credit' => $group['credit'],
			'condition_1' => $discounts_temp[$group['groupid']]['condition_1'],
			'discount_1' => $discounts_temp[$group['groupid']]['discount_1'],
			'condition_2' => $discounts_temp[$group['groupid']]['condition_2'],
			'discount_2' => $discounts_temp[$group['groupid']]['discount_2'],
		);
	}
	$setting['params'] = preg_replace('/\n/', '', $setting['params']);
	template('mc/card-editor');
	exit();
}

if ($do == 'manage') {
	uni_user_permission_check('mc_card_manage');
	$cardid = intval($_GPC['cardid']);
	if ($_W['ispost']) {
		$status = array('status' => intval($_GPC['status']));
		if (false === pdo_update('mc_card_members', $status, array('uniacid' => $_W['uniacid'], 'id' => $cardid))) {
			exit('error');
		}
		exit('success');
	}
	if ($setting['status'] == 0) {
		message('会员卡功能未开启', url('mc/card/editor'), 'error');
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	$param = array(':uniacid' => $_W['uniacid']);
	$cardsn = trim($_GPC['cardsn']);
	if(!empty($cardsn)) {
		$where .= ' AND a.cardsn LIKE :cardsn';
		$param[':cardsn'] = "%{$cardsn}%";
	}
	$status = isset($_GPC['status']) ? intval($_GPC['status']) : -1;
	if ($status >= 0) {
		$where .= " AND a.status = :status";
		$param[':status'] = $status;
	}
	$num = isset($_GPC['num']) ? intval($_GPC['num']) : -1;
	if($num >= 0) {
		if(!$num) {
			$where .= " AND a.nums = 0";
		} else {
			$where .= " AND a.nums > 0";
		}
	}
	$endtime = isset($_GPC['endtime']) ? intval($_GPC['endtime']) : -1;
	if($endtime >= 0) {
		$where .= " AND a.endtime <= :endtime";
		$param[':endtime'] = strtotime($endtime . 'days');
	}

	$keyword = trim($_GPC['keyword']);
	if(!empty($keyword)) {
		$where .= " AND (b.mobile LIKE '%{$keyword}%' OR b.realname LIKE '%{$keyword}%')";
	}
	$sql = 'SELECT a.*, b.realname, b.groupid, b.credit1, b.credit2, b.mobile FROM ' . tablename('mc_card_members') . " AS a LEFT JOIN " . tablename('mc_members') . " AS b ON a.uid = b.uid WHERE a.uniacid = :uniacid $where ORDER BY a.id DESC LIMIT ".($pindex - 1) * $psize.','.$psize;
	$list = pdo_fetchall($sql, $param);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_members') . " AS a LEFT JOIN " . tablename('mc_members') . " AS b ON a.uid = b.uid WHERE a.uniacid = :uniacid $where", $param);
	$pager = pagination($total, $pindex, $psize);
	template('mc/card');
}

if ($do == 'delete') {
	$cardid = intval($_GPC['cardid']);
	if (pdo_delete('mc_card_members',array('id' =>$cardid))) {
		message('删除会员卡成功',url('mc/card/manage'),'success');
	} else {
		message('删除会员卡失败',url('mc/card/manage'),'error');
	}
}

if($do == 'coupon') {
	$title = trim($_GPC['keyword']);
	$condition = ' WHERE uniacid = :uniacid AND (amount-dosage>0) AND starttime <= :time AND endtime >= :time';
	$param = array(
		':uniacid' => $_W['uniacid'],
		':time' => TIMESTAMP,
	);
	$data = pdo_fetchall('SELECT * FROM ' . tablename('activity_coupon') . $condition, $param);
	if(empty($data)) {
		exit('empty');
	}
	template('mc/coupon-model');
	exit();
}

if($do == 'modal') {
	$uid = intval($_GPC['uid']);
	$setting = pdo_get('mc_card', array('uniacid' => $_W['uniacid']));
	$card = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $uid));
	if(empty($card)) {
		exit('error');
	}
	template('mc/card-model');
	exit();
}

if($do == 'submit') {
	load()->model('mc');
	$uid = intval($_GPC['uid']);
	$setting = pdo_get('mc_card', array('uniacid' => $_W['uniacid']));
	$card = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $uid));
	if(empty($card)) {
		message('用户会员卡信息不存在', referer(), 'error');
	}
	$type = trim($_GPC['type']);
	if($type == 'nums_plus') {
		$fee = floatval($_GPC['fee']);
		$tag = intval($_GPC['nums']);
		if(!$fee && !$tag) {
			message('请完善充值金额和充值次数', referer(), 'error');
		}
		$total_num = $card['nums'] + $tag;
		pdo_update('mc_card_members', array('nums' => $total_num), array('uniacid' => $_W['uniacid'], 'uid' => $uid));
		$log = array(
			'uniacid' => $_W['uniacid'],
			'uid' => $uid,
			'type' => 'nums',
			'model' => 1,
			'fee' => $fee,
			'tag' => $tag,
			'addtime' => TIMESTAMP,
			'note' => date('Y-m-d H:i') . "充值{$fee}元，管理员手动添加{$tag}次，添加后总次数为{$total_num}次",
			'remark' => trim($_GPC['remark']),
		);
		pdo_insert('mc_card_record', $log);
		mc_notice_nums_plus($card['openid'], $setting['nums_text'], $tag, $total_num);
	}

	if($type == 'nums_times') {
		$tag = intval($_GPC['nums']);
		if(!$tag) {
			message('请填写消费次数', referer(), 'error');
		}
		if($card['nums'] < $tag) {
			message('当前用户的消费次数不够', referer(), 'error');
		}
		$total_num = $card['nums'] - $tag;
		pdo_update('mc_card_members', array('nums' => $total_num), array('uniacid' => $_W['uniacid'], 'uid' => $uid));
		$log = array(
			'uniacid' => $_W['uniacid'],
			'uid' => $uid,
			'type' => 'nums',
			'model' => 2,
			'fee' => 0,
			'tag' => $tag,
			'addtime' => TIMESTAMP,
			'note' => date('Y-m-d H:i') . "消费1次，管理员手动减1次，消费后总次数为{$total_num}次",
			'remark' => trim($_GPC['remark']),
		);
		pdo_insert('mc_card_record', $log);
		mc_notice_nums_times($card['openid'], $card['cardsn'], $setting['nums_text'], $total_num);
	}

	if($type == 'times_plus') {
		$fee = floatval($_GPC['fee']);
		$endtime = strtotime($_GPC['endtime']);
		$days = intval($_GPC['days']);
		if($endtime <= $card['endtime'] && !$days) {
			message('服务到期时间不能小于会员当前的服务到期时间或未填写延长服务天数', '', 'error');
		}
		$tag = floor(($endtime - $card['endtime']) / 86400);
		if($days > 0) {
			$tag = $days;
			if($card['endtime'] > TIMESTAMP) {
				$endtime = $card['endtime'] + $days * 86400;
			} else {
				$endtime = strtotime($days . 'days');
			}
		}
		pdo_update('mc_card_members', array('endtime' => $endtime), array('uniacid' => $_W['uniacid'], 'uid' => $uid));
		$endtime = date('Y-m-d', $endtime);
		$log = array(
			'uniacid' => $_W['uniacid'],
			'uid' => $uid,
			'type' => 'times',
			'model' => 1,
			'fee' => $fee,
			'tag' => $tag,
			'addtime' => TIMESTAMP,
			'note' => date('Y-m-d H:i') . "充值{$fee}元，管理员手动设置{$setting['times_text']}到期时间为{$endtime},设置之前的{$setting['times_text']}到期时间为".date('Y-m-d', $card['endtime']),
			'remark' => trim($_GPC['remark']),
		);
		pdo_insert('mc_card_record', $log);
		mc_notice_times_plus($card['openid'], $card['cardsn'], $setting['times_text'], $fee, $tag, $endtime);
	}

	if($type == 'times_times') {
		$endtime = strtotime($_GPC['endtime']);
		if($endtime > $card['endtime']) {
			message("该会员的{$setting['times_text']}到期时间为：" . date('Y-m-d', $card['endtime']) . ",您当前在进行消费操作，设置到期时间不能超过" . date('Y-m-d', $card['endtime']) , '', 'error');
		}
		$flag = intval($_GPC['flag']);
		if($flag) {
			$endtime = TIMESTAMP;
		}
		$tag = floor(($card['endtime'] - $endtime) / 86400);
		pdo_update('mc_card_members', array('endtime' => $endtime), array('uniacid' => $_W['uniacid'], 'uid' => $uid));
		$endtime = date('Y-m-d', $endtime);
		$log = array(
			'uniacid' => $_W['uniacid'],
			'uid' => $uid,
			'type' => 'times',
			'model' => 2,
			'fee' => 0,
			'tag' => $tag,
			'addtime' => TIMESTAMP,
			'note' => date('Y-m-d H:i') . "管理员手动设置{$setting['times_text']}到期时间为{$endtime},设置之前的{$setting['times_text']}到期时间为".date('Y-m-d', $card['endtime']),
			'remark' => trim($_GPC['remark']),
		);
		pdo_insert('mc_card_record', $log);
		mc_notice_times_times($card['openid'], "您好，您的{$setting['times_text']}到期时间已变更", $setting['times_text'], $endtime);
	}
	message('操作成功', referer(), 'success');
}

if($do == 'record') {
	$uid = intval($_GPC['uid']);
	$card = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $uid));
	$where = ' WHERE uniacid = :uniacid AND uid = :uid';
	$param = array(':uniacid' => $_W['uniacid'], ':uid' => $uid);
	$type = trim($_GPC['type']);
	if(!empty($type)) {
		$where .= ' AND type = :type';
		$param[':type'] = $type;
	}
	if(empty($_GPC['endtime']['start'])) {
		$starttime = strtotime('-30 days');
		$endtime = TIMESTAMP;
	} else {
		$starttime = strtotime($_GPC['endtime']['start']);
		$endtime = strtotime($_GPC['endtime']['end']) + 86399;
	}
	$where .= ' AND addtime >= :starttime AND addtime <= :endtime';
	$param[':starttime'] = $starttime;
	$param[':endtime'] = $endtime;

	$pindex = max(1, intval($_GPC['page']));
	$psize = 30;
	$limit = " ORDER BY id DESC LIMIT " . ($pindex -1) * $psize . ", {$psize}";
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_record') . " {$where}", $param);
	$list = pdo_fetchall('SELECT * FROM ' . tablename('mc_card_record') . " {$where} {$limit}", $param);
	$pager = pagination($total, $pindex, $psize);
	template('mc/card');
}

if($do == 'notice') {
	uni_user_permission_check('mc_card_other');
	$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'list';
	if($op == 'list') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 30;
		$limit = " ORDER BY id DESC LIMIT " . ($pindex -1) * $psize . ", {$psize}";

		$addtime = intval($_GPC['addtime']);
		$where = ' WHERE uniacid = :uniacid AND type = 1';
		$param = array(':uniacid' => $_W['uniacid']);

		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_notices') . " {$where}", $param);
		$notices = pdo_fetchall('SELECT * FROM ' . tablename('mc_card_notices') . " {$where} {$limit}", $param);
		$pager = pagination($total, $pindex, $psize);
	}
	if($op == 'post') {
		$id = intval($_GPC['id']);
		if($id > 0) {
			$notice = pdo_get('mc_card_notices', array('uniacid' => $_W['uniacid'], 'id' => $id));
			if(empty($notice)) {
				message('通知不存在或已被删除', referer(), 'error');
			}
		}
		if(checksubmit()) {
			$title = trim($_GPC['title']) ? trim($_GPC['title']) : message('通知标题不能为空');
			$content = trim($_GPC['content']) ? trim($_GPC['content']) : message('通知内容不能为空');
			$data = array(
				'uniacid' => $_W['uniacid'],
				'type' => 1,
				'uid' => 0,
				'title' => $title,
				'thumb' => trim($_GPC['thumb']),
				'groupid' => intval($_GPC['groupid']),
				'content' => htmlspecialchars_decode($_GPC['content']),
				'addtime' => TIMESTAMP
			);
			if($id > 0) {
				pdo_update('mc_card_notices', $data, array('uniacid' => $_W['uniacid'], 'id' => $id));
			} else {
				pdo_insert('mc_card_notices', $data);
			}
			message('发布通知成功', url('mc/card/notice') , 'success');
		}
	}

	if($op == 'del') {
		$id = intval($_GPC['id']);
		pdo_delete('mc_card_notices', array('uniacid' => $_W['uniacid'], 'id' => $id));
		message('删除成功', referer(), 'success');
	}
	template('mc/card-notice');
}

if ($do == 'sign') {
	uni_user_permission_check('mc_card_other');
	$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'sign-credit';
	if ($op == 'sign-credit') {
		$set = pdo_get('mc_card_credit_set', array('uniacid' => $_W['uniacid']));
		if(empty($set)) {
			$set = array();
		} else {
			$set['sign'] = iunserializer($set['sign']);
		}
		if(checksubmit()) {
			$data = array(
				'uniacid' => $_W['uniacid'],
				'sign' => array(
					'everydaynum' => intval($_GPC['sign']['everydaynum']),
					'first_group_day' => intval($_GPC['sign']['first_group_day']),
					'first_group_num' => intval($_GPC['sign']['first_group_num']),
					'second_group_day' => intval($_GPC['sign']['second_group_day']),
					'second_group_num' => intval($_GPC['sign']['second_group_num']),
					'third_group_day' => intval($_GPC['sign']['third_group_day']),
					'third_group_num' => intval($_GPC['sign']['third_group_num']),
					'full_sign_num' => intval($_GPC['sign']['full_sign_num']),
				),
				'content' => htmlspecialchars_decode($_GPC['content']),
			);
			$data['sign'] = iserializer($data['sign']);
			if(empty($set['uniacid'])) {
				pdo_insert('mc_card_credit_set', $data);
			} else {
				pdo_update('mc_card_credit_set', $data, array('uniacid' => $_W['uniacid']));
			}
			message('积分策略更新成功', referer(), 'success');
		}
	}
	if ($op == 'record-list') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$list = pdo_fetchall("SELECT * FROM ". tablename('mc_card_sign_record'). " WHERE uniacid = :uniacid ORDER BY id DESC LIMIT " . ($pindex - 1)*$psize. ','. $psize, array(':uniacid' => $_W['uniacid']));
		foreach ($list as $key => &$value){
			$value['addtime'] = date('Y-m-d H:i:s', $value['addtime']);
			$value['realname'] = pdo_fetchcolumn("SELECT realname FROM ". tablename('mc_members'). ' WHERE uniacid = :uniacid AND uid = :uid', array(':uniacid' => $_W['uniacid'], ':uid' => $value['uid']));
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ". tablename('mc_card_sign_record'). " WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
		$pager = pagination($total, $pindex, $psize);
	}
	template('mc/card-sign');
}


if($do == 'wechat'){

	$_W['page']['title'] = '微信会员卡 - 会员卡管理 - 会员中心';

	$coupon_title = '微信会员卡';

	$types = array('ajax' , 'manage', 'create', 'delete', 'detail', 'record');
	$type = $_GPC['type'];

	if(!empty($type) && in_array($type, $types)){
		$type = $_GPC['type'];
	}else{
		$type = 'manage';
	}

	if($type == 'manage'){

		$status = empty($_GPC['status']) ? 0 : $_GPC['status'];
		$list = pdo_getall('member_wechat_card', array('uniacid' => $_W['uniacid'], 'status >=' => $status ));

		if(!empty($list)){
			foreach ($list as &$item){
				$item['date_info'] = iunserializer($item['date_info']);
				$item['sku'] = iunserializer($item['sku']);
			}

			unset($item);
		}
	}

	if($type == 'create'){

		if(checksubmit('submit-create')){
			load()->classs('coupon');

			$coupon_api = new coupon();
			$coupon = Card::create(COUPON_TYPE_MEMBER);

			// 会员卡LOGO
			empty($_GPC['logo_url']) && message('会员卡LOGO不可以为空。', '', 'error');
			$coupon->logo_url = urldecode(trim($_GPC['logo_url']));

			// 商户名称
			if(empty($_GPC['brand_name']) || strlen($_GPC['brand_name']) > 36 ){
				message('商户名字不允许为空，且字数上限为12个汉字。', '', 'error');
			}
			$coupon->brand_name = substr(trim($_GPC['brand_name']), 0, 36);

			// 会员卡副标题
			$coupon->title = substr(trim($_GPC['title']), 0, 27);

			// 背景
			if(empty($_GPC['bg_type'])){
				$coupon->color = !empty($_GPC['color']) ? $_GPC['color'] : 'Color082';
			}else{
				$coupon->background_pic_url = trim($_GPC['background_pic_url']);
			}

			// 有效时间 （默认永久有效）
			$coupon->date_info = array('type' => 'DATE_TYPE_PERMANENT');

			// 商品信息
			// 卡券库存的数量，不支持填写0，上限为100000000。
			//$coupon->sku = array( 'quantity' => 0 );
			$coupon->setQuantity(100000000);
			// 获取上限
			$coupon->get_limit = 1;
			// 自定义CODE
			$coupon->use_custom_code = false;
			// 赠送好友
			$coupon->can_give_friend = false;

			// 自定义按钮标题，副标题，跳转URL。
			//$coupon->setCenterMenu('center_title', 'center_sub_title', 'center_url');

			// base_info end
			// 积分设置
			$coupon->setSupplyBonus(intval($_GPC['supplybonus']));

			if(intval($_GPC['supplybonus'])){
				// 消费{cost_money_unit}元得{increase_bonus}分，单笔最多可获得{max_increase_bonus}积分。
				$coupon->setBonusRule('cost_money_unit', max(0, trim($_GPC['cost_money_unit']) * 100) );
				$coupon->setBonusRule('increase_bonus', max(0, trim($_GPC['increase_bonus'])) );

				$coupon->setBonusRule('max_increase_bonus', max(0, trim($_GPC['max_increase_bonus'])) );

				// 起始积分为{init_increase_bonus}.
				$coupon->setBonusRule('init_increase_bonus', trim($_GPC['init_increase_bonus']));

				// 每{cost_bonus_unit}积分，抵扣{reduce_money}元钱。
				$coupon->setBonusRule('cost_bonus_unit', trim($_GPC['cost_bonus_unit']));
				$coupon->setBonusRule('reduce_money',trim($_GPC['reduce_money']) * 100) ;

				// 满{least_money_to_use_bonus}元才可用积分抵扣，单次最多抵扣{max_reduce_bonus}积分。
				$coupon->setBonusRule('least_money_to_use_bonus', trim($_GPC['least_money_to_use_bonus']) * 100);
				$coupon->setBonusRule('max_reduce_bonus', $_GPC['max_reduce_bonus']);
			}

			// 余额储值
			$coupon->setSupplyBalance(intval($_GPC['supplybalance']));

			// 会员卡特权说明。
			$coupon->prerogative = trim($_GPC['prerogative']);

			// 使用说明（须知）
			$coupon->description = trim($_GPC['description']);

			// 使用提醒
			$coupon->notice = trim($_GPC['notice']);
			// 商家号码
			$coupon->service_phone = trim($_GPC['service_phone']);
			// 商家服务类型
			if(!empty($_GPC['business_service'])){
				$coupon->setBusinessService($_GPC['business_service']);
			}

			// 自定义跳转入口
			if(!empty($_GPC['custom']['url_name']) && !empty($_GPC['custom']['url'])){
				$coupon->setCustomMenu($_GPC['custom']['url_name'], $_GPC['custom']['url_sub_title'], $_GPC['custom']['url']);
			}

			// 自定义营销场景
			if(!empty($_GPC['promotion']['url_name']) && !empty($_GPC['promotion']['url'])){
				$coupon->setPromotionMenu($_GPC['promotion']['url_name'], $_GPC['promotion']['url_sub_title'], $_GPC['promotion']['url']);
			}

			// 自定义会员信息类目
			if(!empty($_GPC['custom_cell']['name']) && !empty($_GPC['custom_cell']['url'])){
				$coupon->setCustomCell($_GPC['custom_cell']['name'], $_GPC['custom_cell']['tips'], $_GPC['custom_cell']['url']);
			}

			// 会员可享受折扣（100 - {discount} * 10）。
			if(!empty($_GPC['discount'])){
				$coupon->discount = 100 - trim($_GPC['discount']) * 10 ;
			}

			// 默认走微信一键激活。
			$coupon->auto_activate = false;
			$coupon->wx_activate = true;

			if($_GPC['pay_info'] == 1){
				$coupon->setPayInfo(true);
			}

			$status = $coupon_api->CreateCard($coupon->getCardData());

			if(!is_error($status)){
				$coupon->card_id = $status['card_id'];
				$coupon->status = 1;
				$insertData = $coupon->getCardArray();
				$insertData['uniacid'] = $_W['uniacid'];
				pdo_insert('member_wechat_card', $insertData);

				if(!empty($_GPC['required_form'])){
					$required_form = $_GPC['required_form'];
					$required_form[] = 'USER_FORM_INFO_FLAG_NAME';
					$required_form[] = 'USER_FORM_INFO_FLAG_MOBILE';

					$coupon->setRequiredForm($required_form);
				}

				if(!empty($_GPC['optional_form'])){
					$optional_form = $_GPC['optional_form'];
					$coupon->setOptionalForm($optional_form);
				}

				$form = $coupon->activateUserForm($coupon->card_id);

				$res = $coupon_api->activateUserForm($form);

				if(!is_error($res)){
					message('恭喜您，会员卡创建成功了。', url('mc/card/wechat', array('type' => 'manage')), 'success');
				}else{
					pdo_delete('member_wechat_card', array('card_id' => $coupon->card_id, 'uniacid' => $_W['uniacid']));
					message('很抱歉，会员卡激活字段创建失败了，具体原因：'.var_export($status, true), '', 'error');
				}

			}else{
				message('很抱歉，会员卡创建失败了，具体原因：'.var_export($status, true), '', 'error');
			}

		}
	}

	if($type == 'record'){
		$action = empty($_GPC['action']) ? 'display' : trim($_GPC['action']);

		if($action == 'invalid'){
			$rid = $_GPC['rid'];
			$userCard = pdo_get('wechat_card_record', array('id' => intval($_GPC['rid']), 'uniacid' => $_W['uniacid'], 'status >=' => 0 ));

			if(!empty($userCard)){
				load()->classs('coupon');
				$coupon_api = new coupon();
				$result = $coupon_api->UnavailableCode(array('card_id' => $userCard['card_id'], 'code' => $userCard['code']));

				if(!is_error($result)){
					pdo_update('wechat_card_record', array('status' => -1) , array('id' => intval($_GPC['rid']), 'uniacid' => $_W['uniacid']));
					message('已经将此卡设置为无效卡', '', 'success');
				}else{
					message('出现了错误: '.var_export($result, true), '', 'error');
				}
			}else{
				message('不存在的卡，可能是因为已经被删除过了。', '', 'error');
			}
		}

		$id = intval($_GPC['id']);
		$psize = 10;
		$pindex = max(0, empty($_GPC['page']) ? 1 : intval($_GPC['page']));

		$status = empty($_GPC['status']) ? 0 : $_GPC['status'];

		$card = pdo_get('member_wechat_card', array('id' => $id, 'uniacid' => $_W['uniacid']));

		$recordCount = pdo_getcolumn('wechat_card_record', array('card_id' => $card['card_id'], 'uniacid' => $_W['uniacid'], 'status >=' => $status ), 'id');
		$record = pdo_getslice('wechat_card_record', array('card_id' => $card['card_id'], 'uniacid' => $_W['uniacid'], 'status >=' => $status ), array($pindex, $psize), $recordCount);

		if(!empty($record)){
			foreach ($record as &$row) {
				$fans = mc_fetch($row['openid'], array('nickname', 'avatar', 'credit1', 'credit2'));
				$row['nickname'] = $fans['nickname'];
				$row['avatar'] = $fans['avatar'];
				$row['credit1'] = $fans['credit1'];
				$row['credit2'] = $fans['credit2'];
			}
			unset($row);
		}

		$pager = pagination($recordCount, $pindex, $psize);
	}

	if($type == 'detail'){
		$id = intval($_GPC['id']);

		$coupon = pdo_get('member_wechat_card', array('id' => $id, 'uniacid' => $_W['uniacid']));

		load()->classs('coupon');
		$coupon_api = new coupon();
		$res = $coupon_api->fetchCard($coupon['card_id']);

		$wechatMedia = './index.php?c=utility&a=wxcode&do=image&attach=';

		$coupon['date_info'] = iunserializer($coupon['date_info']);
		$coupon['sku'] = iunserializer($coupon['sku']);
		$coupon['bonus_rule'] = iunserializer($coupon['bonus_rule']);
		$coupon['logo_url'] = $wechatMedia . $coupon['logo_url'];

		if(empty($coupon['background_pic_url'])){
			$coupon['color_value'] = $coupon_api->locationColor($coupon['color']);
			$coupon['bg_css'] = 'background: '. $coupon['color_value'];
		}else{
			$coupon['background_pic_url'] = $wechatMedia . $coupon['background_pic_url'];
			$coupon['bg_css'] = 'background: '. $coupon['background_pic_url'];;
		}

		$coupon['date_info'] = iunserializer($coupon['date_info']);
		if($coupon['date_info']['type'] == 'DATE_TYPE_PERMANENT'){
			$coupon['extra_date_info'] = '永久有效';
		}else if($coupon['date_info']['type'] == 'DATE_TYPE_FIX_TIME_RANGE'){
			$coupon['extra_date_info'] = date('Y-m-d', $coupon['date_info']['begin_timestamp']) .' - '.date('Y-m-d', $coupon['date_info']['end_timestamp']);
		}

		$coupon['custom_cell1'] = iunserializer($coupon['custom_cell1']);
		$coupon['sku'] = iunserializer($coupon['sku']);
		$coupon['quantity'] = $coupon['sku']['quantity'] - $coupon['dosage'];

		template('mc/card-wechat');
		exit();
	}

	if($type == 'delete'){
		$id = intval($_GPC['id']);

		if(!empty($id)){

			$card = pdo_get('member_wechat_card', array('id' => $id, 'uniacid' => $_W['uniacid'], 'status >=' => 1 ));

			if(!empty($card)){
				pdo_update('member_wechat_card', array('status' => -1), array('id' => $id, 'uniacid' => $_W['uniacid']));

				load()->classs('coupon');
				$coupon_api = new coupon();
				$coupon_api->DeleteCard($card['card_id']);

				message('删除会员卡成功，注意：已领取过的会员卡，无法被删除。所有领取该卡的途径都将失效（会员卡，JSSDK）。', url('mc/card/wechat', array('type' => 'manage')), 'success');
			}else{
				message('会员卡不存在，可能是已被删除，请刷新后重新尝试', '', 'error');
			}
		}
	}

	if($type == 'ajax'){
		$result = array();

		if($_GPC['action'] == 'qrcode'){
			load()->classs('coupon');
			$coupon_api = new coupon();

			$cid = intval($_GPC['cid']);
			$card = pdo_get('member_wechat_card', array('id' => $cid));

			if(empty($card)) {
				message('会员卡不存在或已经删除', '', 'error', 'ajax');
			}

			$qrcode_sceneid = sprintf('12%012d', $cid);
			$card_qrcode = pdo_get('qrcode', array('qrcid' => $qrcode_sceneid, 'type' => 'card'));
			if (empty($card_qrcode)) {
				$insert = array(
					'uniacid' => $_W['uniacid'],
					'acid' => $_W['acid'],
					'qrcid' => $qrcode_sceneid,
					'keyword' => '',
					'name' => $card['title'],
					'model' => 1,
					'ticket' => '',
					'expire' => '',
					'url' => '',
					'createtime' => TIMESTAMP,
					'status' => '1',
					'type' => 'card',
				);
				pdo_insert('qrcode', $insert);
				$card_qrcode['id'] = pdo_insertid();
			}
			$response = ihttp_request($card_qrcode['url']);
			if ($response['code'] != '200' || empty($coupon_qrcode['url'])) {
				$card_qrcode_image = $coupon_api->QrCard($card['card_id'], $qrcode_sceneid);
				if (is_error($card_qrcode_image)) {
					if ($card_qrcode_image['errno'] == '40078') {
						pdo_update('coupon', array('status' => 2), array('id' => $cid));
					}
					message(error('1', '生成二维码失败，' . $card_qrcode_image['message']), '', 'ajax');
				}
				$cid = $card_qrcode['id'];
				unset($card_qrcode['id']);

				$card_qrcode['url'] = $card_qrcode_image['show_qrcode_url'];
				$card_qrcode['ticket'] = $card_qrcode_image['ticket'];
				$card_qrcode['expire'] = TIMESTAMP + $card_qrcode_image['expire_seconds'];
				pdo_update('qrcode', $card_qrcode, array('id' => $cid));
			}

			$card_qrcode['expire'] = date('Y-m-d H:i:s', $card_qrcode['expire']);
			$qrcode_list = pdo_getslice('qrcode_stat', array('qrcid' => $qrcode_sceneid), 10, $total, array('openid', 'createtime'));
			if (!empty($qrcode_list)) {
				$openids = array();
				foreach ($qrcode_list as &$row) {
					$fans = mc_fansinfo($row['openid']);
					$row['nickname'] = $fans['nickname'];
					$row['avatar'] = $fans['avatar'];
					$row['createtime'] = date('Y-m-d H:i:s', $row['createtime']);
				}
				unset($row);
			}
			message(error('0', array('coupon' => $card_qrcode, 'record' => $qrcode_list, 'total' => $total)), '', 'ajax');
		}

		if($_GPC['action'] == 'whitelist'){
			load()->classs('coupon');
			$coupon_api = new coupon();

			$openids = explode("\n", $_GPC['openids']);
			$result = $coupon_api->SetTestWhiteList(array('openid' => $openids));

			if(!is_error($result)){
				message(error(0), '', 'ajax');
			}else{
				message(error($result['errcode'], $result['errmsg']), '', 'ajax');
			}
		}

		if($_GPC['action'] == 'userinfo'){
			$rid = $_GPC['rid'];
			$result = pdo_get('wechat_card_record', array('id' => $rid, 'uniacid' => $_W['uniacid']));

			if(!empty($result)){
				$result['extra'] = iunserializer($result['extra']);

				$infoTemp = $result['extra']['common_field_list'];

				$info = array();
				foreach($infoTemp as $item){
					switch($item['name']){
						case 'USER_FORM_INFO_FLAG_MOBILE' :
							$info[] = array('name' => '手机号', 'value' => $item['value']);
							break;
						case 'USER_FORM_INFO_FLAG_NAME' :
							$info[] = array('name' => '真实姓名', 'value' => $item['value']);
							break;
						case 'USER_FORM_INFO_FLAG_BIRTHDAY' :
							$info[] = array('name' => '生日', 'value' => $item['value']);
							break;
						case 'USER_FORM_INFO_FLAG_IDCARD' :
							$info[] = array('name' => '身份证', 'value' => $item['value']);
							break;
						case 'USER_FORM_INFO_FLAG_EMAIL' :
							$info[] = array('name' => '邮箱', 'value' => $item['value']);
							break;
						case 'USER_FORM_INFO_FLAG_LOCATION' :
							$info[] = array('name' => '详细地址', 'value' => $item['value']);
							break;
						case 'USER_FORM_INFO_FLAG_EDUCATION_BACKGRO' :
							$info[] = array('name' => '教育背景', 'value' => $item['value']);
							break;
						case 'USER_FORM_INFO_FLAG_CAREER' :
							$info[] = array('name' => '职业', 'value' => $item['value']);
							break;
						case 'USER_FORM_INFO_FLAG_INDUSTRY' :
							$info[] = array('name' => '行业', 'value' => $item['value']);
							break;
						case 'USER_FORM_INFO_FLAG_INCOME' :
							$info[] = array('name' => '收入', 'value' => $item['value']);
							break;
						case 'USER_FORM_INFO_FLAG_HABIT' :
							$info[] = array('name' => '兴趣爱好', 'value' => $item['value']);
							break;
					}
				}

				$info[] = array(
					'name' => '性别',
					'value' => $result['sex'] == 1 ? '男' : '女',
				);

				$info[] = array(
					'name' => '来源',
					'value' => $result['remark'],
				);


				message(error(0, array('info' => $info)), '', 'ajax');
			}else{
				message(error(1, '未查询到对应用户'), '', 'ajax');
			}
		}

	}

	template('mc/card-wechat');
}

if($do == 'other') {
	uni_user_permission_check('mc_card_other');
	template('mc/card-other');
}