<?php
/**
 *    #Case		tankuang
 *    #Page		ArcAction.class.php (新闻)
 *
 *    @author		Zhang Long
 *    @E-mail		68779953@qq.com
 */
class boyinyuanAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function boyinyuan()
	{
		$list=D("boyinyuan")->byy_list_pro();
		
		/* foreach($list['item'] as $key=>$val){
			$event_ids[$val['event_id']] = $val['event_id'];
		}
		$events = M('event')->where("event_id in('".implode("','",(array)$event_ids)."')")->select();
		foreach($events as $key=>$val){
			$event_list[$val['event_id']] = $val['event_name'];
		}
		$this->assign("event_list",$event_list); */
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","直播");
    	$this->display();
	}

	public function boyinyuan_add()
	{
		
		import("@.ORG.editor");  //导入类
		$editor=new editor("400px","700px",$data['byy_detail'],"byy_detail");     //创建一个对象
		$a=$editor->createEditor();   //返回编辑器
		$b=$editor->usejs();             //js代码
		$this->assign('usejs',$b);     //输出到html
		$this->assign('editor',$a);

		$this->assign("page_title","添加直播");
    	$this->display();
	}

	public function boyinyuan_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["byy_name"]=post("byy_name");
			$data["zhibo_id"]=post("zhibo_id");
			//$data["zhibo_url"]=post("zhibo_url");
			$data["byy_detail"]=post("byy_detail");
			$data["byy_source"]=post("byy_source");
			$data["byy_addtime"]=time();
			if($_FILES["byy_pic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/zhibo");
				foreach($uploadinfo as $key=>$val){
					$pic_list[$val['up_name']] = $val;
				}
				unset($uploadinfo);
				if(!empty($pic_list["byy_pic"]))
				{
					$data["byy_pic"]=$pic_list['byy_pic']["savepath"] . $pic_list['byy_pic']["savename"];
					/* $img_exp = explode('.',$data["zhibo_pic"]);
					$ext_end = end($img_exp);
					$o_img = WEB_ROOT_PATH.'/'.$data["zhibo_pic"];
					$s_img = $o_img.'_s.'.$ext_end;
					$s_path = $data["zhibo_pic"].'_s.'.$ext_end;
					com_thumb($o_img, $s_img,'', 60, 60);
					$data["zhibo_pic"] = $s_path; */
				}
			
			}
			
			$list=M("boyinyuan")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('admin/boyinyuan/boyinyuan',array()));
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


	public function boyinyuan_edit()
	{
		if(intval(get("byy_id"))>0)
		{
			$data=M("boyinyuan")->where("byy_id=".intval(get("byy_id")))->find();
			$this->assign("data",$data);

			 import("@.ORG.editor");  //导入类
			 $editor=new editor("400px","700px",$data['byy_detail'],"byy_detail");     //创建一个对象
			 $a=$editor->createEditor();   //返回编辑器
			 $b=$editor->usejs();             //js代码
			 $this->assign('usejs',$b);     //输出到html
			 $this->assign('editor',$a);

			$this->assign("page_title","修改直播");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function boyinyuan_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["byy_id"]=post("byy_id");
			$data["byy_name"]=post("byy_name");
			$data["zhibo_id"]=post("zhibo_id");
			//$data["zhibo_url"]=post("zhibo_url");
			$data["byy_detail"]=post("byy_detail");
			$data["byy_source"]=post("byy_source");
			
			if($_FILES["byy_pic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/zhibo");
				foreach($uploadinfo as $key=>$val){
					$pic_list[$val['up_name']] = $val;
				}
				unset($uploadinfo);
				if(!empty($pic_list["byy_pic"]))
				{
					$data["byy_pic"]=$pic_list['byy_pic']["savepath"] . $pic_list['byy_pic']["savename"];
					/* $img_exp = explode('.',$data["zhibo_pic"]);
					$ext_end = end($img_exp);
					$o_img = WEB_ROOT_PATH.'/'.$data["zhibo_pic"];
					$s_img = $o_img.'_s.'.$ext_end;
					$s_path = $data["zhibo_pic"].'_s.'.$ext_end;
					com_thumb($o_img, $s_img,'', 60, 60);
					$data["zhibo_pic"] = $s_path; */
				}
			
			}
			
			$list=M("boyinyuan")->save($data);
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

	public function boyinyuan_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("boyinyuan")->where("byy_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}



	public function boyinyuan_detail()
	{
		if(intval(get("byy_id"))>0)
		{
			$data=M("boyinyuan")->where("byy_id=".intval(get("byy_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["byy_name"]."直播");
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