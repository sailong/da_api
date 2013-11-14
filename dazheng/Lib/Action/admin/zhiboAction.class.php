<?php
/**
 *    #Case		tankuang
 *    #Page		ArcAction.class.php (新闻)
 *
 *    @author		Zhang Long
 *    @E-mail		68779953@qq.com
 */
class zhiboAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function zhibo()
	{
		$list=D("zhibo")->zhibo_list_pro();
		
		foreach($list['item'] as $key=>$val){
			$event_ids[$val['event_id']] = $val['event_id'];
		}
		$events = M('event')->where("event_id in('".implode("','",(array)$event_ids)."')")->select();
		foreach($events as $key=>$val){
			$event_list[$val['event_id']] = $val['event_name'];
		}
		$this->assign("event_list",$event_list);
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","直播");
    	$this->display();
	}

	public function zhibo_add()
	{
		
		import("@.ORG.editor");  //导入类
		$editor=new editor("400px","700px",$data['zhibo_content'],"zhibo_content");     //创建一个对象
		$a=$editor->createEditor();   //返回编辑器
		$b=$editor->usejs();             //js代码
		$this->assign('usejs',$b);     //输出到html
		$this->assign('editor',$a);

		$this->assign("page_title","添加直播");
    	$this->display();
	}

	public function zhibo_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["zhibo_name"]=post("zhibo_name");
			$data["event_id"]=post("event_id");
			$data["zhibo_url"]=post("zhibo_url");
			$data["zhibo_content"]=post("zhibo_content");
			$data["zhibo_state"]=post("zhibo_state");
			$data["zhibo_addtime"]=time();
			
			if($_FILES["zhibo_pic"]["error"]==0)
			{
				
				$uploadinfo=upload_file("upload/zhibo");
				foreach($uploadinfo as $key=>$val){
					$pic_list[$val['up_name']] = $val;
				}
				unset($uploadinfo);
				if(!empty($pic_list["zhibo_pic"]))
				{
					$data["zhibo_pic"]=$pic_list['zhibo_pic']["savepath"] . $pic_list['zhibo_pic']["savename"];
					/* $img_exp = explode('.',$data["zhibo_pic"]);
					$ext_end = end($img_exp);
					$o_img = WEB_ROOT_PATH.'/'.$data["zhibo_pic"];
					$s_img = $o_img.'_s.'.$ext_end;
					$s_path = $data["zhibo_pic"].'_s.'.$ext_end;
					com_thumb($o_img, $s_img,'', 60, 60);
					$data["zhibo_pic"] = $s_path; */
				}
				
			}
			
			$list=M("zhibo")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('admin/zhibo/zhibo',array()));
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


	public function zhibo_edit()
	{
		if(intval(get("zhibo_id"))>0)
		{
			$data=M("zhibo")->where("zhibo_id=".intval(get("zhibo_id")))->find();
			$this->assign("data",$data);

			 import("@.ORG.editor");  //导入类
			 $editor=new editor("400px","700px",$data['zhibo_content'],"zhibo_content");     //创建一个对象
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

	public function zhibo_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["zhibo_id"]=post("zhibo_id");
			$data["zhibo_name"]=post("zhibo_name");
			$data["event_id"]=post("event_id");
			$data["zhibo_url"]=post("zhibo_url");
			$data["zhibo_content"]=post("zhibo_content");
			$data["zhibo_state"]=post("zhibo_state");
			
			if($_FILES["zhibo_pic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/zhibo");
				foreach($uploadinfo as $key=>$val){
					$pic_list[$val['up_name']] = $val;
				}
				unset($uploadinfo);
				if(!empty($pic_list["zhibo_pic"]))
				{
					$data["zhibo_pic"]=$pic_list['zhibo_pic']["savepath"] . $pic_list['zhibo_pic']["savename"];
					/* $img_exp = explode('.',$data["zhibo_pic"]);
					$ext_end = end($img_exp);
					$o_img = WEB_ROOT_PATH.'/'.$data["zhibo_pic"];
					$s_img = $o_img.'_s.'.$ext_end;
					$s_path = $data["zhibo_pic"].'_s.'.$ext_end;
					com_thumb($o_img, $s_img,'', 60, 60);
					$data["zhibo_pic"] = $s_path; */
				}
			
			}
			
			$list=M("zhibo")->save($data);
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

	public function zhibo_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("zhibo")->where("zhibo_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}



	public function zhibo_detail()
	{
		if(intval(get("zhibo_id"))>0)
		{
			$data=M("zhibo")->where("zhibo_pic_id=".intval(get("zhibo_pic_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["zhibo_name"]."直播");
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