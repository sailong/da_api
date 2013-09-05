<?php
/**
 *    #Case		bwvip
 *    #Page		bmw_ticketAction.class.php (门票领取)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-06
 */
class bmw_ticketModel extends Model{

	//list and page
	function bmw_ticket_list_pro($bigwhere="", $page_size=20, $sort=" bwm_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("k")!="")
		{
			$where .=" and phone like '%".get("k")."%' ";
		}
		
		if(get("starttime")!="")
		{
			$where .=" and bwm_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$end_time = strtotime(get("endtime"))+86400;
			$where .=" and bwm_addtime<".$end_time." ";
		}
		
		$data["item"]=M("user_ticket_bmw")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		
		
		/* for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		} */
		$data["total"] = M("user_ticket_bmw")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function user_ticket_select_pro($bigwhere="",$limit=999999, $sort=" bwm_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("user_ticket_bmw")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("user_ticket_bmw")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>