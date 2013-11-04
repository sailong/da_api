<?php
/**
 *    #Case		bwvip
 *    #Page		push_messageModel.class.php (消息推送)
 *
 *    @author		Jack
 *    @E-mail		123695069@qq.com
 */
class push_messageModel extends Model{

	//list and page
	function push_message_list_pro($bigwhere="", $page_size=20, $sort=" message_sendtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";
		
		if(get("message_type"))
		{
			$where .=" and message_type='".get("message_type")."' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and message_sendtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$end_time = strtotime(get("endtime")) + 86400;
			$where .=" and message_sendtime<".$end_time." ";
		}


		$data["item"]=M("push_message")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		//echo M()->getLastSql();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("push_message")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function push_message_select_pro($bigwhere="",$limit=999999, $sort=" message_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("push_message")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("push_message")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>