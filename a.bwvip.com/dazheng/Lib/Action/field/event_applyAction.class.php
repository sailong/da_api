<?php
/**
 *    #Case		bwvip
 *    #Page		Event_applyAction.class.php (赛事报名)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-05-28
 */
class event_applyAction extends field_publicAction
{

	public function _initialize()
	{
		parent::_initialize();
	}

	public function event_apply()
	{
		$this->assign('event_apply_on',1);
		
		$event_info=M("event")->where("event_id=".intval(get("event_id")))->find();
		$this->assign('event_name',$event_info['event_name']);
		$this->assign('event_id',$event_info['event_id']);
		
		$list=D("event_apply")->event_apply_list_pro(" and parent_id='0'");
		
		$baofen_list = M('baofen')->where("event_id='".intval(get("event_id"))."' and parent_id='0'")->select();
		//echo '<pre>';
		//var_dump($baofen_list);die;
		$baofen_apply_list = array();
		$baofen_apply_ids = array();
		foreach($baofen_list as $key=>$val){
			$baofen_apply_list[$val['event_apply_id']] = $val;
			$baofen_apply_ids[$val['event_apply_id']] = $val['event_apply_id'];
		}
		//var_dump($baofen_list);die;
		foreach($list["item"] as $key1=>&$val1){
			if(in_array($val1['event_apply_id'],$baofen_apply_ids)){
				$list["item"][$key1]['nofenzu'] = 'false';
				$list["item"][$key1]['fenzu_id'] = "第".$baofen_apply_list[$val1['event_apply_id']]['fenzu_id']."组";
			}else{
				$list["item"][$key1]['nofenzu'] = 'true';
				$list["item"][$key1]['fenzu_id'] = '暂无分组';
			}
		}
		
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","赛事报名");
    	$this->display();
	}

	public function event_apply_add()
	{
	    $event_id = get('event_id');
		$event=D('event')->event_select_pro(" and field_uid='".$_SESSION['field_uid']."' and event_id='{$event_id}'");
		$this->assign('event',reset($event['item']));
		//echo '<pre>';
		//var_dump(reset($event['item']));
		$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and field_uid='".$_SESSION['field_uid']."' and event_id='{$event_id}' ");
		$this->assign('fenzhan',$fenzhan['item']);
		
		
		$this->assign("page_title","添加赛事报名");
    	$this->display();
	}

	public function event_apply_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{	
			$event_type = post("event_type");
			$real_name_arr = $_POST["event_apply_realname"];
			$event_apply_sex_arr = $_POST["event_apply_sex"];
			$event_apply_card_arr = $_POST["event_apply_card"];
			$event_apply_chadian_arr = $_POST["event_apply_chadian"];
			
			$data["event_id"]=post("event_id");
			$data["parent_id"] = 0;
			$data["uid"]=post("uid");
			$data["field_uid"]=$_SESSION['field_uid'];
			$data["field_id"]=$_SESSION['field_uid'];
			$data["fenzhan_id"]=post("fenzhan_id");
			$data["event_apply_state"]=post("event_apply_state");
			$data["event_apply_addtime"]=time();
			if($event_type == "T"){
				$tuanti_flag = post("tuanti_name");
				$tuanti_flag = explode('_|_',$tuanti_flag);
				$data["tuanti_flag"]=$tuanti_flag[0];
				$data["event_apply_realname"]=$tuanti_flag[1];
			}else{
				$data["event_apply_realname"]=$real_name_arr[0];
				$data["event_apply_sex"]=post("event_apply_sex_0");
				$data["event_apply_card"]=$event_apply_card_arr[0];
				$data["event_apply_chadian"]=$event_apply_chadian_arr[0];
			}
			
			$parent_id=M("event_apply")->add($data);
			if($event_type == "T"){
				foreach($real_name_arr as $key=>$val){
					$data["event_apply_realname"]=$real_name_arr[$key];
					$data["event_apply_sex"]=post("event_apply_sex_".$key);
					$data["event_apply_card"]=$event_apply_card_arr[$key];
					$data["event_apply_chadian"]=$event_apply_chadian_arr[$key];
					$data["parent_id"]=$parent_id;
					M("event_apply")->add($data);
				}
			}
			//$list=M("event_apply")->add($data);
		
			$this->success("添加成功",U('field/event_apply/event_apply',array('event_id'=>$data['event_id'])));
			
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}


	public function event_apply_edit()
	{
		if(intval(get("event_apply_id"))>0)
		{
			
			$event_id = get('event_id');
			$event_info=D('event')->event_select_pro(" and field_uid='".$_SESSION['field_uid']."' and event_id='{$event_id}'");
			//$this->assign('event',$event['item']);
			$event_info = reset($event_info['item']);
			//echo '<pre>';
			//var_dump($event['item']);
			$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and field_uid='".$_SESSION['field_uid']."'");
			$this->assign('fenzhan',$fenzhan['item']);
			
			$data=M("event_apply")->where("event_apply_id=".intval(get("event_apply_id")))->find();
			$event_apply_list = array();
			if($event_info['event_type'] == 'T'){
				$event_apply_list=M("event_apply")->where("parent_id=".intval(get("event_apply_id")))->select();
			}else{
				$event_apply_list = $data;
			}
			$this->assign('people_num',count(event_apply_list));
			$this->assign("data",$data);
			$this->assign("event_apply_list",$event_apply_list);
			$this->assign("event_type",$event_info['event_type']);
			$this->assign("page_title","修改赛事报名");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function event_apply_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			//echo '<pre>';
			//var_dump($_POST);die;
			$event_apply_ids = $_POST['event_apply_id'];
			$event_apply_realnames = $_POST['event_apply_realname'];
			$event_apply_cards = $_POST['event_apply_card'];
			$event_apply_chadians = $_POST['event_apply_chadian'];
			$event_apply_ids = $_POST['event_apply_id'];
			$data["event_apply_id"]=post("event_apply_id");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			$data["uid"]=post("uid");
			$data["event_apply_realname"]=post("event_apply_realname");
			$data["event_apply_sex"]=post("event_apply_sex");
			$data["event_apply_card"]=post("event_apply_card");
			$data["event_apply_chadian"]=post("event_apply_chadian");
			$data["event_apply_state"]=post("event_apply_state");
			
			$list=M("event_apply")->save($data);
			$this->success("修改成功",U('field/event_apply/event_apply',array('event_id'=>$data['event_id'])));
		
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}

	public function event_apply_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("event_apply")->where("event_apply_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function event_apply_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_event_apply set event_apply_state=1 where event_apply_id=".$ids_arr[$i]." ");
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
	
	
	public function event_apply_no_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_event_apply set event_apply_state=2 where event_apply_id=".$ids_arr[$i]." ");
			}
			if($res)
			{
				echo "succeed^操作成功";
			}
			else
			{
				echo "error^操作失败";
			}			
			
		}
	}

	public function event_apply_detail()
	{
		if(intval(get("event_apply_id"))>0)
		{
			$data=M("event_apply")->where("event_apply_id=".intval(get("event_apply_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["event_apply_name"]."赛事报名");
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