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

		if(post("username") && post("password"))
		{
			//print_r($_POST)."<hr>";
			
			$res=M()->query("select uid,username,(password),salt from pre_ucenter_members where username='".post("username")."' ");
			//print_r($res);
			if($res[0]['password']==md5(md5(post("password")).$res[0]['salt']))
			{
				$realname=M()->query("select realname from pre_common_member_profile where uid='".$res[0]['uid']."'");
				
				$_SESSION['uid']=$res[0]['uid'];
				$_SESSION['field_uid']=$res[0]['uid'];
				$_SESSION['realname']=$realname[0]['realname'];
				$_SESSION['username']=$res[0]['username'];
				$_SESSION['email']=$res[0]['password'];
				
				//print_r($_SESSION);
				$this->success("登录成功",U('field/index/index'));
			}
			else
			{
				$this->error("用户名或密码错误，请重试",U('field/public/login'));
			}
			//echo "lsdjkfsdlfjsdlfksdf<hr>";
		
		}
		else
		{
			$this->error("必须输入 用户名密码",U('field/public/login'));
		}
	

		
	}


	public function logout()
	{
		if(isset($_SESSION['uid']))
		{
			unset($_SESSION['uid']);
			unset($_SESSION['realname']);
			unset($_SESSION['username']);
			unset($_SESSION['email']);			
			unset($_SESSION['field_uid']);			

			$this->success("退出成功",U('field/public/login'));
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