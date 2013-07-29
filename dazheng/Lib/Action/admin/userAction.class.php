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

		$this->assign("page_title","添加用户");
    	$this->display();
	}

	public function user_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["user_name"]=post("user_name");
			$data["user_password"]=md5(post("user_password"));
			$data["user_realname"]=post("user_realname");
			$data["role_id"]=post("role_id");
			$data["company_id"]=post("company_id");
			$data["user_sex"]=post("user_sex");
			$data["user_email"]=post("user_email");
			$data["user_email2"]=post("user_email2");
			$data["user_nation"]=post("user_nation");
			$data["user_jiguan"]=post("user_jiguan");
			$data["user_company"]=post("user_company");
			$data["user_company_address"]=post("user_company_address");
			$data["user_company_post"]=post("user_company_post");
			$data["user_address"]=post("user_address");
			$data["user_post"]=post("user_post");
			$data["user_xueli"]=post("user_xueli");
			$data["user_xuewei"]=post("user_xuewei");
			$data["user_zhuanye"]=post("user_zhuanye");
			$data["user_fangxiang"]=post("user_fangxiang");
			$data["user_duty"]=post("user_duty");
			$data["user_zhicheng"]=post("user_zhicheng");
			$data["user_qq"]=post("user_qq");
			$data["user_tel"]=post("user_tel");
			$data["user_mobile"]=post("user_mobile");
			$data["user_content"]=post("user_content");
			$data["user_lasttime"]=strtotime(post("user_lasttime"));

			$data["user_fax"]=post("user_fax");
			$data["user_birthday"]=post("user_birthday");

			$data["user_addtime"]=time();
			
			$list=M("user")->add($data);
			if($list!=false)
			{
				$this->success("添加成功");
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
		if(intval(get("user_id"))>0)
		{
			$data=M("user")->where("user_id=".intval(get("user_id")))->find();
			$this->assign("data",$data);

			$xl=select_dict(4,"select");
			$this->assign("xl",$xl);
			$xw=select_dict(5,"select");
			$this->assign("xw",$xw);
			
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

			$data["user_id"]=post('user_id');
			$data["role_id"]=post("role_id");
			
			if(post("user_password"))
			{
				$data["user_password"]=md5(post("user_password"));
			}
			$data["user_realname"]=post("user_realname");
			$data["user_sex"]=post("user_sex");
			$data["user_email"]=post("user_email");
			$data["user_email2"]=post("user_email2");
			$data["user_nation"]=post("user_nation");
			$data["user_jiguan"]=post("user_jiguan");
			$data["user_company"]=post("user_company");
			$data["user_company_address"]=post("user_company_address");
			$data["user_company_post"]=post("user_company_post");
			$data["user_address"]=post("user_address");
			$data["user_post"]=post("user_post");
			$data["user_xueli"]=post("user_xueli");
			$data["user_xuewei"]=post("user_xuewei");
			$data["user_zhuanye"]=post("user_zhuanye");
			$data["user_fangxiang"]=post("user_fangxiang");
			$data["user_duty"]=post("user_duty");
			$data["user_zhicheng"]=post("user_zhicheng");
			$data["user_qq"]=post("user_qq");
			$data["user_tel"]=post("user_tel");
			$data["user_mobile"]=post("user_mobile");
			$data["user_content"]=post("user_content");
			$data["user_fax"]=post("user_fax");
			$data["user_birthday"]=post("user_birthday");

			$list=M("user")->save($data);
			if($list!=false)
			{
				$this->success("修改成功");
			}
			else
			{
				$this->error("修改失败");
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
				$res=M("user")->where("user_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function user_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_user set user_state=1 where user_id=".$ids_arr[$i]." ");
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
		if(intval(get("user_id"))>0)
		{
			$data=M("user")->where("user_id=".intval(get("user_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["user_name"]."用户");
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