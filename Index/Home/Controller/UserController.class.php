<?php
/**
 * 用户个人页控制器
 */
namespace Home\Controller;
Use Think\Controller;

Class UserController extends Controller {

	/**
	 * 用户个人页视图
	 */
	Public function index () {
		$id = intval(I('get.id'));
		echo $id;
	}

	/**
	 * 空操作
	 */
	Public function _empty ($name) {
		$this->_getUrl($name);
	}

	/**
	 * 处理用户名空操作，获得用户ID 跳转至个人页
	 */
	Private function _getUrl ($name) {
		$name = htmlspecialchars ($name);
		$where = array('username' => $name);
		$uid = M('userinfo')->where($where)->getField('uid');
		if (!$uid) {
			redirect(U('Index/index'));
		} else {
			redirect(U('/'.$uid));
		}
	}
}
?>