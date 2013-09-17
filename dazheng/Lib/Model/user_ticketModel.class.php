<?php
/**
 *    #Case		bwvip
 *    #Page		User_ticketAction.class.php (门票领取)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-06
 */
class user_ticketModel extends Model{

	//list and page
	function user_ticket_list_pro($bigwhere="", $page_size=20, $sort=" user_ticket_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("k")!="")
		{
			$where .=" and ticket_id=".get("k");
		}

		if(get("starttime")!="")
		{
			$where .=" and user_ticket_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$endtime = strtotime(get("endtime")) + 86400;
			$where .=" and user_ticket_addtime<".$endtime." ";
		}
		
		$data["item"]=M("user_ticket")->where($where.$bigwhere)->field("user_ticket_id,uid,ticket_id,event_id,ticket_type,user_ticket_code,user_ticket_codepic,user_ticket_realname,user_ticket_nums,user_ticket_sex,user_ticket_age,user_ticket_address,user_ticket_cardtype,user_ticket_card,user_ticket_mobile,user_ticket_imei,user_ticket_company,user_ticket_company_post,user_ticket_status,user_ticket_addtime,out_id,out_idtype")->order($sort)->page($page.",".$page_size)->select();
		
		//echo M()->getLastSql();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("user_ticket")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function user_ticket_select_pro($bigwhere="",$limit=999999, $sort=" user_ticket_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("user_ticket")->where($where.$bigwhere)->field("user_ticket_id,uid,ticket_id,event_id,ticket_type,user_ticket_code,user_ticket_codepic,user_ticket_realname,user_ticket_nums,user_ticket_sex,user_ticket_age,user_ticket_address,user_ticket_cardtype,user_ticket_card,user_ticket_mobile,user_ticket_imei,user_ticket_company,user_ticket_company_post,user_ticket_status,user_ticket_addtime")->order($sort)->limit($limit)->select();
		$data["total"]=M("user_ticket")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>