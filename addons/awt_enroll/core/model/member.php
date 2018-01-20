<?php
/**
 * File  member.php
 * Created by https://github.com/junxxx
 * Email: hprjunxxx@gmail.com
 * Author: junxxx
 * Datetime: 2017/8/15 22:27
 */
if (!defined('IN_IA')) {
	die('Access Denied');
}
class AWT_Tenroll_Member
{

	public function getCredit($openid = '', $credittype = 'credit1'){
		global $_W;
		load()->model('mc');
		$uid = mc_openid2uid($openid);
		if (!empty( $uid )) {
			return pdo_fetchcolumn("SELECT {$credittype} FROM " . tablename('mc_members') . " WHERE `uid` = :uid", array( ':uid' => $uid ));
		} else {
			return pdo_fetchcolumn("SELECT {$credittype} FROM " . tablename('enroll_member') . " WHERE  openid=:openid and uniacid=:uniacid limit 1", array( ':uniacid' => $_W['uniacid'], ':openid' => $openid ));
		}
	}

	public function getMember($openid = '', $getCredit = false){
		global $_W;
		$uid = intval($openid);
		if (empty( $uid )) {
			$info = pdo_fetch('select * from ' . tablename('enroll_member') . ' where  openid=:openid and uniacid=:uniacid limit 1', array( ':uniacid' => $_W['uniacid'], ':openid' => $openid ));
		} else {
			$info = pdo_fetch('select * from ' . tablename('enroll_member') . ' where id=:id and uniacid=:uniacid limit 1', array( ':uniacid' => $_W['uniacid'], ':id' => $uid ));
		}
		if ($getCredit) {
			$info['credit1'] = $this->getCredit($openid, 'credit1');
			$info['credit2'] = $this->getCredit($openid, 'credit2');
		}
		return $info;
	}

	public function checkMember($openid = ''){
		global $_W, $_GPC;
		if (strexists($_SERVER['REQUEST_URI'], '/web/')) {
			return;
		}
		if (empty( $openid )) {
			$openid = m('user')->getOpenid();
		}
		if (empty( $openid )) {
			return;
		}
		$member = m('member')->getMember($openid);
		$userinfo = m('user')->getInfo();
		$followed = m('user')->followed($openid);

		$uid = 0;
		$mc = array();
		load()->model('mc');
		//已关注公众号
		if ($followed) {
			$uid = mc_openid2uid($openid);
			$mc = mc_fetch($uid, array( 'realname', 'mobile', 'avatar', 'resideprovince', 'residecity', 'residedist' ));
		}
//        else{
//            exit('请关注公众号！');
//        }

        //新用户
		if (empty( $member )) {
			$member = array( 'uniacid' => $_W['uniacid'],
				'uid' => $uid, 'openid' => $openid,
				'realname' => !empty( $mc['realname'] ) ? $mc['realname'] : '',
				'mobile' => !empty( $mc['mobile'] ) ? $mc['mobile'] : '',
				'nickname' => !empty( $mc['nickname'] ) ? $mc['nickname'] : $userinfo['nickname'],
				'avatar' => !empty( $mc['avatar'] ) ? $mc['avatar'] : $userinfo['avatar'],
				'gender' => !empty( $mc['gender'] ) ? $mc['gender'] : $userinfo['sex'],
				'province' => !empty( $mc['residecity'] ) ? $mc['resideprovince'] : $userinfo['province'],
				'city' => !empty( $mc['residecity'] ) ? $mc['residecity'] : $userinfo['city'],
				'area' => !empty( $mc['residedist'] ) ? $mc['residedist'] : '',
				'createtime' => time(),
				'status' => 0 );
			pdo_insert('enroll_member', $member);
		} else {
			if (!empty( $uid )) {
				$upgrade = array();
				if ($userinfo['nickname'] != $member['nickname']) {
					$upgrade['nickname'] = $userinfo['nickname'];
				}
				if ($userinfo['avatar'] != $member['avatar']) {
					$upgrade['avatar'] = $userinfo['avatar'];
				}
				if (empty( $member['uid'] )) {
					$upgrade['uid'] = $uid;
				}
				if ($member['credit1'] > 0) {
					mc_credit_update($uid, 'credit1', $member['credit1']);
					$upgrade['credit1'] = 0;
				}
				if ($member['credit2'] > 0) {
					mc_credit_update($uid, 'credit2', $member['credit2']);
					$upgrade['credit2'] = 0;
				}

				if (!empty( $upgrade )) {
					pdo_update('enroll_member', $upgrade, array( 'id' => $member['id'] ));
				}
			}
		}
	}

    /**
     * @param $userid
     * @param array $arr_new_data
     * Function: updateMember
     * Date: 2018/1/20 0:17
     * Author: peRFect
     * @return bool
     * Decripstion:更新用户信息，将申请会员成功的用户的俱乐部id存储到awt_enroll_member中
     * 添加此函数后，服务器返回500
     */
//	public function updateMember($userid, $arr_new_data = array()){
//	    if(!empty($userid) && !empty($arr_new_data)){
//            return !empty(pdo_update('enroll_member', $arr_new_data, array('id'=>intval($userid))));
//        }else{
//	        return false;
//        }
//
//    }
}