<?php
/**
 *    #Case		bwvip
 *    #Page		field_publicAction.class.php (门票全局)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-05-28
 */
class shop_publicAction extends Action
{
	public function _initialize()
	{	
		if(!isset($_SESSION['uid']) || !isset($_SESSION['field_uid']))
		{
			$this->error("请登录",U('shop/public/login'));
		}
		
		if(get("field_uid"))
		{
			$event_info=M("field")->where("field_uid=".intval(get("field_uid")))->find();
			$this->assign('field_name',$event_info['field_name']);
			$this->assign('field_uid',$event_info['field_uid']);
		}
	
	}



}
?>