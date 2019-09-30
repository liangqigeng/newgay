<?php

namespace Home\Controller;
use Think\Controller;
/**
 * 共用控制器
 */

Class CommonController extends Controller {

	/**
	 * 自动运行的方法
	 */
	Public function _initialize () {

		//处理自动登录
		if (isset($_COOKIE['auto']) && !isset($_SESSION['uid'])){
			$value = explode('|',encrytion($_COOKIE['auto'],1));
			$ip = get_client_ip();

			//本次登录IP与上一次登录IP一致时
			if ($ip == $value[1]) {
				$account = $value[0];
				$where = array('account' => $account);

				$user = M('user')->where($where)->field(array('id','lock'))->find();
				
				//检索出用户信息并且该用户没有被锁定时，保存登录状态
				if ($user && !$user['lock']) {
					session('uid',$user['id']);
				}
			}
		}

		//判断用户是否已经登录
		if (!isset($_SESSION['uid'])) {
			redirect(U('Login/index'));
		}
	}

	/**
	 * 头像上传
	 */
	Public function uploadFace () {
		if(!IS_POST) {
			E('页面不存在');
		}
		$upload = $this->_upload('Face', '180,80,50', '180,80,50');
		echo json_encode($upload);
	}

	/**
	 * 微博图片上传
	 */
	 	Public function uploadPic () {
	 		if (!IS_POST) {
	 			E('页面不存在');
	 		}
	 		$upload = $this->_upload('Pic', '800,380,120', '800,380,120');
	 		echo json_encode($upload);
	}

	/**
	 * 异步创建新分组
	 */
	Public function addGroup () {
		if (!IS_AJAX) {
			E ('页面不存在');
		}
		$data = array (
			'name' => I('post.name'),
			'uid' => session('uid')
		);
		if (M('group')->data($data)->add()) {
			echo json_encode(array('status' =>1, 'msg' => '创建成功'));
		} else {
			echo json_encode(array('status' =>0, 'msg' => '创建失败，请重试'));
		}
	}

	/**
	 * 异步添加关注
	 */
	Public function addFollow () {
		if (!IS_AJAX) {
			E('页面不存在');
		}
		$data = array (
			'follow' => intval (I('post.follow')),
			'fans' => (int) session('uid'),
			'gid' => intval(I('gid'))

		);
			// p($data);die;
			if (M('follow')->data($data)->add()) {
				$db =M ('userinfo');
				$db->where(array('uid' => $data['follow']))->setInc('fans');
				$db->where(array('uid' => session('uid')))->setInc('follow');
				$db->where(array('status' =>1, 'msg' => '关注成功')) ;
				echo json_encode(array('status' => 1, 'msg' => '关注成功'));
			} else {
				echo json_encode(array('status' => 0, 'msg' => '关注失败请重试...'));
			}
		}

	/**
	 * 处理图片上传
	 * @param [String] $path [保存文件夹名称]
	 * @param [String] $width [缩略图宽度多个用，号分隔]
	 * @param [String] $height [缩略图高度多个用，号分隔（要与宽度一一对应）]
	 * @param [Array]  			[图片上传信息]
	 */
	Private function _upload ($path,$width,$height) {
		$obj = new \Think\UploadFile();// 实例化文件上传类
		$obj->maxSize = C('UPLOAD_MAX_SIZE'); // 图片最大上传大小
		$obj->savePath = C('UPLOAD_PATH') .$path. '/'; //文件保存路径
		$obj->saveRule = 'uniqid'; //保存文件名
		$obj->uploadReplace = true; //覆盖同名文件
		$obj->allowExts = C('UPLOAD_EXTS'); //允许上传文件的后缀名
		$obj->thumb = true; //生成缩略图
		$obj->thumbMaxWidth = $width; //缩略图宽度
		$obj->thumbMaxHeight = $height; //缩略图高度
		$obj->thumbPrefix = 'max_,medium_,mini_'; //缩略图前缀名
		$obj->thumbPath = $obj->savePath .date('Y-m'). '/'; //缩略图保存路径
		$obj->thumbRemoveOrigin = true; //删除原图
		$obj->autoSub = true; //使用子目录保存文件
		$obj->subType ='date'; //使用日期为子目录名称
		$obj->dateFormat = 'Y-m'; //使用 年_月 形式

		if(!$obj->upload()) {
			return array ('status' => 0, 'msg' => $obj->getErrorMsg());
		} else {
			$info = $obj->getUploadFileInfo();
			$pic = explode('/', $info[0]['savename']);
			return array(
				'status' => 1,
				'path' => array(
					'max' => $pic[0] . '/max_'. $pic[1],
					'medium' => $pic[0].'/medium_'.$pic[1],
					'mini' => $pic[0].'/mini_'.$pic[1],
				)
			);
		}
	}
}
?>