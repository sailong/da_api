<?php
/**
 *    #Case		bwvip
 *    #Page		BaomingAction.class.php (报名)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2014-02-13
 */
class baomingModel extends Model{

	//list and page
	function baoming_list_pro($bigwhere="", $page_size=20, $sort=" baoming_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("k")!="")
		{
			$where .=" and (baoming_realname like '%".get("k")."%' or baoming_mobile like '%".get("k")."%' or uid='".get("k")."')";
		}
		if(get("event_id")!="")
		{
			$where .=" and event_id='".get("event_id")."' ";
		}
		if(get("baoming_year")!="")
		{
			$where .=" and baoming_year='".get("baoming_year")."' ";
		}
		if(get("fenzhan_id")!="")
		{
			$where .=" and (fenzhan_id='".get("fenzhan_id")."' or fenzhan_ids like '%".get("fenzhan_id")."%')";
		}
		if(get("baoming_status")!="")
		{
			$where .=" and baoming_status='".get("baoming_status")."' ";
		}
		if(get("baoming_pay_status")!="")
		{
			$where .=" and baoming_pay_status='".get("baoming_pay_status")."' ";
		}
		if(get("baoming_event_status")!="")
		{
			$where .=" and baoming_event_status='".get("baoming_event_status")."' ";
		}
		if(get("baoming_note")!="")
		{
			$where .=" and baoming_note='".get("baoming_note")."' ";
		}
		
		if(get("baoming_source")!="")
		{
			$where .=" and baoming_source='".get("baoming_source")."' ";
		}
		
		if(get("baoming_pay_type")!="")
		{
			$where .=" and baoming_pay_type='".get("baoming_pay_type")."' ";
		}
		
		if(get("baoming_is_waika")!="")
		{
			$where .=" and baoming_is_waika='".get("baoming_is_waika")."' ";
		}
		

		if(get("starttime")!="")
		{
			$where .=" and baoming_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and baoming_addtime<".strtotime(get("endtime"))." ";
		}
		if(isset($_GET['export'])){
			if(get("event_id") == 66){
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
			
			$data["item"]=M("baoming")->field($fields)->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		}else{
			$data["item"]=M("baoming")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		}
		
		//->field("baoming_id,uid,event_id,fenzhan_id,baoming_realname,baoming_mobile,baoming_email,baoming_country,baoming_chadian,baoming_sex,baoming_is_waika,baoming_waika_fenzhan_id,baoming_note,baoming_source,baoming_status,baoming_addtime")
		//echo M()->getLastSql();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
			if($data["item"][$i]["event_id"]!="")
			{
				$user=M()->query("select event_name from ".C("db_prefix")."event where  event_id='".$data["item"][$i]["event_id"]."' ");
				$data["item"][$i]["event_name"]=$user[0]["event_name"];
			}
			if($data["item"][$i]["fenzhan_id"]!="")
			{
				$user=M()->query("select fenzhan_name from ".C("db_prefix")."fenzhan where  fenzhan_id='".$data["item"][$i]["fenzhan_id"]."' ");
				$data["item"][$i]["fenzhan_name"]=$user[0]["fenzhan_name"];
			}
		}
		$data["total"] = M("baoming")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function baoming_select_pro($bigwhere="",$limit=999999, $sort=" baoming_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("baoming")->where($where.$bigwhere)->field("baoming_id,uid,event_id,fenzhan_id,baoming_realname,baoming_mobile,baoming_email,baoming_country,baoming_chadian,baoming_sex,baoming_is_waika,baoming_waika_fenzhan_id,baoming_note,baoming_source,baoming_status,baoming_addtime")->order($sort)->limit($limit)->select();
		$data["total"]=M("baoming")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}