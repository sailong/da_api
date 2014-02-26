<?php
/**
 *    #Case		bwvip
 *    #Page		BaomingAction.class.php (报名)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2014-02-13
 */
class baomingAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function baoming()
	{
		//赛事列表
		$event_select=D('event')->event_select_pro(" ");
		$this->assign('event_select',$event_select['item']);
	
		$page_size=get('page_size');
		if(!$page_size)
		{
			$page_size=20;
		}
	
		$list=D("baoming")->baoming_list_pro('',$page_size,' baoming_addtime desc ');
		
		if(isset($_GET['export'])){
			$this->excel_import($list["item"]);
			return false;
		}
	
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","报名");
    	$this->display();
	}

	public function baoming_add()
	{
		//赛事列表
		$event_select=D('event')->event_select_pro(" ");
		$this->assign('event_select',$event_select['item']);
		
		$this->assign("page_title","添加报名");
    	$this->display();
	}
	
	public function baoming_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["uid"]=post("uid");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			$data["fenzhan_ids"]=post("fenzhan_ids");
			$data["baoming_realname"]=post("baoming_realname");
			$data["baoming_mobile"]=post("baoming_mobile");
			$data["baoming_email"]=post("baoming_email");
			$data["baoming_country"]=post("baoming_country");
			$data["baoming_chadian"]=post("baoming_chadian");
			$data["baoming_sex"]=post("baoming_sex");
			$data["baoming_year"]=post("baoming_year");
			$data["baoming_age"]=post("baoming_age");
			$data["baoming_card"]=post("baoming_card");
			$data["baoming_is_waika"]=post("baoming_is_waika");
			$data["baoming_waika_fenzhan_id"]=post("baoming_waika_fenzhan_id");
			$data["baoming_note"]=post("baoming_note");
			$data["baoming_source"]=post("baoming_source");
			$data["baoming_status"]=post("baoming_status");
			$data["baoming_height"]=post("baoming_height");
			$data["baoming_birth"]=post("baoming_birth");
			$data["baoming_fax"]=post("baoming_fax");
			$data["baoming_credentials"]=post("baoming_credentials");
			$data["baoming_credentials_num"]=post("baoming_credentials_num");
			$data["baoming_resideprovince"]=post("baoming_resideprovince");
			$data["baoming_income"]=post("baoming_income");
			$data["baoming_company"]=post("baoming_company");
			$data["baoming_position"]=post("baoming_position");
			$data["baoming_company_class"]=post("baoming_company_class");
			$data["baoming_address"]=post("baoming_address");
			$data["baoming_zipcode"]=post("baoming_zipcode");
			$data["baoming_is_huang"]=post("baoming_is_huang");
			$data["baoming_car_brand"]=post("baoming_car_brand");
			$data["baoming_h_car_type"]=post("baoming_h_car_type");
			$data["baoming_car_j_type"]=post("baoming_car_j_type");
			$data["baoming_car_marking_shop"]=post("baoming_car_marking_shop");
			$data["baoming_sure_realize"]=post("baoming_sure_realize");
			$data["baoming_sure_drive"]=post("baoming_sure_drive");
			$data["baoming_assess_price"]=post("baoming_assess_price");
			$data["baoming_konw_saishi"]=post("baoming_konw_saishi");
			$data["baoming_bianhua"]=post("baoming_bianhua");
			$data["baoming_buy_car"]=post("baoming_buy_car");
			$data["baoming_car_impress"]=$this->arr_format_to_str($_POST["baoming_car_impress"]," , ");//多
			$data["baoming_ball_age"]=post("baoming_ball_age");
			$data["baoming_best_score"]=post("baoming_best_score");
			$data["baoming_tool_brand"]=$this->arr_format_to_str($_POST["baoming_tool_brand"]," , ");//多
			$data["baoming_num_qiu_hui"]=post("baoming_num_qiu_hui");
			$data["baoming_faction"]=post("baoming_faction");
			$data["baoming_attract"]=$this->arr_format_to_str($_POST["baoming_attract"]," , ");//多
			$data["baoming_hot_district"]=$this->arr_format_to_str($_POST["baoming_hot_district"]," , ");//多
			$data["baoming_accept_way"]=post("baoming_accept_way");
			$data["baoming_yiqi_shop"]=post("baoming_yiqi_shop");
			$data["baoming_is_join_c"]=post("baoming_is_join_c");
			$data["baoming_zige"]=post("baoming_zige");
			$data["baoming_is_zidai_qiutong"]=post("baoming_is_zidai_qiutong");
			$data["baoming_pay_status"]=post("baoming_pay_status");
			$data["baoming_event_status"]=post("baoming_event_status");
			//$data["baoming_note_title"]=post("baoming_note_title");
			//$data["baoming_note_content"]=post("baoming_note_content");
			
			
			$uploadinfo=upload_file("upload/baoming");
			foreach($uploadinfo as $key=>$val){
				$pic_list[$val['up_name']] = $val;
			}
			if($_FILES["baoming_realname_photo"]["error"]==0)
			{
				$data["baoming_realname_photo"]=$pic_list["baoming_realname_photo"]["savepath"] . $pic_list["baoming_realname_photo"]["savename"];
			}
			if($_FILES["baoming_credentials_photo"]["error"]==0)
			{
				$data["baoming_credentials_photo"]=$pic_list["baoming_credentials_photo"]["savepath"] . $pic_list["baoming_credentials_photo"]["savename"];
			}
			if($_FILES["baoming_car_drive_pic"]["error"]==0)
			{
				$data["baoming_car_drive_pic"]=$pic_list["baoming_car_drive_pic"]["savepath"] . $pic_list["baoming_car_drive_pic"]["savename"];
			}
			$data["baoming_addtime"]=time();
			
			$list=M("baoming")->add($data);
			$this->success("添加成功",U('admin/baoming/baoming'));
		}
		else
		{
			$this->error("不能重复提交",U('admin/baoming/baoming_add'));
		}

	}


	public function baoming_edit()
	{
		if(intval(get("baoming_id"))>0)
		{
			//赛事列表
			$event_select=D('event')->event_select_pro(" ");
			$this->assign('event_select',$event_select['item']);
			
			$data=M("baoming")->where("baoming_id=".intval(get("baoming_id")))->find();
			
			$this->assign("data",$data);
			$this->assign("baoming_car_impress_json",$data['baoming_car_impress']);
			$this->assign("baoming_tool_brand_json",$data['baoming_tool_brand']);
			$this->assign("baoming_attract_json",$data['baoming_attract']);
			//$this->assign("baoming_hot_district_json",$data['baoming_hot_district']);
			
			$this->assign("page_title","修改报名");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}
	public function baoming_edit_66()
	{
		if(intval(get("baoming_id"))>0)
		{
			$data=M("baoming")->where("baoming_id=".intval(get("baoming_id")))->find();
			$fenzhan_list = M('fenzhan')->where("event_id='66'")->select();
			$this->assign("fenzhan_list",$fenzhan_list);
			$this->assign("data",$data);
			$this->assign("baoming_hot_district_json",$data['baoming_hot_district']);
			$this->assign("page_title","修改报名");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}
	public function baoming_edit_65()
	{
		if(intval(get("baoming_id"))>0)
		{
			//赛事列表
			$event_select=D('event')->event_select_pro(" ");
			$this->assign('event_select',$event_select['item']);
			
			$data=M("baoming")->where("baoming_id=".intval(get("baoming_id")))->find();
			$fenzhan_list = M('fenzhan')->where("event_id='65'")->select();
			$this->assign("fenzhan_list",$fenzhan_list);
			
			$this->assign("data",$data);
			$this->assign("baoming_car_impress_json",$data['baoming_car_impress']);
			$this->assign("baoming_tool_brand_json",$data['baoming_tool_brand']);
			$this->assign("baoming_attract_json",$data['baoming_attract']);
			//$this->assign("baoming_hot_district_json",$data['baoming_hot_district']);
			
			$this->assign("page_title","修改报名");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}
	
	public function baoming_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{	
			$data["baoming_id"]=post("baoming_id");
			$data["uid"]=post("uid");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			$data["fenzhan_ids"]=post("fenzhan_ids");
			$data["baoming_realname"]=post("baoming_realname");
			$data["baoming_mobile"]=post("baoming_mobile");
			$data["baoming_email"]=post("baoming_email");
			$data["baoming_country"]=post("baoming_country");
			$data["baoming_chadian"]=post("baoming_chadian");
			$data["baoming_sex"]=post("baoming_sex");
			$data["baoming_year"]=post("baoming_year");
			$data["baoming_age"]=post("baoming_age");
			$data["baoming_card"]=post("baoming_card");
			$data["baoming_is_waika"]=post("baoming_is_waika");
			$data["baoming_waika_fenzhan_id"]=post("baoming_waika_fenzhan_id");
			$data["baoming_note"]=post("baoming_note");
			$data["baoming_source"]=post("baoming_source");
			$data["baoming_status"]=post("baoming_status");
			$data["baoming_height"]=post("baoming_height");
			$data["baoming_birth"]=post("baoming_birth");
			$data["baoming_fax"]=post("baoming_fax");
			$data["baoming_credentials"]=post("baoming_credentials");
			$data["baoming_credentials_num"]=post("baoming_credentials_num");
			$data["baoming_resideprovince"]=post("baoming_resideprovince");
			$data["baoming_income"]=post("baoming_income");
			$data["baoming_company"]=post("baoming_company");
			$data["baoming_position"]=post("baoming_position");
			$data["baoming_company_class"]=post("baoming_company_class");
			$data["baoming_address"]=post("baoming_address");
			$data["baoming_zipcode"]=post("baoming_zipcode");
			$data["baoming_is_huang"]=post("baoming_is_huang");
			$data["baoming_car_brand"]=post("baoming_car_brand");
			$data["baoming_h_car_type"]=post("baoming_h_car_type");
			$data["baoming_car_j_type"]=post("baoming_car_j_type");
			$data["baoming_car_marking_shop"]=post("baoming_car_marking_shop");
			$data["baoming_sure_realize"]=post("baoming_sure_realize");
			$data["baoming_sure_drive"]=post("baoming_sure_drive");
			$data["baoming_assess_price"]=post("baoming_assess_price");
			$data["baoming_konw_saishi"]=post("baoming_konw_saishi");
			$data["baoming_bianhua"]=post("baoming_bianhua");
			$data["baoming_buy_car"]=post("baoming_buy_car");
			$data["baoming_car_impress"]=$this->arr_format_to_str($_POST["baoming_car_impress"]," , ");//多
			$data["baoming_ball_age"]=post("baoming_ball_age");
			$data["baoming_best_score"]=post("baoming_best_score");
			$data["baoming_tool_brand"]=$this->arr_format_to_str($_POST["baoming_tool_brand"]," , ");//多
			$data["baoming_num_qiu_hui"]=post("baoming_num_qiu_hui");
			$data["baoming_faction"]=post("baoming_faction");
			$data["baoming_attract"]=$this->arr_format_to_str($_POST["baoming_attract"]," , ");//多
			$data["baoming_hot_district"]=$this->arr_format_to_str($_POST["baoming_hot_district"]," , ");//多
			$data["baoming_accept_way"]=post("baoming_accept_way");
			$data["baoming_yiqi_shop"]=post("baoming_yiqi_shop");
			$data["baoming_is_join_c"]=post("baoming_is_join_c");
			$data["baoming_zige"]=post("baoming_zige");
			$data["baoming_is_zidai_qiutong"]=post("baoming_is_zidai_qiutong");
			$data["baoming_pay_status"]=post("baoming_pay_status");
			$data["baoming_event_status"]=post("baoming_event_status");
			//$data["baoming_note_title"]=post("baoming_note_title");
			//$data["baoming_note_content"]=post("baoming_note_content");
			
			$uploadinfo=upload_file("upload/baoming/");
			foreach($uploadinfo as $key=>$val){
				$pic_list[$val['up_name']] = $val;
			}
			if($_FILES["baoming_realname_photo"]["error"]==0)
			{
				$data["baoming_realname_photo"]=$pic_list["baoming_realname_photo"]["savepath"] . $pic_list["baoming_realname_photo"]["savename"];
			}
			if($_FILES["baoming_credentials_photo"]["error"]==0)
			{
				$data["baoming_credentials_photo"]=$pic_list["baoming_credentials_photo"]["savepath"] . $pic_list["baoming_credentials_photo"]["savename"];
			}
			if($_FILES["baoming_car_drive_pic"]["error"]==0)
			{
				$data["baoming_car_drive_pic"]=$pic_list["baoming_car_drive_pic"]["savepath"] . $pic_list["baoming_car_drive_pic"]["savename"];
			}
			
			$list=M("baoming")->save($data);
			$this->success("修改成功",U('admin/baoming/baoming'));			
		}
		else
		{
			$this->error("不能重复提交",U('admin/baoming/baoming'));
		}

	}
	
	public function baoming_edit_66_action()
	{
		if(M()->autoCheckToken($_POST))
		{	
			$data["baoming_id"]=post("baoming_id");
			$data["baoming_realname"]=post("baoming_realname");
			$data["baoming_mobile"]=post("baoming_mobile");
			$data["baoming_email"]=post("baoming_email");
			$data["baoming_chadian"]=post("baoming_chadian");
			$data["baoming_sex"]=post("baoming_sex");
			$data["baoming_card"]=post("baoming_card");
			//$data["baoming_hot_district"]=$this->arr_format_to_str($_POST["baoming_hot_district"]," , ");//多
			$data["fenzhan_ids"]=$this->arr_format_to_str($_POST["fenzhan_ids"]," , ");
			$data["baoming_zige"]=post("baoming_zige");
			$data["baoming_is_zidai_qiutong"]=post("baoming_is_zidai_qiutong");
			
			$list=M("baoming")->save($data);
			$this->success("修改成功",U('admin/baoming/baoming'));			
		}
		else
		{
			$this->error("不能重复提交",U('admin/baoming/baoming'));
		}

	}
	public function baoming_edit_65_action()
	{
		if(M()->autoCheckToken($_POST))
		{	
			$data["baoming_id"]=post("baoming_id");
			$data["uid"]=post("uid");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			//$data["fenzhan_ids"]=post("fenzhan_ids");
			$data["baoming_realname"]=post("baoming_realname");
			$data["baoming_mobile"]=post("baoming_mobile");
			$data["baoming_email"]=post("baoming_email");
			$data["baoming_country"]=post("baoming_country");
			$data["baoming_chadian"]=post("baoming_chadian");
			$data["baoming_sex"]=post("baoming_sex");
			$data["baoming_year"]=post("baoming_year");
			$data["baoming_age"]=post("baoming_age");
			$data["baoming_card"]=post("baoming_card");
			$data["baoming_is_waika"]=post("baoming_is_waika");
			$data["baoming_waika_fenzhan_id"]=post("baoming_waika_fenzhan_id");
			$data["baoming_note"]=post("baoming_note");
			$data["baoming_source"]=post("baoming_source");
			$data["baoming_status"]=post("baoming_status");
			$data["baoming_height"]=post("baoming_height");
			$data["baoming_birth"]=post("baoming_birth");
			$data["baoming_fax"]=post("baoming_fax");
			$data["baoming_credentials"]=post("baoming_credentials");
			$data["baoming_credentials_num"]=post("baoming_credentials_num");
			$data["baoming_resideprovince"]=post("baoming_resideprovince");
			$data["baoming_income"]=post("baoming_income");
			$data["baoming_company"]=post("baoming_company");
			$data["baoming_position"]=post("baoming_position");
			$data["baoming_company_class"]=post("baoming_company_class");
			$data["baoming_address"]=post("baoming_address");
			$data["baoming_zipcode"]=post("baoming_zipcode");
			$data["baoming_is_huang"]=post("baoming_is_huang");
			$data["baoming_car_brand"]=post("baoming_car_brand");
			$data["baoming_h_car_type"]=post("baoming_h_car_type");
			$data["baoming_car_j_type"]=post("baoming_car_j_type");
			$data["baoming_car_marking_shop"]=post("baoming_car_marking_shop");
			$data["baoming_sure_realize"]=post("baoming_sure_realize");
			$data["baoming_sure_drive"]=post("baoming_sure_drive");
			$data["baoming_assess_price"]=post("baoming_assess_price");
			$data["baoming_konw_saishi"]=post("baoming_konw_saishi");
			$data["baoming_bianhua"]=post("baoming_bianhua");
			$data["baoming_buy_car"]=post("baoming_buy_car");
			$data["baoming_car_impress"]=$this->arr_format_to_str($_POST["baoming_car_impress"],",");//多
			$data["baoming_ball_age"]=post("baoming_ball_age");
			$data["baoming_best_score"]=post("baoming_best_score");
			$data["baoming_tool_brand"]=$this->arr_format_to_str($_POST["baoming_tool_brand"],",");//多
			$data["baoming_num_qiu_hui"]=post("baoming_num_qiu_hui");
			$data["baoming_faction"]=post("baoming_faction");
			$data["baoming_attract"]=$this->arr_format_to_str($_POST["baoming_attract"],",");//多
			//$data["baoming_hot_district"]=$this->arr_format_to_str($_POST["baoming_hot_district"]," , ");//多
			$data["fenzhan_ids"]=$this->arr_format_to_str($_POST["fenzhan_ids"],",");
			$data["baoming_accept_way"]=post("baoming_accept_way");
			$data["baoming_yiqi_shop"]=post("baoming_yiqi_shop");
			$data["baoming_is_join_c"]=post("baoming_is_join_c");
			$data["baoming_zige"]=post("baoming_zige");
			$data["baoming_is_zidai_qiutong"]=post("baoming_is_zidai_qiutong");
			$data["baoming_pay_status"]=post("baoming_pay_status");
			$data["baoming_event_status"]=post("baoming_event_status");
			//$data["baoming_note_title"]=post("baoming_note_title");
			//$data["baoming_note_content"]=post("baoming_note_content");
			
			$uploadinfo=upload_file("upload/baoming/");
			foreach($uploadinfo as $key=>$val){
				$pic_list[$val['up_name']] = $val;
			}
			if($_FILES["baoming_realname_photo"]["error"]==0)
			{
				$data["baoming_realname_photo"]=$pic_list["baoming_realname_photo"]["savepath"] . $pic_list["baoming_realname_photo"]["savename"];
			}
			if($_FILES["baoming_credentials_photo"]["error"]==0)
			{
				$data["baoming_credentials_photo"]=$pic_list["baoming_credentials_photo"]["savepath"] . $pic_list["baoming_credentials_photo"]["savename"];
			}
			if($_FILES["baoming_car_drive_pic"]["error"]==0)
			{
				$data["baoming_car_drive_pic"]=$pic_list["baoming_car_drive_pic"]["savepath"] . $pic_list["baoming_car_drive_pic"]["savename"];
			}
			
			$list=M("baoming")->save($data);
			$this->success("修改成功",U('admin/baoming/baoming'));			
		
		}
		else
		{
			$this->error("不能重复提交",U('admin/baoming/baoming'));
		}

	}
	
	public function baoming_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("baoming")->where("baoming_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function baoming_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_baoming set baoming_state=1 where baoming_id=".$ids_arr[$i]." ");
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

	public function baoming_detail()
	{
		if(intval(get("baoming_id"))>0)
		{
			$data=M("baoming")->where("baoming_id=".intval(get("baoming_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["baoming_name"]."报名");
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
	
	//编辑备注信息
	public function baoming_note_edit()
	{
		if(intval(get("baoming_id"))>0)
		{
			
			$data=M("baoming")->field('baoming_id,baoming_note,baoming_note_title,baoming_note_content')->where("baoming_id=".intval(get("baoming_id")))->find();
			
			$this->assign("data",$data);
			
			$this->assign("page_title","编辑报名备注");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}
	
	//编辑备注信息
	public function baoming_note_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{	
			$data["baoming_id"]=post("baoming_id");
			$data["baoming_note"]=post("baoming_note");
			$data["baoming_note_title"]=post("baoming_note_title");
			$data["baoming_note_content"]=post("baoming_note_content");
			$list=M("baoming")->save($data);
			if($list){
				//$this->success("操作成功");
				msg_dialog_tip('succeed^操作成功');
			}else{
				//$this->error("操作失败");
				msg_dialog_tip('error^操作失败');
			}
		}else{
			//$this->error("操作失败");
			msg_dialog_tip('error^操作失败');
		}
	}
	
	//备注信息详细
	public function baoming_note_detail()
	{
		if(intval(get("baoming_id"))>0)
		{
			
			$data=M("baoming")->field('baoming_id,baoming_note,baoming_note_title,baoming_note_content')->where("baoming_id=".intval(get("baoming_id")))->find();
			
			$this->assign("data",$data);
			
			$this->assign("page_title","报名备注详细");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}
	
	//将数组值用符号链接(只支持一维数组)
	public function arr_format_to_str($arr=array(), $exp)
	{
		if(empty($arr) || empty($exp)){
			return '';
		}
		
		
		return implode($exp,$arr);
	}
	//将字符串值根据某个字符转化成数组
	public function str_format_to_arr($str='', $exp)
	{
		if(empty($str) || empty($exp)){
			return '';
		}
		
		return explode($exp,$str);
	}
	
	//根据赛事id(event_id)获取相关分站列表
	public function get_fenzhan_list()
	{
		$event_id = get('event_id');
		
		if(empty($event_id)){
			$this->ajaxReturn(null,'参数错误',0);
		}
		$fenzhan_list = M('fenzhan')->where("event_id='{$event_id}'")->select();
		
		if($fenzhan_list){
			$this->ajaxReturn($fenzhan_list,'成功',1);
		}
		
		$this->ajaxReturn(null,'失败',0);
	}
	
	//修改比赛状态
	public function batch_upd_event_status()
	{
		
		//赛事列表
		$event_select=D('event')->event_select_pro(" ");
		$this->assign('event_select',$event_select['item']);
		
		$this->display();
	}
	
	public function batch_upd_event_status_action()
	{
		$event_id=post("event_id");
		$fenzhan_id=post("fenzhan_id");
		$baoming_event_status=post("baoming_event_status");
		
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			
			$ids = "'".implode("','",$ids_arr)."'";
			
			$res=M()->execute("update tbl_baoming set event_id='{$event_id}',fenzhan_id='{$fenzhan_id}',baoming_event_status='{$baoming_event_status}' where baoming_id in({$ids})");
			
			
			if($res){
			//$this->success("操作成功");
				msg_dialog_tip('succeed^操作成功');
			}else{
				//$this->error("操作失败");
				msg_dialog_tip('error^操作失败');
			}
		}
		
	}
	
	//ajax方式修改信息
	public function upd_data_by_ajax(){
		$baoming_id = get('baoming_id');
		$field = get('field');
		$value = get('value');
		if($baoming_id == '' || $field == '' || $value == ''){
			$this->ajaxReturn(null,'失败',0);
		}
		$data['baoming_id'] = $baoming_id;
		$data[$field] = $value;
		
		$list=M("baoming")->save($data);
		
		if($list){
			$this->ajaxReturn(null,'成功',1);
		}
		$this->ajaxReturn(null,'失败',0);
	}
	
	public function excel_import($data_list,$event_id)
	{
		if(get("event_id") == 66){
			$titiles = array(
				'姓名',
				'性别',
				'差点',
				'手机号码',
				'邮箱',
				'身份证号',
				'参加的比赛',
				'报名资格',
				'是否自带球童'
			);
			$fields = 'baoming_realname,
						baoming_sex,
						baoming_chadian,
						baoming_mobile,
						baoming_email,
						baoming_card,
						baoming_hot_district,
						baoming_zige,
						baoming_is_zidai_qiutong						
						';
		}else{
			$titiles = array(
				'姓名',
				'性别',
				'出生日期',
				'国籍',
				'身高',
				'球龄',
				'差点',
				'比赛场地',
				'手机号码',
				'证件类型',
				'证件号码',
				'所属行业',
				'公司名称',
				'职务',
				'个人年收入',
				'地址',
				'邮箱',
				'是否参加去年城市挑战赛',
				'您目前的座驾',
				'拥有的球具品牌',
				'是否皇冠车主',
				'皇冠车型',
				'皇冠车驾号',
				'所属经销店',
				'对皇冠车印象变化',
				'对皇冠的评价',
				'是否了解皇冠',
				'是否试驾皇冠',
				'知道哪些汽车类赛事',
				'通过何种渠道了解挑战赛',
				'您参加本次比赛的原因'
			);
			$fields = 'baoming_realname,
						baoming_sex,
						baoming_birth,
						baoming_country,
						baoming_height,
						baoming_ball_age,
						baoming_chadian,
						baoming_hot_district,
						baoming_mobile,
						baoming_credentials,
						baoming_credentials_num,
						baoming_company_class,
						baoming_company,
						baoming_position,
						baoming_income,
						baoming_address,
						baoming_email,
						baoming_is_join_c,
						baoming_car_brand,
						baoming_tool_brand,
						baoming_is_huang,
						baoming_h_car_type,
						baoming_car_j_type,
						baoming_car_marking_shop,
						baoming_bianhua,
						baoming_assess_price,
						baoming_sure_realize,
						baoming_sure_drive,
						baoming_konw_saishi,
						baoming_accept_way,
						baoming_attract';
		}
		$baoming_list = $data_list;//M('baoming')->field($fields)->select();//->where()
		$i=1;
		$excel_list[$i++] = $titiles;
		foreach($baoming_list as $key=>$val){
			if(get("event_id") == 66){
				if($val['baoming_is_zidai_qiutong'] == 1){
					$val['baoming_is_zidai_qiutong'] = '是';
				}else{
					$val['baoming_is_zidai_qiutong'] = '否';
				}
			}else{
				if($val['baoming_is_join_c'] == 1){
					$val['baoming_is_join_c'] = '是';
				}else{
					$val['baoming_is_join_c'] = '否';
				}
				if($val['baoming_is_huang'] == 1){
					$val['baoming_is_huang'] = '是';
				}else{
					$val['baoming_is_huang'] = '否';
				}
				$baoming_assess_price = array(
					1=>'中级车',
					2=>'中高级车',
					3=>'高级车'
				);
				if($val['baoming_sure_realize'] == 1){
					$val['baoming_sure_realize'] = '了解';
				}else{
					$val['baoming_sure_realize'] = '不了解';
				}
				if($val['baoming_sure_drive'] == 1){
					$val['baoming_sure_drive'] = '试驾过';
				}else{
					$val['baoming_sure_drive'] = '没有';
				}
				if($val['baoming_sure_drive'] == 1){
					$val['baoming_sure_drive'] = '试驾过';
				}else{
					$val['baoming_sure_drive'] = '没有';
				}
				$baoming_credentials = array(
					1=>'身份证',
					2=>'驾驶本',
					3=>'台胞证',
					4=>'回乡证'
				);
				$val['baoming_assess_price'] = $baoming_assess_price[$val['baoming_assess_price']];
				$val['baoming_credentials'] = $baoming_credentials[$val['baoming_credentials']];
			}
			foreach($val as $key1=>$val1){
				$tmp_info[] = $val1;
			}
			$excel_list[$i++] = $tmp_info;
			unset($baoming_list[$key],$tmp_info);
		}
		
		$excel_datas[0] = array(
			'title' => '大正报名-'.date('Y-m-d',time()),
			'cols' => count($titiles),
			'rows' => count($excel_list),
			'datas' => $excel_list,
		);
		
		$excel_pre = time();
		$tmp_dir = dirname(dirname(dirname(dirname(__FILE__))));
		$root_dir = dirname($tmp_dir);
		
		$save_path=$root_dir."/upload/myexcel/";
		$full_save_path=$save_path.date("Ymd",time())."/";
		if(!file_exists($save_path))
		{
			mkdir($save_path);
		}
		if(!file_exists($full_save_path))
		{
			mkdir($full_save_path);
		}
		
		$pFileName =  $full_save_path . $excel_pre . ".xls";
		
		include_once $tmp_dir.'/Common/WmwPHPExcel.class.php';
		$HandlePHPExcel = new WmwPHPExcel();
		
		$HandlePHPExcel->saveToExcelFile($excel_datas, $pFileName);
		$HandlePHPExcel->export($pFileName,$excel_datas[0]['title']);
		$this->chmodDirByDir($save_path);
		unset($excel_datas); 

	}
	
	//修改最后一个目录 的权限
	public function chmodDirByDir($dir)
	{
		$list = scandir($dir);
		if(count($list) > 2)
		{
			foreach($list as $file)
			{
				
				if(($file != ".") && ($file != ".."))
				{
					$tmp = $dir."/".$file;
					call_user_func(__METHOD__,$tmp);//$this->chmodDirByDir($tmp);
				}
				else
				{
					@chmod($dir,0777);
				}
			}
		}
		else
		{
			$a=@chmod($dir,0777);
		}
	}

}
?>