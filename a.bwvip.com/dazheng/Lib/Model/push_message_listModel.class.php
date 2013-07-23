<?php
/**
 *    #Case		bwvip
 *    #Page		Push_message_listAction.class.php (推送消息队列)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-07-02
 */
class push_message_listModel extends Model{

	//list and page
	function push_message_list_list_pro($bigwhere="", $page_size=20, $sort=" message_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";



		if(get("starttime")!="")
		{
			$where .=" and message_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and message_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("push_message_list")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("push_message_list")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function push_message_list_select_pro($bigwhere="",$limit=999999, $sort=" message_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("push_message_list")->where($where.$bigwhere)->field("message_list_id,message_id,message_number,message_type,uid,message_title,message_content,devices_token,message_state,receiver_type,message_totalnum,message_sendnum,message_errorcode,message_errormsg,message_sendtime,message_addtime")->order($sort)->limit($limit)->select();
		$data["total"]=M("push_message_list")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>