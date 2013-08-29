<?php
/**
 *    #Case		bwvip
 *    #Page		PhotoAction.class.php (照片)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-06
 */
class photoAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function photo()
	{
		$list=D("photo")->photo_list_pro();
		//echo '<pre>';
		//var_dump($list);die;
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","照片");
    	$this->display();
	}

	public function photo_add()
	{
		$album=D('album')->album_select_pro();
		$this->assign('album',$album['item']);
		
		$this->assign("page_title","添加照片");
    	$this->display();
	}

	public function photo_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["uid"]=post("uid");
			$data["album_id"]=post("album_id");
			$data["photo_name"]=post("photo_name");
			if($_FILES["photo_url"]["error"]==0)
			{
				$uploadinfo=upload_img("upload/photo/",true,'115','80');
				$data["photo_url"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["photo_addtime"]=time();
			
			$list=M("photo")->add($data);
			$this->success("添加成功",U('admin/photo/photo'));
		}
		else
		{
			$this->error("不能重复提交",U('admin/photo/photo_add'));
		}

	}


	public function photo_edit()
	{
		if(intval(get("photo_id"))>0)
		{
			
			$album=D('album')->album_select_pro();
			$this->assign('album',$album['item']);
		
			$data=M("photo")->where("photo_id=".intval(get("photo_id")))->find();
			$this->assign("data",$data);
			
			
			
			$this->assign("page_title","修改照片");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function photo_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["photo_id"]=post("photo_id");
			$data["uid"]=post("uid");
			$data["album_id"]=post("album_id");
			$data["photo_name"]=post("photo_name");
			if($_FILES["photo_url"]["error"]==0)
			{
				$uploadinfo=upload_img("upload/photo/",true,'115','80');
				$data["photo_url"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			
			$list=M("photo")->save($data);
			$this->success("修改成功",U('admin/photo/photo'));			
		}
		else
		{
			$this->error("不能重复提交",U('admin/photo/photo'));
		}

	}

	public function photo_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("photo")->where("photo_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function photo_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_photo set photo_state=1 where photo_id=".$ids_arr[$i]." ");
			}
			if($res)
			{
				echo "succeed^审核成功";
			}
			else
			{
				echo "error^审核失败";
			}			
			
		}
	}

	public function photo_detail()
	{
		if(intval(get("photo_id"))>0)
		{
			$data=M("photo")->where("photo_id=".intval(get("photo_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["photo_name"]."照片");
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