<?php
/**
 *    #Case		bwvip
 *    #Page		Rank_logAction.class.php (排名记录)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2014-03-05
 */
class rank_logModel extends Model{

	//list and page
	function rank_log_list_pro($bigwhere="", $page_size=20, $sort=" rank_log_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("k")!="")
		{
			$where .=" and rank_log_name like '%".get("k")."%' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and rank_log_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and rank_log_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("rank_log")->where($where.$bigwhere)->field("rank_log_id,uid,event_id,event_level,user_group,rank_num,rank_total_score,rank_score,rank_reward_score,rank_log_addtime")->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("rank_log")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function rank_log_select_pro($bigwhere="",$limit=999999, $sort=" rank_log_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("rank_log")->where($where.$bigwhere)->field("rank_log_id,uid,event_id,event_level,user_group,rank_num,rank_total_score,rank_score,rank_reward_score,rank_log_addtime")->order($sort)->limit($limit)->select();
		$data["total"]=M("rank_log")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>