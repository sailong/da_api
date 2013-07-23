<?php
 /**
     * 探矿工程 Public 
     * jack 20130201
     *
     */
class publicAction extends Action
{
	public function _initialize()
	{
		$site=M("site")->where(" site_id=1")->find();
		$this->assign("site",$site);

	}


	public function reg()
	{

		$this->assign("seo_title","注册");
		$this->display();
	}


	public function reg_action()
	{
		if(M()->autoCheckToken($_POST))
		{

			if(post("user_name")!="" && post("user_password")!="")
			{
					$user=M()->query("select * from tbl_user where user_name='".post("user_name")."'  ");
					if(!$user[0]['user_id'])
					{
						$data['user_name']=post("user_name");
						$data['user_password']=md5(post("user_password"));
						$data['user_email']=post("user_email");
						$data['role_id']=1;

						$data['user_addtime']=time();
						$res=M("user")->add($data);

						$_SESSION['user_id']=$res;
						$_SESSION['user_name']=$data[0]['user_name'];
						$_SESSION['user_email']=$data[0]['user_email'];
						$_SESSION['role_id']=0;

			
						$this->success("注册成功",U('home/user/index'));
				
					}
					else
					{
						$this->error("该用户已存在，请重新输入");
					}
			
			}
			else
			{
				$this->error("用户名密码必须输入，请重试");
			}
		}
		else
		{
			$this->error("参数有误");
		}

    }


	public function login()
	{

		$this->assign("seo_title","登录");
		$this->display();
	}


	public function login_action()
	{
		
		if(M()->autoCheckToken($_POST))
		{
			if(post("user_name") && post("user_password"))
			{
		
				$res=M()->query("select * from tbl_user where user_name='".post("user_name")."' and user_password='".md5(post("user_password"))."' ");
				if($res[0]['user_id'])
				{

					$_SESSION['user_id']=$res[0]['user_id'];
					$_SESSION['user_name']=$res[0]['user_name'];
					$_SESSION['user_email']=$res[0]['user_email'];
					$_SESSION['role_id']=$res[0]['role_id'];
				
					$up=M()->execute("update tbl_user set  user_lasttime='".time()."' where user_id='".$res[0]['user_id']."' ");
					$this->success("登录成功",U('home/user/index'));
					
				}
				else
				{
					$this->error("用户名或密码错误，请重试");
				}
			
			}
			else
			{
				$this->error("必须输入 用户名密码");
			}
	
		}
		else
		{
			$this->error("参数错误");
		}
		
	}


	public function logout()
	{
		if(isset($_SESSION['user_id']))
		{
			unset($_SESSION['user_id']);
			unset($_SESSION['user_name']);
			unset($_SESSION['user_email']);
			unset($_SESSION['role_id']);

			$this->success("退出成功",U('home/public/login'));
		}
		else
		{
			$this->error("您已经退出",U('home/public/login'));
		}
	}



}
?>