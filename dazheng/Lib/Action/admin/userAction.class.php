<?php
/**
 *    #Case		tankuang
 *    #Page		UserAction.class.php (用户)
 *
 *    @author		Zhang Long
 *    @E-mail		68779953@qq.com
 */
class userAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function user_dialog()
	{
		$list=D("user")->user_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","用户");
    	$this->display();
	}
	
	
	
	public function user_zimeiti()
	{
		$level = get('level');
		if($level!==''){
			$level_sql=" and pre_common_member_profile.level='{$level}'";
		}
		$list=D("user")->user_list_pro(" and pre_common_member_profile.is_zimeiti='Y'{$level_sql}");
		$level_tmp = select_dict(19);
		foreach($level_tmp as $key=>$val){
			$levels[$val['dict_value']] = $val;
		}
		
		unset($level_tmp);
		$this->assign("levels",$levels);
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","用户");
    	$this->display();
	}
	
	
	public function to_zimeiti_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update pre_common_member_profile set is_zimeiti='Y' where uid=".$ids_arr[$i]." ");
			}
			
			echo "succeed^恭喜，升级成功";
			
		}
	}
	
	
	public function cancel_zimeiti_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update pre_common_member_profile set is_zimeiti='N' where uid=".$ids_arr[$i]." ");
			}
			
			echo "succeed^操作成功";
			
		}
	}
	
	

	public function user()
	{
		$list=D("user")->user_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","用户");
    	$this->display();
	}

	public function user_add()
	{
		$levels = select_dict(19);
		$this->assign("levels",$levels);
		$this->assign("page_title","添加用户");
    	$this->display();
	}

	public function user_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["username"]=post("username");		
			$password=post("password");
			 $salt = substr(uniqid(rand()), -6);
			 $password = md5(md5($password).$salt);
			$data["salt"]=$salt;
			$data["password"]=$password;
			$data["email"]=post("email"); 
			
			$data["regip"]=time();
			$data["regdate"]=time();
			//生成ucenter会员 
			$list=M("ucenter_members","pre_")->add($data); 
			$ucuid=$list;
			unset($data["salt"]);
			//unset($data["username"]);
			//$data["realname"]=post("realname"); 
			$data["mobile"]=post("mobile");  
			$data["groupid"]=10;  
			//生成社区会员 
			$list=M("common_member","pre_")->add($data); 
			
			$data["uid"]=$ucuid; 
			$data["gender"]=post("gender"); 
			$data["realname"]=post("realname");
			$data["enrealname"]=post("enrealname"); 
			$data["level"]=post("level"); 
			
			//生成真实姓名
			$list=M("common_member_profile","pre_")->add($data); 
			$data["nickname"]=post("realname"); 
			$data["ucuid"]=$ucuid; 
			$data["role_id"]=3; 			
			
			//生成微博记录
			$list=M("members","jishigou_")->add($data); 
			
			if($list!=false)
			{
					$this->success("添加成功",U('admin/user/user',array('event_id'=>post('event_id'))));
			}
			else
			{
				$this->error("添加失败");
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}


	public function user_edit()
	{
		if(intval(get("uid"))>0)
		{
			$data=M("common_member","pre_")->where("uid=".intval(get("uid")))->find();
			$db = M( "common_member","pre_" );
			$fix ="pre_";
			$table = $fix."common_member";
			$table2 = $fix."common_member_profile";
			$data= $db -> field( "$table.*,$table2.*,$table.username as uname" ) ->
			join( "$table2 on $table.uid=$table2.uid" ) ->
			where("$table.uid=".intval(get("uid")))->find();
			$this->assign("data",$data); 
			$levels = select_dict(19);
			$this->assign("levels",$levels);
			
			$this->assign("page_title","修改用户");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function user_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{

			$uid=post("uid");	 
			$username=post("username");
			$realname=post("realname");
			$enrealname=post("enrealname");
			$password=post("password");
			$gender=post("gender"); 
			$mobile=post("mobile"); 
			$email=post("email"); 
			$level=post("level"); 
			
			$upda="username='$username',";
			$updp="uid='$uid',";
			$updj="username='$username',";
			if($password){
			 $salt = substr(uniqid(rand()), -6);
			 $password = md5(md5($password).$salt); 
			$upda.="password='$password',salt='$salt',";
			
			//$updp.="password='$password',";
			}
			if($email){	
			$upda.="email='$email',";
			}
			if($mobile){	
			$updp.="mobile='$mobile',";
			} 
			if($realname){	
			$updp.="realname='$realname',";
			$updj.="nickname='$realname',";
			}
			if($enrealname){	
			$updp.="enrealname='$enrealname',";
			$updj.="enrealname='$enrealname',";
			}
			if($gender){	
			$updp.="gender='$gender',";
			$updj.="gender='$gender',";
			}
			if($level){	
			$updp.="level='$level',";
			}
			  
			  
			$upda.="username='$username'";
			$updp.="uid='$uid'";
			$updj.="username='$username'";
			
			//ucenter会员  
			$res=M()->execute("update pre_ucenter_members set $upda where uid=".$uid." ");  
			//生成社区会员 			
			$res1=M()->execute("update pre_common_member set $upda where uid=".$uid." "); 		
			//生成社区会员 			
			$res2=M()->execute("update pre_common_member_profile set $updp where uid=".$uid." ");
			/* 	echo "update pre_common_member_profile set $updp where uid=".$uid;
			  var_dump($res2);die; */
			//生成微博记录   
			$res3=M()->execute("update jishigou_members set $updj where uid=".$uid." ");   
			if($res!=false||$res1!=false||$res2!=false||$res3!=false)
			{ 
				
					$this->success("修改成功",U('admin/user/user',array('event_id'=>post('event_id'))));
			}
			else
			{ 
					$this->success("修改失败",U('admin/user/user',array('event_id'=>post('event_id'))));
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}

	public function user_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				//$res=M("user")->where("uid=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除功能暂停";
		}
	}


	public function user_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_user set user_state=1 where uid=".$ids_arr[$i]." ");
			}
			if($res)
			{
				echo "succeed^审核成功";
			}
			else
			{
				echo "error^审核成功";
			}			
			
		}
	}

	public function user_detail()
	{
		if(intval(get("uid"))>0)
		{
			$data=M("common_member","pre_")->where("uid=".intval(get("uid")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["username"]."用户");
				$this->display();
			}
			else
			{
				$this->error("您该问的信息不存在");	
			}
			
		}
		else
		{
			$this->error("您该问的信息不存在");
		}

	}


	

}
?>