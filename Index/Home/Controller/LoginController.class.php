<?php

namespace Home\Controller;
use Think\Controller;
/**
 * 注册与登录控制器
 */
Class LoginController extends Controller {

	/**
	 * 登录页面
	 */
	Public function index () {
		$this->display();
	}

	/**
	 * 注册页面
	 */
	Public function register () {
		$this->display();
	}

	/**
	 * 登录页面
	 */
	Public function login () {
		if(!IS_POST){
			E('页面不存在');
		}

		//提取表单内容
		$account = I('post.account');
		$pwd = md5(I('post.pwd'));
		$where = array('account' => $account);
		$user = M('user')->where($where)->find();
		if (!$user || $user['password']!=$pwd) {
			$this->error('用户名或密码不正确');
		}
		if($user['lock']) {
			$this->error('用户被锁定');
		}
		
		//处理下一次自动登录
		if (isset($_POST['auto'])) {
			$account = $user['account'];
			$ip = get_client_ip();
			$value = $account . '|'. $ip;
			$value = encrytion($value);
			@setcookie('auto',$value, C('AUTO_LOGIN_TIME'),'/');
			// // 测试加密方法
			// $value = 'houddunwang';
			// $value = encrytion($value);
			// echo $value;
			// echo '<br/>';
			// $value = encrytion($value, 1);
		}
		//登录成功写入SESSION并且跳转到首页
		session('uid', $user['id']);

		redirect(__APP__,3,'登录成功，正在为您跳转...');
	}

	/**
	 * 注册表单处理
	 */
	Public function runRegis () {
		if (!IS_POST) {
			E ('页面不存在');
		}
		$code = I('post.verify');
		$verify = new \Think\Verify();
		if(!$verify -> check($code,1)) {
			$this->error('验证码错误');
		}
		if($_POST['pwd'] != $_POST['pwded']) {
			$this->error('两次密码不一致');
		}
		// 提取POST数据
		$data = array (
			'account' => I('post.account'),
			'password' => md5(I('post.pwd')),
			'registime' => $_SERVER['REQUEST_TIME'],
			'userinfo' => array(
				'username' => I('post.uname')
			)

		);
		// p($data);die;
		$id = D('UserRelation')->insert($data);
		// new UserRelation();
		 // $db = D('UserRelation');
		 // $db ->relation(true)->data($data)->add();
		 if ($id) {
		 	//插入数据成功后把用户ID写SESSION
		 	session ('uid', $id);

		 	//跳转至首页
		 	header('Content-Type:text/html;Charset=utf-8');
		 	redirect(__APP__,3,'注册成功，正在为您跳转...');
		 } else {
		 	$this->error('注册失败，请重试...');
		 }
	}

	/**
	 * 生成验证码
	 */
	Public function verify () {
		$config = array (
			'length' => 4,
		);
		$Verify = new \Think\Verify ($config);
		$Verify -> entry (1);
	}

	/**
	 * 异步检验账号是否存在
	 */
	Public function checkAccount () {
		if(!IS_AJAX) {
			E('页面不存在');
		}
		$account = I('post.account');
		//这两句上下等价
		// $where = array('account' => $account);
		$where = " account = '$account'";
		if (M('user')->where($where)->getField('id')) {
			echo 'false';
		} else {
			echo 'true';
		}
	}

	/**
	 * 异步检验昵称是否存在
	 */
	Public function checkUname () {
		if(!IS_AJAX) {
			E('页面不存在');
		}
		$username = I('post.uname');
		$where = " username = '$username'";
		if(M('userinfo')->where($where)->getField('id')) {
			echo 'false';
		} else {
			echo 'true';
		}

	}
	
	/**
	 * 异步检验验证码是否正确
	 */
	Public function checkVerify () {
		if(!IS_AJAX) {
			E('页面不存在');
		}
		$code = I('post.verify');
		$verify = new \Think\Verify();
		$verify -> reset = false;
		if($verify -> check($code,1)) {
			echo 'true';
		} else {
			echo 'false';
		}
	}
}	
?>