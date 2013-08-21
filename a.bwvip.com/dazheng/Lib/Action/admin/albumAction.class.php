<?php
/**
 *    #Case		bwvip
 *    #Page		AlbumAction.class.php (相册)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-06
 */
class albumAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function album()
	{
		$list=D("album")->album_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","相册");
    	$this->display();
	}

	public function album_add()
	{
		
		$this->assign("page_title","添加相册");
    	$this->display();
	}

	public function album_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
		
			$data["album_name"]=post("album_name");
			$data["album_sort"]=post("album_sort");
			$data["album_addtime"]=time();
			
			$list=M("album")->add($data);
			$this->success("添加成功",U('admin/album/album'));
		}
		else
		{
			$this->error("不能重复提交",U('admin/album/album_add'));
		}

	}


	public function album_edit()
	{
		if(intval(get("album_id"))>0)
		{
			$data=M("album")->where("album_id=".intval(get("album_id")))->find();
			$this->assign("data",$data);
			
			
			
			$this->assign("page_title","修改相册");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function album_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["album_id"]=post("album_id");
			$data["album_name"]=post("album_name");
			$data["album_sort"]=post("album_sort");
			
			$list=M("album")->save($data);
			$this->success("修改成功",U('admin/album/album'));			
		}
		else
		{
			$this->error("不能重复提交",U('admin/album/album'));
		}

	}

	public function album_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("album")->where("album_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function album_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_album set album_state=1 where album_id=".$ids_arr[$i]." ");
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

	public function album_detail()
	{
		if(intval(get("album_id"))>0)
		{
			$data=M("album")->where("album_id=".intval(get("album_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["album_name"]."相册");
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