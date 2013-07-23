<?php
class publicAction extends Action
{
	
	public function verify() {
        $type	 =	 isset($_GET['type'])?$_GET['type']:'gif';
        import("@.ORG.String");
        import("@.ORG.Image");
        Image::buildImageVerify(4,1,$type,60,27);
    }

	public function login()
	{

		$this->display();
    }

	public function login_action()
	{

		if(post("username")!="" && post("password")!="")
		{
			if($_SESSION['verify']==md5(post("code")))
			{
				$user=M()->query("select admin_id,admin_name,admin_role_id from tbl_admin where admin_name='".post("username")."' and admin_password='".md5(post("password"))."' ");
				if($user[0]['admin_id'])
				{
					$_SESSION['admin_id']=$user[0]['admin_id'];
					$_SESSION['admin_name']=$user[0]['admin_name'];
					$_SESSION['admin_role_id']=$user[0]['admin_role_id'];

					$up=M()->execute("update tbl_admin set admin_lastip='".get_ip()."' , admin_lasttime='".time()."' where admin_id='".$user[0]['admin_id']."' ");
					$this->success("登录成功",U('admin/index/index'));
				}
				else
				{
					$this->error("用户名或密码错误，请重试");
				}
			}
			else
			{
				$this->error("验证码错误，请重试");
			}
			
		}
		else
		{
			$this->error("用户名密码必须输入，请重试");
		}

	}

	public function logout()
	{
		if(isset($_SESSION['admin_id']))
		{
			unset($_SESSION['admin_id']);
			unset($_SESSION['admin_name']);
			unset($_SESSION['admin_role_id']);
			unset($_SESSION);
			//$this->assign("JumpUrl");
			$this->success("注销成功！",U('admin/index/login'));
		}
		else
		{
			$this->error("您已经退出");	
		}
    }




	public function load_city_action()
	{
		if(post("state_id"))
		{
			$city=M("city")->query("select * from tbl_city where city_up_num='".post("state_id")."' ");
			if(count($city)>0)
			{
				for($i=0; $i<count($city); $i++)
				{
					$str .=$city[$i]['city_name']."|".$city[$i]['city_id']."|".$city[$i]['city_code'].",";
				}
				 echo "succeed^".$str;
			}
		}
	
	}


	public function load_code_action()
	{
		if(post("city_id"))
		{
			$city=M()->query("select * from tbl_city where city_id='".post("city_id")."' ");
			$str .=$city[0]['city_code']."^";
			$str .=$city[0]['city_path'];
			echo "succeed^".$str;
		}
	
	}


	public function load_ab_action()
	{
		$field_uid=get("field_uid");
		$row=M()->query("select coursetype,par from pre_common_course where uid='".$field_uid."' group by coursetype order by coursetype asc ");
		for($i=0; $i<count($row); $i++)
		{
			if($row[$i]['par'])
			{
				$row[$i]['par']=str_replace(",","|",$row[$i]['par']);
				$str .=$row[$i]['coursetype'].','.$row[$i]['par']."-";
			}
			
		}
		echo "succeed^".$str;
	}


	public function upload()
	{
		$upload=upload_file("upload/editor");
	}


	




}