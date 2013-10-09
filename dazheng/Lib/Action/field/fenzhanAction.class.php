<?php
/**
 *    #Case		bwvip
 *    #Page		FenzhanAction.class.php (分站)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-05-28
 */
class fenzhanAction extends field_publicAction
{

	public function _initialize()
	{
		parent::_initialize();
	}

	public function fenzhan()
	{
		$event_info=M("event")->where("event_id=".intval(get("event_id")))->find();
		$this->assign('event_name',$event_info['event_name']);
		$this->assign('event_id',$event_info['event_id']);
		
		/* $event=D('event')->event_select_pro(" and field_uid='".$_SESSION['field_uid']."' ");
		$this->assign('event',$event['item']); */
		
		$list=D("fenzhan_tbl")->fenzhan_list_pro();
		
		$field_list_tmp=M()->table('pre_common_field')->select();
		$field_list = array();
		foreach($field_list_tmp as $key=>$val)
		{
			$field_list[$val['uid']] = $val['fieldname'];
		}
		unset($field_list_tmp);
		$this->assign('field_list',$field_list);
		
		
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
		
		$this->assign('fenzhan_on',1);

		$this->assign("page_title","分站");
    	$this->display();
	}

	public function fenzhan_add()
	{
		$event=D('event')->event_select_pro(" and field_uid='".$_SESSION['field_uid']."' ");
		$this->assign('event',$event['item']);
		 
		$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".get("event_id")."' "); 
		$this->assign('fenzhan',$fenzhan['item']);
		
		
		$area=M()->query("select id,name from pre_common_district where  upid=0");
		$this->assign('area',$area);
		
		$this->assign('fenzhan_on',1);
		$event_info=M("event")->where("event_id=".intval(get("event_id")))->find();
		$this->assign('event_name',$event_info['event_name']);
		$this->assign('event_id',$event_info['event_id']);
		$this->assign('event_type',$event_info['event_type']);
		
		$this->assign("page_title","添加分站");
    	$this->display();
	}

	public function fenzhan_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["event_id"]=post("event_id");
			$data["fenzhan_name"]=post("fenzhan_name");
			$data["fenzhan_rule"]=post("fenzhan_rule");
			$data["field_id"]=post("field_id");
			//获取前九洞			 
			$data["fenzhan_a"]=post("fenzhan_a"); 			 
			//获取前九洞		 			
			$data["fenzhan_b"]=post("fenzhan_b"); 
			
			$data["fenzhan_lun"]=post("fenzhan_lun");
			$data["parent_id"]=post("parent_id");
			$data["field_uid"]=$_SESSION['field_uid'];
			$data["year"]=post("year");
			
			if($_FILES["timepic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/fenzhan/");
				$data["timepic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			if(post("starttime"))
			{
				$data["starttime"]=strtotime(post("starttime")." 00:00:01");
			}
			if(post("endtime"))
			{
				$data["endtime"]=strtotime(post("endtime")." 23:59:59");
			}
			  
			
			$data["orderby"]=post("orderby");
			$data["is_delete"]=post("is_delete");
			$data["addtime"]=time();
			
			$list=M("fenzhan")->add($data);
			
			$this->success("添加成功",U('field/fenzhan/fenzhan',array('event_id'=>$data['event_id'])));
		}
		else
		{
			$this->error("不能重复提交",U('field/fenzhan/fenzhan_add'));
		}

	}


	public function fenzhan_edit()
	{
		if(intval(get("fenzhan_id"))>0)
		{	
			$event=D('event')->event_select_pro(" and field_uid='".$_SESSION['field_uid']."' ");
			$this->assign('event',$event['item']);
			
		
			$data=M("fenzhan")->where("fenzhan_id=".intval(get("fenzhan_id")))->find();
			$this->assign("data",$data); 
			
			$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".$data['event_id']."' "); 
			 
	    	$this->assign('fenzhan',$fenzhan['item']); 		
			 
			
			$area=M()->query("select id,name from pre_common_district where  upid=0");
			$this->assign('area',$area);
			
			$this->assign('fenzhan_on',1);
			$this->assign('fenzhan_on',1);
			$event_info=M("event")->where("event_id=".intval(get("event_id")))->find();
			$this->assign('event_id',intval(get("event_id")));
			$this->assign('event_name',$event_info['event_name']);
			$this->assign('event_type',$event_info['event_type']);
			$this->assign("page_title","修改分站");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function fenzhan_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["fenzhan_id"]=post("fenzhan_id");
			$data["fenzhan_name"]=post("fenzhan_name");
			$data["fenzhan_rule"]=post("fenzhan_rule");			
			$data["fenzhan_lun"]=post("fenzhan_lun");			
			//获取前九洞			 
			$data["fenzhan_a"]=post("fenzhan_a"); 			 
			//获取前九洞		 			
			$data["fenzhan_b"]=post("fenzhan_b"); 
			
			$data["year"]=post("year");
			if($_FILES["timepic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/fenzhan/");
				$data["timepic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			if(post("starttime"))
			{
				$data["starttime"]=strtotime(post("starttime")." 00:00:01");
			}
			if(post("parent_id"))
			{				
			    $data["parent_id"]=post("parent_id");
			}else
		    {
			   $data["parent_id"]=post("parent_id1");
			}
			if(post("field_id"))
			{				
			    $data["field_id"]=post("field_id");
			}else
		    {
			   $data["field_id"]=post("field_id1");
			}
			
			if(post("endtime"))
			{
				$data["endtime"]=strtotime(post("endtime")." 23:59:59");
			}
			$data["orderby"]=post("orderby");
			$data["is_delete"]=post("is_delete");
			 
			$data["field_uid"]=$data["field_id"];
			 
			$list=M("fenzhan")->save($data);
			
			$this->success("修改成功",U('field/fenzhan/fenzhan',array('event_id'=>post('event_id'))));
			
		}
		else
		{
			$this->error("不能重复提交",U('field/fenzhan/fenzhan',array('event_id'=>post('event_id'))));
		}

	}

	public function fenzhan_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("fenzhan")->where("fenzhan_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function fenzhan_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_fenzhan set fenzhan_state=1 where fenzhan_id=".$ids_arr[$i]." ");
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

	public function fenzhan_detail()
	{
		if(intval(get("fenzhan_id"))>0)
		{
			$data=M("fenzhan")->where("fenzhan_id=".intval(get("fenzhan_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["fenzhan_name"]."分站");
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
	
	public function fenzhan_lun()
	{
		$this->assign('fenzhan_lun_on',1);
		
		$fenzhan_id=get("fenzhan_id");
		if($fenzhan_id)
		{
			$fenzhan_info=M()->query("select fenzhan_lun from tbl_fenzhan where fenzhan_id='".$fenzhan_id."'");
			$fenzhan_lun=$fenzhan_info[0]['fenzhan_lun'];
		}
		for($i=0; $i<$fenzhan_lun; $i++)
		{
			$lun_arr[]=$i+1;
		}
		
		$this->assign('lun_arr',$lun_arr);
		
		$event_info=M("event")->where("event_id=".intval(get("event_id")))->find();
		$this->assign('event_name',$event_info['event_name']);
		$this->assign('event_id',$event_info['event_id']);
		
		$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".get("event_id")."' ");
		$this->assign('fenzhan',$fenzhan['item']);
		
		$fenzu=D('fenzu')->fenzu_list_pro(" and fenzhan_id='".get("fenzhan_id")."' ");
		$this->assign('fenzu',$fenzu['item']);
		
		$list=D("event_apply")->event_apply_list_pro(' and event_apply_state=1 '); 

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","分轮管理");
    	$this->display();
	}
	
	public function batch_lun()
	{
		$fenzhan_id=get("fenzhan_id");
		if($fenzhan_id)
		{
			$fenzhan_info=M()->query("select fenzhan_lun from tbl_fenzhan where fenzhan_id='".$fenzhan_id."'");
			$fenzhan_lun=$fenzhan_info[0]['fenzhan_lun'];
		}
		for($i=0; $i<$fenzhan_lun; $i++)
		{
			$lun_arr[]=$i+1;
		}
		$this->assign('lun_arr',$lun_arr);
		
		$this->display();
	}
	
	public function batch_lun_action()
	{
		if(post('lun_id'))
		{
			$arr=explode(",",post('ids'));
			for($i=0; $i<count($arr); $i++)
			{
				if($arr[$i])
				{
					//获取报名信息
					$event_apply_id=$arr[$i];
					$baoming_info=M()->query("select * from tbl_event_apply where event_apply_id='".$event_apply_id."' ");
					if($baoming_info[0]["uid"])
					{
						
						$if_have=M()->query("select * from tbl_lun_mingxi where uid='".$baoming_info[0]['uid']."' and fenzhan_id='".post("fenzhan_id")."' and lun_id='".post("lun_id")."' ");
						if(!$if_have[0]['lun_mingxi_id'])
						{
							$data["uid"]=$baoming_info[0]['uid'];
							$data["lun_id"]=post("lun_id");
							$data["event_id"]=post("event_id");
							$data["fenzhan_id"]=post("fenzhan_id");
							$data["field_id"]=post("field_id");
							$data["lun_mingxi_addtime"]=time();
							$res=M("lun_mingxi")->add($data);
						}
						
					}
					
				}
			}
	
			msg_dialog_tip('succeed^分轮成功');
		}
		else
		{
			msg_dialog_tip('error^分轮不能为空');
		}

	}
	
	
	public function batch_lun_del()
	{
		$fenzhan_id=get("fenzhan_id");
		if($fenzhan_id)
		{
			$fenzhan_info=M()->query("select fenzhan_lun from tbl_fenzhan where fenzhan_id='".$fenzhan_id."'");
			$fenzhan_lun=$fenzhan_info[0]['fenzhan_lun'];
		}
		for($i=0; $i<$fenzhan_lun; $i++)
		{
			$lun_arr[]=$i+1;
		}
		$this->assign('lun_arr',$lun_arr);
		
		$this->display();
	}
	
	
	public function batch_lun_del_action()
	{
		if(post('lun_id'))
		{
			$arr=explode(",",post('ids'));
			for($i=0; $i<count($arr); $i++)
			{
				if($arr[$i])
				{
					//获取报名信息
					$event_apply_id=$arr[$i];
					$baoming_info=M()->query("select * from tbl_event_apply where event_apply_id='".$event_apply_id."' ");
					if($baoming_info[0]["uid"])
					{
						$if_have=M()->query("delete from tbl_lun_mingxi where uid='".$baoming_info[0]['uid']."' and fenzhan_id='".post("fenzhan_id")."' and lun_id='".post("lun_id")."' ");						
					}
					
				}
			}
	
			msg_dialog_tip('succeed^操作成功');
		}
	
	}
	
	
	public function fenzhan_user()
	{
		$this->assign('fenzhan_user_on',1);
		
		$fenzhan_id=get("fenzhan_id");
		 
		 
		$event_info=M("event")->where("event_id=".intval(get("event_id")))->find();
		$this->assign('event_name',$event_info['event_name']);
		$this->assign('event_id',$event_info['event_id']);
		$this->assign('event_type',$event_info['event_type']);
		
		//$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".get("event_id")."' ");
		
		$fenzhan=M('fenzhan')->where("event_id='".get("event_id")."'")->select();
		$fenzhan_info = array();
		foreach($fenzhan as $key=>$val) {
			$fenzhan_info[$val['fenzhan_id']] = $val;
			$default_fenzhan_id = $val['fenzhan_id'];
		}
		unset($fenzhan);
		
		$this->assign('fenzhan',$fenzhan_info);
		
		//$fenzu=D('fenzu')->fenzu_list_pro(" and fenzhan_id='".get("fenzhan_id")."' ",999,"lun desc,fenzhan_num asc");
		//$this->assign('fenzu',$fenzu['item']);
		//$fenzu_list=M('fenzu')->where("event_id='".get("event_id")."'")->select();
		//var_dump($fenzu_list);
		//foreach($fenzu_list as $key=>$val) {
		//	$fenzu[$val['fenzhu_id']]=$val;
		//}
		//unset($fenzu_list);
		//$this->assign('fenzu',$fenzu);
		if(empty($fenzhan_id))
		{
			$fenzhan_id = $default_fenzhan_id;
		}
		$event_apply_list=M('event_apply')->where("fenzhan_id='{$fenzhan_id}' and parent_id='0'")->select();//query("select * from tbl_event_apply where fenzhan_id='".$fenzhan_id."' and parent_id='0' order by fenzu_id asc ");
		//echo M()->getLastSql();die;
		//var_dump($event_apply_list);
		
		$parent_apply_list = array();
		foreach($event_apply_list as $key=>$val)
		{
			$parent_apply_list[$val['event_apply_id']] = $val;
		}
		unset($event_apply_list);
		$this->assign("parent_apply_list",$parent_apply_list);
		//$list=D("fenzu_mingxi")->fenzu_mingxi_list_pro(" ");
		if($fenzhan_id)
		{
			$list=M()->query("select * from tbl_baofen where fenzhan_id='".$fenzhan_id."' order by fenzu_id asc ");
		}else
		{		
			//$list=M()->query("select * from tbl_baofen where event_id='".get("event_id")."' order by baofen_id desc ");
		}
		$this->assign("fenzhan_id",$fenzhan_id);
		$this->assign("list",$list);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",count($list));

		$this->assign("page_title","赛事报名");
    	$this->display();
	}
	
	
	
	
	public function batch_fenzu()
	{
		$fenzu=D('fenzu')->fenzu_list_pro(" and fenzhan_id='".get("fenzhan_id")."' ");
		$this->assign('fenzu',$fenzu['item']);
		
		$this->display();
	}
	
	public function batch_fenzu_del()
	{
		$fenzu=D('fenzu')->fenzu_list_pro(" and fenzhan_id='".get("fenzhan_id")."' ");
		$this->assign('fenzu',$fenzu['item']);
		
		$this->display();
	}
	
	
	
	public function batch_fenzu_action()
	{
		if(post('fenzu_id'))
		{
				$arr=explode(",",post('ids'));
				$fenzu_info=M("fenzu")->where(" fenzu_id='".post('fenzu_id')."' ")->find();
				for($i=0; $i<count($arr); $i++)
				{
					if($arr[$i] && $fenzu_info['fenzu_id'])
					{
						
						//获取报名信息
						$event_apply_id=$arr[$i];
						$baoming_info=M()->query("select * from tbl_event_apply where event_apply_id='".$event_apply_id."' ");
						if($baoming_info[0]["uid"])
						{
							$del=M()->query("delete from tbl_fenzu_mingxi where uid='".$baoming_info[0]['uid']."' and fenzhan_id='".$fenzu_info["fenzhan_id"]."'  and lun='".$fenzu_info["lun"]."' ");
							
							$data["uid"]=$baoming_info[0]['uid'];
							$data["realname"]=$baoming_info[0]['event_apply_realname'];
							$data["event_id"]=$fenzu_info["event_id"];
							$data["fenzhan_id"]=$fenzu_info["fenzhan_id"];
							$data["field_id"]=$fenzu_info["field_id"];
							$data["lun"]=$fenzu_info["lun"];
							$data["fenzu_id"]=$fenzu_info["fenzu_id"];
							$data["fenzu_number"]=$fenzu_info["fenzu_number"];
							$data["am_pm"]=$fenzu_info["fenzu_ampm"];
							$data["start_time"]=$fenzu_info["fenzu_start_time"];
							$data["tee"]=$fenzu_info["fenzu_tee"];
							$data["chadian"]=$baoming_info[0]['event_apply_chadian'];
							
							$data["addtime"]=time();
							$res=M("fenzu_mingxi")->add($data);
							
						}
						
					}
				}
	
			msg_dialog_tip('succeed^分组成功');
		}
		else
		{
			msg_dialog_tip('error^分组不能为空');
		}

	}
	
	public function batch_fenzu_del_action()
	{
		if(post('fenzu_id'))
		{
				$arr=explode(",",post('ids'));
				$fenzu_info=M("fenzu")->where(" fenzu_id='".post('fenzu_id')."' ")->find();
				for($i=0; $i<count($arr); $i++)
				{
					if($arr[$i] && $fenzu_info['fenzu_id'])
					{
						
						//获取报名信息
						$event_apply_id=$arr[$i];
						$baoming_info=M()->query("select * from tbl_event_apply where event_apply_id='".$event_apply_id."' ");
						if($baoming_info[0]["uid"])
						{
							$del=M()->query("delete from tbl_fenzu_mingxi where uid='".$baoming_info[0]['uid']."' and fenzhan_id='".$fenzu_info["fenzhan_id"]."'  and lun='".$fenzu_info["lun"]."' ");
						}
						
					}
				}
	
			msg_dialog_tip('succeed^操作成功');
		}
		else
		{
			msg_dialog_tip('error^分组不能为空');
		}

	}
	
	//启动报分 判断是否已经启动
	public function fenzhan_qidongbaofen_action()
	{
		$fenzhan_id=post("ids");
		if($fenzhan_id)
		{
			$if_open=M()->query("select count(baofen_id) as num from tbl_baofen where fenzhan_id='".$fenzhan_id."' ");
			if($if_open[0]['num'])
			{
				echo "error^报分已经启动，请不在重复操作";
			}
			else
			{
				$fenzu_list=M("fenzu_mingxi")->where(" fenzhan_id='".$fenzhan_id."' ")->order(" addtime asc ")->select();
				for($i=0; $i<count($fenzu_list); $i++)
				{
					if($fenzu_list[$i]['fenzu_mingxi_id'])
					{
						$data["uid"]=$fenzu_list[$i]['uid'];
						$data["realname"]=$fenzu_list[$i]['realname'];
						$data["event_id"]=$fenzu_list[$i]['event_id'];
						$data["fenzhan_id"]=$fenzu_list[$i]['fenzhan_id'];
						$data["field_id"]=$fenzu_list[$i]['field_id'];
						$data["fenzu_id"]=$fenzu_list[$i]['fenzu_id'];
						$data["tee"]=$fenzu_list[$i]['tee'];
						$data["lun"]=$fenzu_list[$i]['lun'];
						$data["is_out"]=0;
						$data["is_end"]=0;
						$data["start_time"]=$fenzu_list[$i]['start_time'];
						$res=M("baofen")->add($data);
					}
					
				}
				echo "succeed^启动成功，现在可以报分了";
			}
		}
		else
		{
			echo "error^请选择一个分站 ";
		}
		
	}
	
	
	//重新启动报分  旧报分数据直接被删除
	public function fenzhan_chongxinqidongbaofen_action()
	{
		$fenzhan_id=post("ids");
		if($fenzhan_id)
		{
			$fenzhan_info=M("fenzhan")->where(" fenzhan_id='".$fenzhan_id."'")->find();
			$del=M()->query("delete from tbl_baofen where fenzhan_id='".$fenzhan_id."' ");
			$del2=M()->query("delete from tbl_score where fenzhan_id='".$fenzhan_id."' ");
			$fenzu_list=M("fenzu_mingxi")->where(" fenzhan_id='".$fenzhan_id."' ")->order(" addtime asc ")->select();
			for($i=0; $i<count($fenzu_list); $i++)
			{
				if($fenzu_list[$i]['fenzu_mingxi_id'])
				{
					$data["uid"]=$fenzu_list[$i]['uid'];
					$data["realname"]=$fenzu_list[$i]['realname'];
					$data["event_id"]=$fenzu_list[$i]['event_id'];
					$data["fenzhan_id"]=$fenzu_list[$i]['fenzhan_id'];
					$data["field_id"]=$fenzhan_info['field_id'];
					$data["fenzu_id"]=$fenzu_list[$i]['fenzu_id'];
					$data["tee"]=$fenzu_list[$i]['tee'];
					$data["lun"]=$fenzu_list[$i]['lun'];
					$data["is_out"]=0;
					$data["is_end"]=0;
					$data["start_time"]=$fenzu_list[$i]['start_time'];
					$res=M("baofen")->add($data);
				}
				
			}
			echo "succeed^启动成功，现在可以报分了";
		}
		else
		{
			echo "error^请选择一个分站 ";
		}
		
	}
	
	//报分导入成绩卡
	public function baofen_to_score()
	{
		$fenzhan_id=post('ids');
		if($fenzhan_id)
		{
			$fenzhan_info=M()->query("select * from tbl_fenzhan where fenzhan_id='".$fenzhan_id."' ");
			
			$qc_id=$fenzhan_info[0]['field_id'];
			$sid=$fenzhan_info[0]['event_id'];
			$fz_id=$fenzhan_info[0]['fenzhan_id'];
			$lun=$fenzhan_info[0]['lun'];
			$qc_par_result = M()->query( " select `par` from pre_common_field where uid='$qc_id'" );
			
			$par = explode ( ',', $qc_par_result[0]['par'] );
			$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
			$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
			$PTL = $POUT + $PIN;
			$arr ['par'] = $par [0] . '|' . $par [1] . '|' . $par [2] . '|' . $par [3] . '|' . $par [4] . '|' . $par [5] . '|' . $par [6] . '|' . $par [7] . '|' . $par [8] . '|' . $POUT . '|' . $par [9] . '|' . $par [10] . '|' . $par [11] . '|' . $par [12] . '|' . $par [13] . '|' . $par [14] . '|' . $par [15] . '|' . $par [16] . '|' . $par [17] . '|' . $PIN . '|' . $PTL;
				$i=0;
			
			$sql="select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin  from tbl_baofen where cave_1>0 and cave_2>0  and cave_3>0  and cave_4>0  and cave_5>0  and cave_6>0  and cave_7>0  and cave_8>0  and cave_9>0  and cave_10>0  and cave_11>0  and cave_12>0  and cave_13>0  and cave_14>0  and cave_15>0  and cave_16>0  and cave_17>0  and cave_18>0 and fenzhan_id='".$fenzhan_id."' and total_score<1000";
			$row=M()->query($sql);
		
			if(count($row)>0)
			{
				for($j=0; $j<count($row); $j++)
				{

					$i=$i+1;
					//$nblist [] = $row;
					$total_score = $row[$j]['lin'] + $row[$j]['lout'];
					$arr ['total_score'] = $total_score;
					for($i = 1; $i <= 21; $i ++)
					{
						if ($i == '10')
						{
							$data [$i] = $row[$j]['lout'];
						}
						elseif ($i == '20')
						{
							$data [$i] = $row[$j]['lin'];
						} 
						elseif ($i == '21')
						{
							$data [$i] = $total_score;
						}
						elseif ($i > 9)
						{
							$data [$i] = $row[$j]['cave_' . ($i - 1)];
						}
						else
						{
							$data [$i] = $row[$j]['cave_' . $i];
						}
					
					}
					$par = explode ( '|', $arr ['par'] );
					
					//初始化
					$total_eagle = 0;
					//birdie  
					$total_birdie = 0;
					//E  
					$total_evenpar = 0;
					//bogi  
					$total_bogi = 0;
					//doubles 
					$total_doubles = 0;
					
							for($i = 1; $i <= 21; $i ++)
							{
								$data1 [$i] = Getpar ( $data [$i] - $par [$i - 1] );
								if ($i != '10' && $i != '20' && $i != '21') {
									//eagle
									Getpar ( $data [$i] - $par [$i - 1] ) == '-2' ? $total_eagle ++ : '';
									//birdie 
									Getpar ( $data [$i] - $par [$i - 1] ) == '-1' ? $total_birdie ++ : '';
									//E 
									Getpar ( $data [$i] - $par [$i - 1] ) == 'E' ? $total_evenpar ++ : '';
									//bogi 
									Getpar ( $data [$i] - $par [$i - 1] ) == '+1' ? $total_bogi ++ : '';
									//doubles
									Getpar ( $data [$i] - $par [$i - 1] ) == '+2' ? $total_doubles ++ : '';
								}
							}
					
					$str = implode ( '|', $data );
					$str1 = implode ( '|', $data1 );
					$arr ['score'] = $str;
					$arr ['tee'] = $row[$j]['tee'];
					$arr ['pars'] = $str1;
					$arr ['total_pushs'] = $total_score;
					$arr ['total_avepushs'] = floor ( $total_score / 18 );
					$arr ['total_eagle'] = $total_eagle;
					$arr ['total_birdie'] = $total_birdie;
					$arr ['total_evenpar'] = $total_evenpar;
					$arr ['total_bogi'] = $total_bogi;
					$arr ['total_doubles'] = $total_doubles;
					$arr ['dateline'] = $row[$j]['start_time'];
					$arr ['event_id'] = $sid;
					$arr ['field_id'] = $row[$j]['field_id'];
					$arr ['uid'] = $row[$j]['uid'];
					$arr ['ismine'] = '0';
					$arr ['status'] = '2'; //状态通过
					$arr ['member'] = '1';
					$arr ['onlymark'] = $onlymark;
					$arr ['lun'] = $row[$j]['lun'];
					$arr ['fenzhan_id'] = $fz_id;
					$arr ['rtype'] = '1';
					$arr ['baofen_id'] = $row[$j]['baofen_id'];
					$arr ['fenzu_id'] = $row[$j]['fenzu_id'];
					$arr ['realname'] = $row[$j]['realname'];
					$arr ['addtime'] = time ();
					//$row = DB::insert('common_cave_', $arr);	  
						$uid=$row[$j]['uid'];
						if($i<=40)
						{
							$jf=20+$jfarray[$i]; 
						}
						else
						{
						
							$jf=20;
						}
						

					$if_have=M()->query("select score_id from tbl_score where fenzhan_id='".$fenzhan_id."' and uid='".$row[$j]['uid']."' and lun='".$row[$j]['lun']."' ");
					if(!$if_have[0]['score_id'])
					{
						if($row[$j]['uid'])
						{
							$res = M("score")->add($arr);
							$up=M()->query("update tbl_score set baofen_id='".$row[$j]['baofen_id']."' where score_id='".$res."' ");
						}
						
					}
					echo 'succeed^操作成功';
				
				}
			}
			else
			{
				echo 'error^报分数据不完整，请认真填写';
			}
		}
		
	}
	
	
	public function jinji()
	{
		
		$event_info=M("event")->where("event_id=".intval(get("event_id")))->find();
		$this->assign('event_name',$event_info['event_name']);
		$this->assign('event_id',$event_info['event_id']);
		
		$this->assign('fenzhan_on',1);
		
		$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".get("event_id")."' "); 
		$this->assign('fenzhan',$fenzhan['item']);
		
		
		$this->display();
	}
	
	
	
	public function jinji_action()
	{
		
		if(post('next_fenzhan_id') && post('fenzhan_id') && post('jinji_par'))
		{
		
			//del old
			$ress=M()->query("delete from tbl_event_apply where fenzhan_id='".post('next_fenzhan_id')."' ");
			$sql="insert tbl_event_apply(parent_id,event_id,fenzhan_id,field_uid,uid,event_user_id,event_apply_realname,event_apply_sex,event_apply_card,event_apply_chadian,event_apply_state,event_apply_addtime) select parent_id,event_id,".post('next_fenzhan_id').",field_uid,uid,event_user_id,event_apply_realname,event_apply_sex,event_apply_card,event_apply_chadian,event_apply_state,event_apply_addtime from tbl_event_apply where fenzhan_id ='".post('fenzhan_id')."' ";
			$res=M()->query($sql);
			//echo $sql;
			
			
			$list=M()->query("select event_apply_id,event_user_id from tbl_event_apply where fenzhan_id='".post('next_fenzhan_id')."'  ");
			for($i=0; $i<count($list); $i++)
			{
				$ju_par=M()->query("select baofen_id,total_sum_ju from tbl_baofen where event_user_id='".$list[$i]['event_user_id']."' and fenzhan_id='".post('fenzhan_id')."' and total_sum_ju<='".post('jinji_par')."' and status>=0 order by lun desc limit 1  ");
				if($ju_par[0]['baofen_id'])
				{
					$up=M()->query("update tbl_event_apply set total_sum_ju='".$ju_par[0]['total_sum_ju']."' where event_apply_id='".$list[$i]['event_apply_id']."' ");
					//echo "update tbl_event_apply set total_sum_ju='".$ju_par[0]['total_sum_ju']."' where event_apply_id='".$list[$i]['event_apply_id']."' ";
					//echo "<hr>";
				}
				else
				{
					$up=M()->query("delete from tbl_event_apply where event_apply_id='".$list[$i]['event_apply_id']."' ");
					//echo "delete from tbl_event_apply where event_apply_id='".$list[$i]['event_apply_id']."' ";
					//echo "<hr>";
				}
				
			}
			
			
			$fenzhan_info=M()->query("select fenzhan_id,fenzhan_lun from tbl_fenzhan where fenzhan_id='".post('next_fenzhan_id')."' ");
			$up=M()->query("insert tbl_baofen(uid,event_user_id,realname,event_id,sid,par,fenzhan_id,field_id,fenzu_id,zong_score,total_sum_ju,addtime,dateline,source,lun,status ) select uid,event_user_id,realname,event_id,sid,par,".post('next_fenzhan_id').",field_id,fenzu_id,zong_score,total_sum_ju,addtime,dateline,source,".$fenzhan_info[0]['fenzhan_lun'].",0 from tbl_baofen where total_sum_ju<='".post('jinji_par')."' and fenzhan_id='".post('fenzhan_id')."' and status>=0 ");
			
			$ress=M()->query("update tbl_baofen set status='-1' where total_sum_ju>'".post('jinji_par')."' and fenzhan_id='".post('fenzhan_id')."' and status>=0 ");
			
			$this->success("处理成功",U('field/fenzhan/fenzhan',array('event_id'=>post('event_id'))));
			
		}
		else
		{
			$this->error("参数不完整");
		}
		
	}
	
	
	public function baofen_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("baofen")->where("baofen_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}
	

	

}
?>