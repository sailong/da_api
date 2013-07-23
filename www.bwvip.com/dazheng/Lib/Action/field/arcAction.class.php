<?php
/**
 *    #Case		bwvip
 *    #Page		ArcAction.class.php (文章)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-05-28
 */
class arcAction extends field_publicAction
{
	public function _initialize()
	{
		parent::_initialize();
	}

	public function arc()
	{
		$list=D("arc")->arc_admin_list_pro(" and arc_type='Q' and field_uid='".$_SESSION['field_uid']."' ");
		//print_r(D());

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","文章");
    	$this->display();
	}

	public function arc_add()
	{
		$arc_type=D("arctype")->arctype_admin_tree_pro(" and arctype_parent_id=0 "," and arctype_type='A' ");
		$this->assign("arc_type",$arc_type['item']);
		//print_r($arc_type);
	
		import("@.ORG.editor");  //导入类
		$editor=new editor("400px","700px",$data['arc_content'],"arc_content");     //创建一个对象
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
			$data["staff_id"]=post("staff_id");
			$data["field_uid"]=$_SESSION["field_uid"];
			$data["language"]=post("language");
			$data["arc_note"]=post("arc_note");
			$data["arc_type"]='Q';
			$data["arc_viewtype"]=post("arc_viewtype");
			if($_FILES["arc_pic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/arc","png,jpg,jpeg,gif,bmp,tiff,psd");
				$data["arc_pic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["arc_source"]=post("arc_source");
			$data["arc_sort"]=post("arc_sort");
			$data["arc_editor"]=post("arc_editor");
			$data["arc_content"]=stripslashes($_POST["arc_content"]);
			$data["arc_is_tj"]=post("arc_is_tj");
			$data["arc_state"]=1;
			$data["arc_path"]=post("arc_path");
			$data["arc_addtime"]=time();
			$data["arc_statetime"]=strtotime(post("arc_statetime"));
			
			$list=M("arc")->add($data);
			if($list!=false)
			{
				
				//blog
				$new_id=$list;
				$res=M()->query("insert into pre_home_blog (blogid,uid,subject,replynum,dateline) values ('".$new_id."','".$row['uid']."','".$data["arc_name"]."','0','".time()."')");
				
				$res2=M()->query("insert into pre_home_blogfield (blogid,uid,message,pic) values ('".$new_id."','".$row['uid']."','".$data["arc_content"]."','".$data["arc_pic"]."')");
			
			
				$table_info=M()->query("show table status where name ='tbl_arc'");
				$up=m()->query("ALTER TABLE `pre_home_blog` AUTO_INCREMENT=".$table_info[0]['Auto_increment']." ");
				
				$this->success("添加成功",U('field/arc/arc',array('arctype_id'=>post("arctype_id"),'language'=>post("language"))));
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
			 $editor=new editor("400px","700px",$data['arc_content'],"arc_content");     //创建一个对象
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
			$data["language"]=post("language");
			$data["staff_id"]=post("staff_id");
			
			$data["arc_note"]=post("arc_note");
			$data["arc_viewtype"]=post("arc_viewtype");
			if($_FILES["arc_pic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/arc","png,jpg,jpeg,gif,bmp,tiff,psd");
				$data["arc_pic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["arc_source"]=post("arc_source");
			$data["arc_sort"]=post("arc_sort");
			$data["arc_editor"]=post("arc_editor");
			$data["arc_content"]=stripslashes($_POST["arc_content"]);
			$data["arc_is_tj"]=post("arc_is_tj");
			$data["arc_path"]=post("arc_path");
			
			
			$list=M("arc")->save($data);
			if($list!=false)
			{
				$up=m()->query("update `pre_home_blog` set subject='".post("arc_name")."' where blogid='".post('arc_id')."' ");
				$up=m()->query("update `pre_home_blogfield` set message='".stripslashes($_POST["arc_content"])."' where blogid='".post('arc_id')."' ");
				
				$this->success("修改成功",U('field/arc/arc',array('arctype_id'=>post("arctype_id"),'language'=>post("language"))));
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

	public function arc_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("arc")->where("arc_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function arc_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_arc set arc_state=1 where arc_id=".$ids_arr[$i]." ");
			}
			echo "succeed^审核成功";
			
		}
	}


	public function arc_checkno_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_arc set arc_state=2 where arc_id=".$ids_arr[$i]." ");
			}
			echo "succeed^操作成功";
			
		}
	}

	public function arc_tj_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_arc set arc_is_tj='Y' where arc_id=".$ids_arr[$i]." ");
			}
			echo "succeed^推荐成功";
			
		}
	}


	public function arc_tjno_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_arc set arc_is_tj='N' where arc_id=".$ids_arr[$i]." ");
			}
			echo "succeed^操作成功";
			
		}
	}


	public function arc_pic_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_arc set arc_is_pic='Y' where arc_id=".$ids_arr[$i]." ");
			}
			echo "succeed^设置成功";
			
		}
	}


	public function arc_picno_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_arc set arc_is_pic='N' where arc_id=".$ids_arr[$i]." ");
			}
			echo "succeed^取消成功";
			
		}
	}



	public function arc_detail()
	{
		if(intval(get("arc_id"))>0)
		{
			$data=M("arc")->where("arc_id=".intval(get("arc_id")))->find();
			if(!empty($data))
			{
				$type=M()->query("select arctype_name from ".C("db_prefix")."arctype where  arctype_id='".$data["arctype_id"]."' ");
				$data["arctype_name"]=$type[0]["arctype_name"];

				$this->assign("data",$data);


				$this->assign("page_title",$data["arc_name"]."文章");
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


	public function arc_tj_edit()
	{
		
			$data=M("arc")->where("arc_id=".intval(get("arc_id")))->find();
			$this->assign("data",$data);

			$this->assign("page_title","修改文章");
			$this->display();

	}

	public function arc_tj_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$blogid=post("blogid");
			$view_type=post("view_type");
			if($_FILES["pic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/blog","png,jpg,jpeg,gif,bmp,tiff,psd");
				$pic=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}

			if($blogid)
			{
				$res=M()->query("update pre_home_blog set view_type='".$view_type."' where blogid='".$blogid."' ");
				if($pic)
				{
					$res=M()->query("update pre_home_blogfield set pic='".$pic."' where blogid='".$blogid."' ");
				}
				$this->success("修改成功",U('field/arc/arc_tj_edit'));
			}
			else
			{
				$this->error("博客ID不能为空",U('field/arc/arc_tj_edit'));
			}
	
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}
	
	
	public function  tongbu()
	{
		$data=M("arc")->where("arc_id=".intval(get("arc_id")))->find();
		if(!empty($data))
		{
			$res=M()->query("insert into pre_home_blog (blogid,uid,subject,replynum,dateline) values ('".$data['arc_id']."','".$data['uid']."','".$data["arc_name"]."','0','".time()."')");
				
			$res2=M()->query("insert into pre_home_blogfield (blogid,uid,message,pic) values ('".$data['arc_id']."','".$data['uid']."','".$data["arc_content"]."','".$data["arc_pic"]."')");
			$this->success("操作成功");
		}
		else
		{
			$this->error("您该问的信息不存在");	
		}
		
	}






	

}
?>