<?php
/**
 *    #Case		bwvip
 *    #Page		field_publicAction.class.php (门票全局)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-05-28
 */
class piao_publicAction extends Action
{
	public function _initialize()
	{	
		
	
		if(!isset($_SESSION['uid']) || !$_SESSION['uid'])
		{
			$this->error("请登录",U('piao/public/login'));
		}
		
		if(get("event_id"))
		{
			$event_info=M("event")->where("event_id=".intval(get("event_id")))->find();
			$this->assign('event_name',$event_info['event_name']);
			$this->assign('event_id',$event_info['event_id']);
		}
	
	}



}
?>