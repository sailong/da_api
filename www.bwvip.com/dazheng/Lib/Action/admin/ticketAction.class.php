<?php
/**
 *    #Case		bwvip
 *    #Page		TicketAction.class.php (门票)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-06
 */
class ticketAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function ticket()
	{
	
		$event_select=D('event')->event_select_pro(" ");
		$this->assign('event_select',$event_select['item']);

		
	
		$list=D("ticket")->ticket_list_pro(" ");
		/* $field_uid = get('field_uid');
		if($field_uid == '')
		{
			$field_uid = $_SESSION['field_uid'];
		}
		
		if($field_uid  != '')
		{
			$event_list = M('event')->where("field_uid='{$field_uid}'")->select();
		}
		else
		{
			$event_list = M('event')->select();
		} 
		*/
		
		$event_list = M('event')->where("event_id='{$event_id}'")->select();
		
		foreach($event_list as $key=>$val)
		{
			unset($event_list[$key]);
			$event_list[$val['event_id']]=$val['event_name'];
		}
		
		/* foreach($list["item"] as $key=>$val)
		{
			if(empty($event_list[$val['event_id']])){
				unset($list["item"][$key]);
			}
		} */
		
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
		
		$this->assign("event_list",$event_list);

		$this->assign("page_title","门票");
    	$this->display();
	}

	public function ticket_add()
	{
		
		$event_list=D('event')->event_select_pro(" ");
		$this->assign('event_list',$event_list['item']);

		
		import("@.ORG.editor");  //导入类
		$editor=new editor("400px","700px",$data["ticket_content"],"ticket_content");     //创建一个对象
		$a=$editor->createEditor();   //返回编辑器
		$b=$editor->usejs();             //js代码
		$this->assign('usejs',$b);     //输出到html
		$this->assign('editor',$a);
		$this->assign("page_title","添加门票");
    	$this->display();
	}

	public function ticket_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["ticket_name"]=post("ticket_name");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			$data["ticket_price"]=post("ticket_price");
			$data["ticket_ren_num"]=post("ticket_ren_num");
			$data["ticket_num"]=post("ticket_num");
			if($_FILES["ticket_pic"]["error"]==0)
			{
				$uploadinfo=upload_img("upload/ticket/",false,'300','300');
				$data["ticket_pic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["ticket_starttime"]=strtotime(post("ticket_starttime"));
			$data["ticket_endtime"]=strtotime(post("ticket_endtime"));
			$data["ticket_type"]=post("ticket_type");
			$data["ticket_times"]=post("ticket_times");
			$data["ticket_sort"]=post("ticket_sort");
			$data["ticket_is_zengsong"]=post("ticket_is_zengsong");
			$data["ticket_content"]=stripslashes($_POST["ticket_content"]);;
			$data["ticket_addtime"]=time();
			
			$list=M("ticket")->add($data);
			$this->success("添加成功",U('admin/ticket/ticket'));
		}
		else
		{
			$this->error("不能重复提交",U('admin/ticket/ticket_add'));
		}

	}


	public function ticket_edit()
	{
		if(intval(get("ticket_id"))>0)
		{
			
			$data=M("ticket")->where("ticket_id=".intval(get("ticket_id")))->find();
			$this->assign("data",$data);
			
			$event_list=D('event')->event_select_pro(" ");
			$this->assign('event_list',$event_list['item']);
			
			import("@.ORG.editor");  //导入类
			$editor=new editor("400px","700px",$data["ticket_content"],"ticket_content");     //创建一个对象
			$a=$editor->createEditor();   //返回编辑器
			$b=$editor->usejs();             //js代码
			$this->assign('usejs',$b);     //输出到html
			$this->assign('editor',$a);
			
			$this->assign("page_title","修改门票");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function ticket_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["ticket_id"]=post("ticket_id");
			$data["ticket_name"]=post("ticket_name");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			$data["ticket_price"]=post("ticket_price");
			$data["ticket_ren_num"]=post("ticket_ren_num");
			$data["ticket_num"]=post("ticket_num");
			if($_FILES["ticket_pic"]["error"]==0)
			{
				$uploadinfo=upload_img("upload/ticket/",false,'300','300');
				$data["ticket_pic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["ticket_starttime"]=strtotime(post("ticket_starttime"));
			$data["ticket_endtime"]=strtotime(post("ticket_endtime"));
			$data["ticket_type"]=post("ticket_type");
			$data["ticket_times"]=post("ticket_times");
			$data["ticket_sort"]=post("ticket_sort");
			$data["ticket_is_zengsong"]=post("ticket_is_zengsong");
			$data["ticket_content"]=stripslashes($_POST["ticket_content"]);;
			
			$list=M("ticket")->save($data);
			$this->success("修改成功",U('admin/ticket/ticket'));			
		}
		else
		{
			$this->error("不能重复提交",U('admin/ticket/ticket'));
		}

	}

	public function ticket_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("ticket")->where("ticket_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function ticket_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_ticket set ticket_state=1 where ticket_id=".$ids_arr[$i]." ");
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

	public function ticket_detail()
	{
		if(intval(get("ticket_id"))>0)
		{
			$data=M("ticket")->where("ticket_id=".intval(get("ticket_id")))->find();
			if(!empty($data))
			{
				$event_list=D('event')->event_select_pro(" ");
				$this->assign('event_list',$event_list['item']);
				
				$this->assign("data",$data);
				$this->assign("page_title",$data["ticket_name"]."门票");
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
	public function ticket_tj()
	{
		$event_id_list = array(51,25);
		
		$start_date = $start_time = post('start_time');
		if(empty($start_date)){
			$start_date =  $start_time =  date('Y-m-d',time());
		}
		$start_time = strtotime($start_time);
		$end_date = $end_time = post('end_time');
		if(empty($end_time)){
			$end_date = $end_time = date('Y-m-d',time());
		}
		$end_time = intval(strtotime($end_time)) + 86400;
		//echo $end_date,$start_date;
		//宝马门票申请统计
		$sql = "SELECT count(id) as total FROM `tbl_user_ticket_bmw` where bwm_addtime<$end_time";// WHERE event_id=25and user_ticket_addtime>=$start_time and user_ticket_addtime<=$end_time
		
		$bm_total_rs = M()->query($sql); 
		$bm_total_rs = $bm_total_rs[0];
		
		$bm_info['total'] = $bm_total_rs['total'];
		
		
		$today_date = date('Y-m-d',time());
		$today_start = intval(strtotime($today_date));
		$today_end = $today_start + 86400;
		$sql = "SELECT count( DISTINCT `user_ticket_mobile` ) as total FROM `tbl_user_ticket` WHERE event_id=25 and user_ticket_addtime>$start_time and user_ticket_addtime<=$end_time";
		//echo $sql;die;
		$bm_today_rs = M()->query($sql);
		$bm_today_rs = $bm_today_rs[0];
		$bm_info['today'] = $bm_today_rs['total'];
		
		//华彬门票申请统计
		$sql = "SELECT count( DISTINCT `user_ticket_imei` ) as total FROM `tbl_user_ticket` WHERE event_id=51 and user_ticket_addtime<$end_time";// and user_ticket_addtime>=$start_time and user_ticket_addtime<=$end_time
		
		$hb_total_rs = M()->query($sql); 
		$hb_total_rs = $hb_total_rs[0];
		$hb_info['total'] = $hb_total_rs['total'];
		
		$sql = "SELECT count( DISTINCT `user_ticket_imei` ) as total FROM `tbl_user_ticket` WHERE event_id=51 and user_ticket_addtime>=$start_time and user_ticket_addtime<=$end_time";
		$hb_today_rs = M()->query($sql);
		$hb_today_rs = $hb_today_rs[0];
		$hb_info['today'] = $hb_today_rs['total'];
		
		$tj_info['hb'] = $hb_info;
		$tj_info['bm'] = $bm_info;
		
		echo '<script language="javascript" type="text/javascript" src="/skin/js/jquery-1.4.4.min.js"></script>
			<script language="javascript" type="text/javascript" src="/skin/js/My97DatePicker/WdatePicker.js"></script>
			<form name="search_form" action="/default.php?g=admin&m=ticket&a=ticket_tj" method="post"><table width="800" border="0" bgcolor="#cccccc">
			  <tr>
				<td colspan="3" style="line-height:50px; text-align:center; font-size:18px; font-weight:bold;" bgcolor="#FFFFFF">查询</td>
			  </tr>
			  <tr>
				<td width="25%" rowspan="6" bgcolor="#FFFFFF" align="center">选择时间</td>
				<td width="35%" bgcolor="#FFFFFF"><input type="text" name="start_time" class="search_input_date" style="width:120px;" value="'.$start_date.'" onFocus="WdatePicker({isShowClear:false,readOnly:true})"> 至 
				<input type="text" name="end_time" class="search_input_date" style="width:120px;" value="'.$end_date.'" onFocus="WdatePicker({isShowClear:false,readOnly:true})"></td>
				<td bgcolor="#FFFFFF"><input type="submit" value="搜索" class="btn_b" style="margin-right:8px;" ></td>
			  </tr>
			</table></form>';
					
			echo "<br />";
		foreach($tj_info as $key=>$val)
		{
			$field_name = '';
			if($key=='hb'){
				$field_name = '华彬门票申请统计';
			}elseif($key=='bm'){
				$field_name = '宝马门票申请统计';
			}
			echo '<table width="800" border="0" bgcolor="#cccccc">
			  <tr>
				<td colspan="3" style="line-height:50px; text-align:center; font-size:18px; font-weight:bold;" bgcolor="#FFFFFF">'.$field_name.'</td>
			  </tr>
			  <tr>
				<td width="25%" rowspan="6" bgcolor="#FFFFFF" align="center">统计</td>
				<td width="35%" bgcolor="#FFFFFF">&nbsp;</td>
				<td bgcolor="#FFFFFF">&nbsp;</td>
			  </tr>
			  <tr>
				<td bgcolor="#FFFFFF">总数</td>
				<td bgcolor="#FFFFFF">'.$val['total'].'人</td>
			  </tr>
			  <tr>
				<td bgcolor="#FFFFFF">'.$today_date.'(今天)</td>
				<td bgcolor="#FFFFFF">'.$val['today'].' 人</td>
			  </tr>
			</table>';
					
			echo "<br />";
		}
		
		
		/* $this->assign('tj_info',$tj_info);
		
		$this->display(); */
	}


	

}
?>