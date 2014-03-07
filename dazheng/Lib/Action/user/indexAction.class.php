<?php
/**
 *    #Case		bwvip.com
 *    #Page		indexAction.class.php (首页)
 *
 *    @author		Jack
 *    @e-mail		zhanglong@bwvip.com
 *    @copyright	www.bwvip.com
 */
class indexAction extends user_publicAction
{
	public function _intialize()
	{
		parent::_intialize();
	}
	
		
	public function index()
	{

		$this->assign('site_title','用户中心');

		//当前位置
		$this->assign('index','index');
		
		$sort=" event_sort desc,event_addtime desc ";
		$event_list = M("event")->order($sort)->limit(2)->select();
		$this->assign("list",$event_list);
		
		$user_info = get_user_info($_SESSION['user_id']);
		
		$this->assign("user_info",$user_info);

		$sys_message_list = M()->query('select message_id,message_title,message_addtime from tbl_sys_message where receiver_type = 4 order by message_addtime desc limit 5');

		$this->assign("sys_message_list",$sys_message_list);
		
		$this->display();
		
	}



	//修改资料
	public function user_eidt()
	{
		
		$this->assign('site_title','修改资料');
		//当前位置
		$this->assign('index','user_eidt');

		$user_info = get_user_info($_SESSION['user_id']);

		$this->assign("user_info",$user_info);

		$this->display();
	}


	public function user_eidt_action()
	{	
		$data['realname'] = post('realname');
		$data['gender'] = post('sex');
		$data['mobile'] = post('mobile');
		$data['idcard'] = post('idcard');
		$data['email'] = post('email');

		$where['uid'] = $_SESSION['user_id'];

		$profile = M('common_member_profile','pre_');
	
		$return = $profile->where($where)->data($data)->save();

		if($return)
		{
			$this->success("修改成功",U('user/index/user_eidt'));
		}
		else
		{
			$this->error("修改失败");
		}
		
		
	}

}
?>