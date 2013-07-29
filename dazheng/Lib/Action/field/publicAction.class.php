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

		if(post("username") && post("password") && post("field_uid"))
		{
			
			$res=M()->query("select admin_id,field_uid,admin_name,admin_password from tbl_field_admin where field_uid='".post("field_uid")."' and admin_name='".post("username")."' ");
			//print_r($res);
			if($res[0]['admin_password']==md5(post("password")))
			{
				$_SESSION['field_admin_id']=$res[0]['admin_id'];
				$_SESSION['uid']=$res[0]['field_uid'];
				$_SESSION['field_uid']=$res[0]['field_uid'];
				$_SESSION['realname']=$res[0]['admin_realname'];
				$_SESSION['username']=$res[0]['admin_name'];
				$_SESSION['email']=$res[0]['admin_email'];
				
				//print_r($_SESSION);
				$this->success("登录成功",U('field/index/index'));
				
			}
			else
			{
				//echo "用户名或密码错误，请重试";
				$this->error("用户名或密码错误，请重试",U('field/public/login',array('field_uid'=>post('field_uid'))));
			}
			//echo "lsdjkfsdlfjsdlfksdf<hr>";
		
		}
		else
		{
			$this->error("必须输入 用户名密码",U('field/public/login',array('field_uid'=>post('field_uid'))));
		}
	

		
	}


	
	public function logout()
	{
		if(isset($_SESSION['field_admin_id']))
		{
			$field_uid=$_SESSION['field_uid'];
			
			unset($_SESSION['field_admin_id']);
			unset($_SESSION['uid']);
			unset($_SESSION['field_uid']);
			unset($_SESSION['realname']);
			unset($_SESSION['username']);			
			unset($_SESSION['email']);

			$this->success("退出成功",U('field/public/login',array('field_uid'=>$field_uid)));
		}
		else
		{
			$this->error("您已经退出",U('field/public/login'));
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