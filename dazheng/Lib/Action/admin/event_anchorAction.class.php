<?php
/**
 *    #Case		bwvip
 *    #Page		EventAction.class.php (赛事)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-05-28
 */
class event_anchorAction extends AdminAuthAction
{
	public function _initialize()
	{
		parent::_initialize();
	}

	public function anchor()
	{
		$list=D("event_anchor")->anchor_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","赛事播音");
    	$this->display();
	}

	public function anchor_add()
	{

		/* import("@.ORG.editor");  //导入类
		$event_editor=new editor("400px","700px",$data['event_content'],"event_content[]");     //创建一个对象
		$a=$event_editor->createEditor();   //返回编辑器
		$b=$event_editor->usejs();             //js代码
		$this->assign('usejs',$b);     //输出到html
		$this->assign('editor',$a);
		$this->assign('event_ad_editor',$a);
		
		$app_list=select_field(1,"select");
		$this->assign("app_list",$app_list);
		
		$event_years = select_dict(18);
		$this->assign("event_years",$event_years); */

		$this->assign("page_title","添加赛事播音");
    	$this->display();
	}
	

	
	public function anchor_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["uid"]=post("uid");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			$data["event_anchor_name"]=post("event_anchor_name");
			
			$data["event_anchor_sort"]=post("event_anchor_sort");
			$data["event_anchor_addtime"]=time();
			
			$uploadinfo=upload_file("upload/anchor/");
				
			foreach($uploadinfo as $key=>$val){
				$uploadinfo[$val['up_name']] = $val;
				unset($uploadinfo[$key]);
			}
			if(!empty($uploadinfo["event_anchor_pic"]))
			{
				$data["event_anchor_pic"]=$uploadinfo["event_anchor_pic"]["savepath"] . $uploadinfo["event_anchor_pic"]["savename"];
			}
			$list=M("event_anchor")->add($data);
			$this->success("添加成功",U('admin/event_anchor/anchor'));
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}


	public function anchor_edit()
	{
		if(intval(get("event_anchor_id"))>0)
		{
			$data=M("event_anchor")->where("event_anchor_id=".intval(get("event_anchor_id")))->find();
			$this->assign("data",$data);
			$this->assign("page_title","修改赛事播音");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}
	
	

	public function anchor_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["event_anchor_id"]=post("event_anchor_id");
			$data["uid"]=post("uid");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			$data["event_anchor_name"]=post("event_anchor_name");
			$data["event_anchor_sort"]=post("event_anchor_sort");
			
			$uploadinfo=upload_file("upload/anchor/");
				
			foreach($uploadinfo as $key=>$val){
				$uploadinfo[$val['up_name']] = $val;
				unset($uploadinfo[$key]);
			}
			if(!empty($uploadinfo["event_anchor_pic"]))
			{
				$data["event_anchor_pic"]=$uploadinfo["event_anchor_pic"]["savepath"] . $uploadinfo["event_anchor_pic"]["savename"];
			}
			
			
			$list=M("event_anchor")->save($data);
			$this->success("修改成功",U('admin/event_anchor/anchor',array('event_anchor_id'=>$data['event_anchor_id'])));
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}

	public function anchor_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("event_anchor")->where("event_anchor_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function anchor_detail()
	{
		if(intval(get("event_anchor_id"))>0)
		{
			$data=M("event_anchor")->where("event_anchor_id=".intval(get("event_anchor_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title","赛事播音详细");
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