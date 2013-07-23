<?php

class userAction extends userpublicAction
{
	public function _intialize()	
	{
		parent::_intialize();
	}

	public function index()
	{

		$list=D("arc")->arc_admin_list_pro(" and user_id='".$_SESSION['user_id']."' ");

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("seo_title","用户中心");
		$this->display();
	}

	
	public function arc_add()
	{
		$arc_type=D("arctype")->arctype_admin_tree_pro(" and arctype_parent_id=0 "," and arctype_type='A' ");
		$this->assign("arc_type",$arc_type['item']);
		//print_r($arc_type);

		import("@.ORG.editor");  //导入类
		$editor=new editor("450px","600px",$data['arc_content'],"arc_content");     //创建一个对象
		$a=$editor->createEditor();   //返回编辑器
		$b=$editor->usejs();             //js代码
		$this->assign('usejs',$b);     //输出到html
		$this->assign('editor',$a);

		$this->assign("page_title","添加文章");
    	$this->display();
	}

	public function arc_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["arc_name"]=post("arc_name");
			$data["user_id"]=$_SESSION["user_id"];
			$data["arctype_id"]=post("arctype_id");
			$data["arctype_id2"]=post("arctype_id2");
			$data["arctype_id3"]=post("arctype_id3");
			$data["arc_note"]=post("arc_note");
			if($_FILES["arc_pic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/arc","png,jpg,jpeg,gif,bmp,tiff,psd");
				$data["arc_pic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["arc_source"]=post("arc_source");
			$data["arc_editor"]=post("arc_editor");
			$data["arc_content"]=stripslashes($_POST["arc_content"]);
			$data["arc_is_tj"]=post("arc_is_tj");
			$data["arc_state"]=0;
			$data["arc_path"]=post("arc_path");
			$data["arc_addtime"]=time();
			$data["arc_statetime"]=strtotime(post("arc_statetime"));
			
			$list=M("arc")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('home/user/index'));
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


	public function arc_edit()
	{
		if(intval(get("arc_id"))>0)
		{
			$data=M("arc")->where("arc_id=".intval(get("arc_id")))->find();
			$this->assign("data",$data);

			 import("@.ORG.editor");  //导入类
			 $editor=new editor("450px","600px",$data['arc_content'],"arc_content");     //创建一个对象
			 $a=$editor->createEditor();   //返回编辑器
			 $b=$editor->usejs();             //js代码
			 $this->assign('usejs',$b);     //输出到html
			 $this->assign('editor',$a);


			$arc_type=D("arctype")->arctype_admin_tree_pro(" and arctype_parent_id=0 "," and arctype_type='A' ");
			$this->assign("arc_type",$arc_type['item']);
			
			$this->assign("page_title","修改文章");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function arc_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["arc_id"]=post("arc_id");
			$data["arc_name"]=post("arc_name");
			//$data["user_id"]=$_SESSION["user_id"];
			$data["arctype_id"]=post("arctype_id");
			$data["arctype_id2"]=post("arctype_id2");
			$data["arctype_id3"]=post("arctype_id3");
			$data["arc_note"]=post("arc_note");
			if($_FILES["arc_pic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/arc","png,jpg,jpeg,gif,bmp,tiff,psd");
				$data["arc_pic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["arc_source"]=post("arc_source");
			$data["arc_editor"]=post("arc_editor");
			$data["arc_content"]=stripslashes($_POST["arc_content"]);
			$data["arc_is_tj"]=post("arc_is_tj");
			$data["arc_path"]=post("arc_path");
			$data["arc_statetime"]=strtotime(post("arc_statetime"));
			
			$list=M("arc")->save($data);
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

	public function user_edit()
	{

			$data=M("user")->where("user_id=".$_SESSION["user_id"])->find();
			$this->assign("data",$data);

			$xl=select_dict(4,"select");
			$this->assign("xl",$xl);
			$xw=select_dict(5,"select");
			$this->assign("xw",$xw);
			
			$this->assign("page_title","修改用户");
			$this->display();

	}

	public function user_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["user_id"]=$_SESSION['user_id'];
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
			$this->error("参数错误或来路非法");
		}

	}



	public function word()
	{
		$list=D("word")->word_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","行业词汇");
    	$this->display();
	}
	

	public function org()
	{
		$list=D("org")->org_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$xz=select_dict("6","select");
		$this->assign("xz",$xz);
		$lx=select_dict("7","select");
		$this->assign("lx",$lx);

		$sheng=M()->query("select * from tbl_province where province_up_num=0 ");
		$this->assign("sheng",$sheng);

		$this->assign("page_title","从业机构");
    	$this->display();
	}

	public function org_detail()
	{
		if(intval(get("org_id"))>0)
		{
			$data=M("org")->where("org_id=".intval(get("org_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["org_name"]."从业机构");
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



	public function org_my()
	{
		$list=D("org")->org_list_pro(" and user_id='".$_SESSION['user_id']."' ");
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$xz=select_dict("6","select");
		$this->assign("xz",$xz);
		$lx=select_dict("7","select");
		$this->assign("lx",$lx);

		$sheng=M()->query("select * from tbl_province where province_up_num=0 ");
		$this->assign("sheng",$sheng);

		$this->assign("page_title","从业机构");
    	$this->display();
	}

	public function org_edit()
	{
		if(intval(get("org_id"))>0)
		{
			$data=M("org")->where("org_id=".intval(get("org_id")))->find();
			$city=M("city")->where("city_id='".$data['org_city']."' ")->find();
			$data['org_cityname']=$city['city_name'];

			$user=M()->query("select user_name from ".C("db_prefix")."user where  user_id='".$data["user_id"]."' ");
			$data["user_name"]=$user[0]["user_name"];


			$this->assign("data",$data);


			$sheng=M()->query("select * from tbl_province where province_up_num=0 ");
			$this->assign("sheng",$sheng);

			$xz=select_dict("6","select");
			$this->assign("xz",$xz);
			$lx=select_dict("7","select");
			$this->assign("lx",$lx);	
			
			$this->assign("page_title","修改从业机构");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function org_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["org_id"]=post("org_id");
			$data["org_name"]=post("org_name");
			$data["org_address"]=post("org_address");
			$data["org_post"]=post("org_post");
			$data["org_tel"]=post("org_tel");
			$data["org_fax"]=post("org_fax");
			$data["org_email"]=post("org_email");
			$data["org_website"]=post("org_website");
			$data["org_buildtime"]=post("org_buildtime");
			$data["org_xingzhi"]=post("org_xingzhi");
			$data["org_leixing"]=post("org_leixing");
			$data["org_is_gaoxin"]=post("org_is_gaoxin");
			$data["org_fanwei"]=post("org_fanwei");
			$data["org_lingyu"]=post("org_lingyu");
			$data["org_hangye"]=post("org_hangye");
			$data["org_zijin"]=post("org_zijin");
			$data["org_city"]=post("org_city");
			$data["org_province"]=post("org_province");
			$data["org_jianjie"]=post("org_jianjie");
			$data["org_lingyuqita"]=post("org_lingyuqita");
			$data["org_hangyeqita"]=post("org_hangyeqita");
			$data["org_zzlb"]=post("org_zzlb");
			$data["org_zzmc"]=post("org_zzmc");
			$data["org_zzjb"]=post("org_zzjb");
			$data["org_zzjg"]=post("org_zzjg");
			$data["org_zzrq"]=post("org_zzrq");
			$data["org_zlrz"]=post("org_zlrz");
			$data["org_showzlrz"]=post("org_showzlrz");
			
			$list=M("org")->save($data);

			$this->success("修改成功");
			
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}




	public function zizhi_dialog()
	{
		$list=D("zizhi")->zizhi_list_pro(" and org_id='".get("org_id")."' ");

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","企业资质");
    	$this->display();
	}


	public function zizhi_add()
	{
		$dict1=select_dict("8","select");
		$this->assign("dict1",$dict1);
		$dict2=select_dict("9","select");
		$this->assign("dict2",$dict2);
		$dict3=select_dict("10","select");
		$this->assign("dict3",$dict3);
		$dict4=select_dict("11","select");
		$this->assign("dict4",$dict4);
		$dict5=select_dict("12","select");
		$this->assign("dict5",$dict5);

		$this->assign("page_title","添加企业资质");
    	$this->display();
	}

	public function zizhi_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["org_id"]=post("org_id");
			if(post("zt_1"))
			{
				$data["zizhi_name"]=post("zizhi_name");	
			}
			else
			{
				$data["zizhi_name"]=post("zizhi_name2");	
			}

			if(post("zt_2"))
			{
				$data["zizhi_big_cat"]=post("zizhi_big_cat");	
			}
			else
			{
				$data["zizhi_big_cat"]=post("zizhi_big_cat2");	
			}

			if(post("zt_3"))
			{
				$data["zizhi_small_cat"]=post("zizhi_small_cat");	
			}
			else
			{
				$data["zizhi_small_cat"]=post("zizhi_small_cat2");	
			}

			if(post("zt_4"))
			{
				$data["zizhi_org"]=post("zizhi_org");	
			}
			else
			{
				$data["zizhi_org"]=post("zizhi_org2");	
			}

			if(post("zt_5"))
			{
				$data["zizhi_level"]=post("zizhi_level");	
			}
			else
			{
				$data["zizhi_level"]=post("zizhi_level2");	
			}
			$data["zizhi_endtime"]=post("zizhi_endtime");
			
			$list=M("zizhi")->add($data);
			if($list!=false)
			{
				msg_dialog_tip("succeed^添加成功");
			}
			else
			{
				msg_dialog_tip("error^添加失败");
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}


	public function zizhi_edit()
	{
		if(intval(get("zizhi_id"))>0)
		{
			$data=M("zizhi")->where("zizhi_id=".intval(get("zizhi_id")))->find();
			$this->assign("data",$data);

			$dict1=select_dict("8","select");
			$this->assign("dict1",$dict1);
			$dict2=select_dict("9","select");
			$this->assign("dict2",$dict2);
			$dict3=select_dict("10","select");
			$this->assign("dict3",$dict3);
			$dict4=select_dict("11","select");
			$this->assign("dict4",$dict4);
			$dict5=select_dict("12","select");
			$this->assign("dict5",$dict5);
			
			$this->assign("page_title","修改企业资质");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function zizhi_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["zizhi_id"]=post("zizhi_id");
			if(post("zt_1"))
			{
				$data["zizhi_name"]=post("zizhi_name");	
			}
			else
			{
				$data["zizhi_name"]=post("zizhi_name2");	
			}

			if(post("zt_2"))
			{
				$data["zizhi_big_cat"]=post("zizhi_big_cat");	
			}
			else
			{
				$data["zizhi_big_cat"]=post("zizhi_big_cat2");	
			}

			if(post("zt_3"))
			{
				$data["zizhi_small_cat"]=post("zizhi_small_cat");	
			}
			else
			{
				$data["zizhi_small_cat"]=post("zizhi_small_cat2");	
			}

			if(post("zt_4"))
			{
				$data["zizhi_org"]=post("zizhi_org");	
			}
			else
			{
				$data["zizhi_org"]=post("zizhi_org2");	
			}

			if(post("zt_5"))
			{
				$data["zizhi_level"]=post("zizhi_level");	
			}
			else
			{
				$data["zizhi_level"]=post("zizhi_level2");	
			}
			$data["zizhi_endtime"]=post("zizhi_endtime");
			
			$list=M("zizhi")->save($data);
			if($list!=false)
			{
				msg_dialog_tip("succeed^修改成功");
			}
			else
			{
				msg_dialog_tip("error^修改失败");
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}

	public function zizhi_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("zizhi")->where("zizhi_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	

}
?>

