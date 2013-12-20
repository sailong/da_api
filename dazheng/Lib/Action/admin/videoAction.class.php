<?php
/**
 *    #Case		tankuang
 *    #Page		ArcAction.class.php (新闻)
 *
 *    @author		Zhang Long
 *    @E-mail		68779953@qq.com
 */
class videoAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function video()
	{
		$list=D("video")->video_list_pro();
		
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

		$this->assign("page_title","视频");
    	$this->display();
	}

	public function video_add()
	{
		
		import("@.ORG.editor");  //导入类
		$editor=new editor("400px","700px",$data['video_content'],"video_content");     //创建一个对象
		$a=$editor->createEditor();   //返回编辑器
		$b=$editor->usejs();             //js代码
		$this->assign('usejs',$b);     //输出到html
		$this->assign('editor',$a);

		$this->assign("page_title","添加视频");
    	$this->display();
	}

	public function video_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["video_name"]=post("video_name");
			
			$data["video_url"]=post("video_url");
			$data["video_content"]=post("video_content");
			$data["video_sort"]=post("video_sort");
			$data["video_addtime"]=time();
			
			if($_FILES["video_pic"]["error"]==0)
			{
				
				$uploadinfo=upload_file("upload/video");
				foreach($uploadinfo as $key=>$val){
					$pic_list[$val['up_name']] = $val;
				}
				unset($uploadinfo);
				if(!empty($pic_list["video_pic"]))
				{
					$data["video_pic"]=$pic_list['video_pic']["savepath"] . $pic_list['video_pic']["savename"];
					/* $img_exp = explode('.',$data["video_pic"]);
					$ext_end = end($img_exp);
					$o_img = WEB_ROOT_PATH.'/'.$data["video_pic"];
					$s_img = $o_img.'_s.'.$ext_end;
					$s_path = $data["video_pic"].'_s.'.$ext_end;
					com_thumb($o_img, $s_img,'', 60, 60);
					$data["video_pic"] = $s_path; */
				}
				
			}
			
			$list=M("video")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('admin/video/video',array()));
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


	public function video_edit()
	{
		if(intval(get("video_id"))>0)
		{
			$data=M("video")->where("video_id=".intval(get("video_id")))->find();
			$this->assign("data",$data);

			 import("@.ORG.editor");  //导入类
			 $editor=new editor("400px","700px",$data['video_content'],"video_content");     //创建一个对象
			 $a=$editor->createEditor();   //返回编辑器
			 $b=$editor->usejs();             //js代码
			 $this->assign('usejs',$b);     //输出到html
			 $this->assign('editor',$a);

			$this->assign("page_title","修改视频");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function video_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["video_id"]=post("video_id");
			$data["video_name"]=post("video_name");
			
			$data["video_url"]=post("video_url");
			$data["video_content"]=post("video_content");
			$data["video_sort"]=post("video_sort");
			
			if($_FILES["video_pic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/video");
				foreach($uploadinfo as $key=>$val){
					$pic_list[$val['up_name']] = $val;
				}
				unset($uploadinfo);
				if(!empty($pic_list["video_pic"]))
				{
					$data["video_pic"]=$pic_list['video_pic']["savepath"] . $pic_list['video_pic']["savename"];
					/* $img_exp = explode('.',$data["video_pic"]);
					$ext_end = end($img_exp);
					$o_img = WEB_ROOT_PATH.'/'.$data["video_pic"];
					$s_img = $o_img.'_s.'.$ext_end;
					$s_path = $data["video_pic"].'_s.'.$ext_end;
					com_thumb($o_img, $s_img,'', 60, 60);
					$data["video_pic"] = $s_path; */
				}
			
			}
			
			$list=M("video")->save($data);
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

	public function video_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("video")->where("video_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}



	public function video_detail()
	{
		if(intval(get("video_id"))>0)
		{
			$data=M("video")->where("video_id=".intval(get("video_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["video_name"]."视频");
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