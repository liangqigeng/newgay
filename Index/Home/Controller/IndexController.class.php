<?php

namespace Home\Controller;
use Think\Controller;
/**
 * 首页控制器
 */
Class IndexController extends CommonController {

	/**
	 * 首页视图
	 */
	Public function index () {
		// $content = '百度网网址:http://www.baidu.com @admin22 hgj[挖鼻屎] gkhlj[害羞]后盾网 @我丢你 [呵呵]geh';
		// echo replace_weibo($content);die;
		// echo time();
		// $time = 1568601362;
		// echo time_format($time);

		//实例化视图模型
		$db = D('WeiboView');

		//取得当前用户的ID与当前用户所有关注好友的ID
		$uid = array(session('uid'));
		$where = array('fans' => session('uid'));
		$result = M('follow')->field('follow')->where($where)->select();
		if ($result) {
			foreach ($result as $v) {
				$uid[] = $v['follow'];
			}
		}

		//组合WHERE条件，条件为当前用户自身的ID与当前用户所有关注好友的ID
		$where = array('uid' => array('IN',$uid));
		//读取所有微博
		$result = $db->getAll($where);
		// p($where);
		$this->weibo = $result;
		$this->display();
	}

	/**
	 * 微博发布处理
	 */
	PUBLIC function sendWeibo () {
		if (!IS_POST) {
			E('页面不存在');
		}
		$data = array(
			'content' => I('post.content'),
			'time' => time(),
			'uid' => session('uid')
		);
		if ($wid = M('weibo')->data($data)->add()) {
			if (!empty($_POST['max'])) {
				$img = array(
					'mini' => I('post.mini'),
					'medium' => I('post.medium'),
					'max' => I('post.max'),
					'wid' => $wid
				);
				M('picture')->data($img)->add();
			}

			$this->success('发布成功', U('index'));
		} else {
			$this->error('发布失败请重试...');
		}
	}

	/**
	 * 退出登录处理
	 */
	Public function loginOut () {
		//卸载SESSION
		session_unset();
		session_destroy();

		//删除用于自动登录的COOKIE
		@setcookie('auto','',time() -3600, '/');

		//跳转至登录页
		redirect(U('Login/index'));
	}
}
?>