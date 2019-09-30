<?php
/**
 * 微博视图模型
 */
namespace Home\Model;
use Think\Model\ViewModel;

Class WeiboViewModel extends ViewModel {

	Protected $viewFields = array(
		'weibo' => array(
		'id', 'content','isturn','time','turn','keep','comment','uid',
			'_type' => 'LEFT'
			),
		'userinfo' => array(
			'username','face50' => 'face',
			'_on' => 'weibo.uid = userinfo.uid',
			'_type' =>'LEFT'
		),
		'picture' => array(
			'mini','medium','max',
			'_on' => 'weibo.id = picture.wid'
		)
	);

	/**
	 * 返回查询所有记录
	 * @param  [type] $where [description]
	 * @return [type] 		 [description]
	 */
	Public function getAll ($where) {
		return $this->where($where)->select();
	}
}
?>