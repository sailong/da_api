<?php
/**
 *    #Case		bwvip
 *    #Page		publicAction.class.php (未登录页面)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-05-28
 */
class publicAction extends Action
{
	public function _initialize()
	{
		/*
		$site=M("site")->where(" site_id=1")->find();
		$this->assign("site",$site);
		*/
	}

	public function login()
	{

		$this->assign("title","登录");
		$this->display();
	}

	public function login_action()
	{

		if(post("username") && post("password") && post("event_id"))
		{
			
			$res=M()->query("select admin_id,event_id,admin_name,admin_password,admin_role_id from tbl_piao_admin where event_id='".post("event_id")."' and admin_name='".post("username")."' ");
			//print_r($res);
			if($res[0]['admin_password']==md5(post("password")))
			{
				$field_info=M()->query("select event_name from tbl_event where event_id='".$res[0]['event_id']."' ");
				
				$_SESSION['piao_admin_id']=$res[0]['admin_id'];
				$_SESSION['piao_admin_role_id']=$res[0]['admin_role_id'];
				$_SESSION['uid']=$res[0]['event_id'];
				$_SESSION['event_id']=$res[0]['event_id'];
				$_SESSION['event_name']=$field_info[0]['event_name'];
				$_SESSION['realname']=$res[0]['admin_realname'];
				$_SESSION['username']=$res[0]['admin_name'];
				$_SESSION['email']=$res[0]['admin_email'];
				
				$this->success("登录成功",U('piao/index/index'));
				
			}
			else
			{
				$this->error("用户名或密码错误，请重试",U('piao/public/login',array('event_id'=>post('event_id'))));
			}
		
		}
		else
		{
			$this->error("必须输入 用户名密码",U('piao/public/login',array('event_id'=>post('event_id'))));
		}
	

		
	}


	
	public function logout()
	{
		if(isset($_SESSION['field_admin_id']))
		{
			$event_id=$_SESSION['event_id'];
			
			unset($_SESSION['field_admin_id']);
			unset($_SESSION['uid']);
			unset($_SESSION['event_id']);
			unset($_SESSION['realname']);
			unset($_SESSION['username']);			
			unset($_SESSION['email']);

			$this->success("退出成功",U('piao/public/login',array('event_id'=>$event_id)));
		}
		else
		{
			$this->error("您已经退出",U('piao/public/login'));
		}
	}
	
	
	
	public function verify() {
        $type	 =	 isset($_GET['type'])?$_GET['type']:'gif';
        import("@.ORG.String");
        import("@.ORG.Image");
        Image::buildImageVerify(4,1,$type,60,27);
    }



}
?>