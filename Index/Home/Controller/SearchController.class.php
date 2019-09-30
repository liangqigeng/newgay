<?php

namespace Home\Controller;
use Think\Controller;
/**
 * 搜索控制器
 */
Class SearchController extends CommonController {

	/**
	 * 搜索找人
	 */
	Public function sechUser () {
		$keyword = $this->_getKeyword();

		if (keyword) {
			//检索出除自己外昵称含有关键字的用户
			$where = array(
				'username' => array('LIKE', '%'.$keyword.'%'),
				'uid' => array('NEQ', session('uid'))
			);
			$field = array('username', 'sex', 'location', 'intro', 'face80', 'follow', 'fans', 'weibo', 'uid');
			$db = M('userinfo');

			//导入分页类
		$count = $db->where($where)->count('id'); // 查询满足要求的总记录数
		$page = new \Think\Page($count,3); // 实例化分页类 传入总记录数和每页显示的记录数(25)
		// $show = $page->show(); // 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$limit = $page->firstRow . ',' . $page->listRows;
		$result = $db->where($where)->field($field)->limit($limit)->select();
		// $this->assign('result',$result); // 赋值数据集
		// $this->assign('page',$show); // 赋值分页输出
		$result = $db->where($where)->field($field)->select();

		//重新组合结果集，得到是否已关注与是否互相关注
		$result = $this->_getMutual($result);

		//分量搜索结果得到视图
		$this->result = $result ? $result : false;
		
		//页码
		$this->page = $page->show();
	}

		$this->keyword = $keyword;
		$this->display();
	}

	/**
	 * 返回搜索关键字
	 */
	Private function _getKeyword () {
		return $_GET['keyword'] == '搜索微博、找人' ?NULL : I('get.keyword');
	}

	/**
	 * 重组结果集得到是否互相关注与是否已关注
	 * @param  [Array] $result [需要处理的结果集]
	 * @return [Array]         [处理完成后的结果集]
	 */
	Private function _getMutual ($result) {
		if (!$result) return false;

		$db = M('follow');

		foreach ($result as $k => $v) {
			//是否互相关注
			$sql = '(SELECT `follow` FROM `hd_follow` WHERE `follow` = '. $v ['uid'].' AND `fans` = '. session('uid') .') UNION (SELECT `follow` FROM `hd_follow` WHERE `follow` = ' . session('uid').' AND `fans` ='. $v ['uid'] .')';

			$mutual = $db->query($sql);
		
		if (count($mutual) == 2) {
			$result[$k]['mutual'] = 1;
			$result[$k]['followed'] = 1;
		} else {
			$result[$k]['mutual'] = 0;

			//未互相关注检索是否已关注
			$where = array (
				'follow' => $v['uid'],
				'fans' => session('uid')
			);
			$result[$k]['followed'] = $db->where($where)->count();
			}
		}
		return $result;
	}
}
?>