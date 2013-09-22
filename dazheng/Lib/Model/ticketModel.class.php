<?php
/**
 *    #Case		bwvip
 *    #Page		TicketAction.class.php (门票)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-06
 */
class ticketModel extends Model{

	//list and page
	function ticket_list_pro($bigwhere="", $page_size=20, $sort=" ticket_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";
		
		if(get("event_id")!="")
		{
			$where .=" and event_id='".get("event_id")."' ";
		}

		if(get("k")!="")
		{
			$where .=" and ticket_name like '%".get("k")."%' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and ticket_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and ticket_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("ticket")->where($where.$bigwhere)->field("ticket_id,ticket_name,event_id,fenzhan_id,ticket_price,ticket_ren_num,ticket_num,ticket_pic,ticket_starttime,ticket_endtime,ticket_type,ticket_times,ticket_content,ticket_addtime,ticket_sort,ticket_is_zengsong")->order($sort)->page($page.",".$page_size)->select();
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
		}
		$data["total"] = M("ticket")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function ticket_select_pro($bigwhere="",$limit=999999, $sort=" ticket_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("ticket")->where($where.$bigwhere)->field("ticket_id,ticket_name,event_id,fenzhan_id,ticket_price,ticket_ren_num,ticket_num,ticket_pic,ticket_starttime,ticket_endtime,ticket_type,ticket_times,ticket_content,ticket_addtime,ticket_sort,ticket_is_zengsong")->order($sort)->limit($limit)->select();
		$data["total"]=M("ticket")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>