<?php
/**
 *    #Case		bwvip
 *    #Page		fenzhantblModel.class.php (分站)
 *
 *    @Aauthor		Jack
 *    @E-mail		123695069@qq.com
 *    @Date			2013-05-28
 */
class fenzhan_tblModel extends Model{

	//list and page
	function fenzhan_list_pro($bigwhere="", $page_size=20, $sort=" orderby desc,addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";
		
		if(get('event_id'))
		{
			$where .=" and event_id='".get('event_id')."' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and starttime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$endtime = strtotime(get("endtime"))+86400;
			$where .=" and starttime<".$endtime." ";
		}

		$data["item"]=M("fenzhan")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["event_id"]!="")
			{
				$event=M()->query("select event_name from ".C("db_prefix")."event where event_id='".$data["item"][$i]["event_id"]."' ");
				$data["item"][$i]["event_name"]=$event[0]["event_name"];
				
				$fenzhan=M()->query("select fenzhan_name from ".C("db_prefix")."fenzhan where fenzhan_id='".$data["item"][$i]["fenzhan_id"]."' ");
				$data["item"][$i]["fenzhan_name"]=$fenzhan[0]["fenzhan_name"];
			}
		}
		$data["total"] = M("fenzhan")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function fenzhan_select_pro($bigwhere="",$limit=999999, $sort="   ") 
	{
		$where = " 1 ";
		$data["item"]=M("fenzhan")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("fenzhan")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>