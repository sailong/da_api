<?php
/**
 *    #Case		tankuang
 *    #Page		msg_logModel.class.php (短信)
 *
 *    @author		Jack
 *    @E-mail		68779953@qq.com
 */
class msg_logModel extends Model{

	//list and page
	function msg_log_list_pro($bigwhere="", $page_size=20, $sort=" msg_log_id desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";
		
		if(get("field_uid"))
		{
			$where .=" and field_uid='".get("field_uid")."' ";
		}

		if(get("mobile"))
		{
			$where .=" and mobile like '%".get("mobile")."%' ";
		}
		if(get("code"))
		{
			$where .=" and code='".get("code")."' ";
		}
		if(get("k"))
		{
			$where .=" and content like '%".get("k")."%' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and msg_log_sendtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and msg_log_sendtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("msg_log")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		//echo M()->getLastSql();
		/* for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		} */
		$data["total"] = M("msg_log")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();
		
		return $data;
	}


	//nopage select limit 
	function msg_log_select_pro($bigwhere="",$limit=999999, $sort=" msg_log_id desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("msg_log")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("msg_log")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>