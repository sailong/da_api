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

		if(post("username") && post("password") && post("field_uid")!=='')
		{
			
			$res=M()->query("select admin_id,field_uid,admin_name,admin_realname,admin_email,admin_password,admin_role_id from tbl_shop_admin where field_uid='".post("field_uid")."' and admin_name='".post("username")."' ");
			$res = reset($res);
			if($res['admin_password']==md5(post("password")))
			{
				$field_info=M()->query("select field_name from tbl_field where field_uid='".$res['field_uid']."' ");
				$field_info=reset($field_info);
				$_SESSION['shop_admin_id']=$res['admin_id'];
				$_SESSION['shop_admin_role_id']=$res['admin_role_id'];
				$_SESSION['uid']=$res['field_uid'];
				$_SESSION['field_uid']=$res['field_uid'];
				$_SESSION['field_name']=$field_info['field_name'];
				$_SESSION['realname']=$res['admin_realname'];
				$_SESSION['username']=$res['admin_name'];
				$_SESSION['email']=$res['admin_email'];
				
				$this->success("登录成功",U('shop/index/index'));
				
			}
			else
			{
				$this->error("用户名或密码错误，请重试",U('shop/public/login',array('field_uid'=>post('field_uid'))));
			}
		
		}
		else
		{
			$this->error("必须输入 用户名密码",U('shop/public/login',array('field_uid'=>post('field_uid'))));
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

			$this->success("退出成功",U('shop/public/login',array('field_uid'=>$field_uid)));
		}
		else
		{
			$this->error("您已经退出",U('shop/public/login'));
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