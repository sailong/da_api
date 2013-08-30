<?php
/**
 *    #Case		bwvip
 *    #Page		Event_userAction.class.php (赛事用户)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-13
 */
class event_userModel extends Model
{

	//list and page
	function event_user_list_pro($bigwhere="", $page_size=20, $sort=" event_user_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("k")!="")
		{
			$where .=" and event_user_name like '%".get("k")."%' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and event_user_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and event_user_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("event_user")->where($where.$bigwhere)->field("event_user_id,event_id,uid,event_user_realname,event_user_sex,event_user_card_type,event_user_card,event_user_chadian,event_user_state,event_user_addtime")->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("event_user")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function event_user_select_pro($bigwhere="",$limit=999999, $sort=" event_user_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("event_user")->where($where.$bigwhere)->field("event_user_id,event_id,uid,event_user_realname,event_user_sex,event_user_card_type,event_user_card,event_user_chadian,event_user_state,event_user_addtime")->order($sort)->limit($limit)->select();
		$data["total"]=M("event_user")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>