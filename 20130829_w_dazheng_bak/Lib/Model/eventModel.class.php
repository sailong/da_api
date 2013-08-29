<?php
/**
 *    #Case		bwvip
 *    #Page		eventModel.class.php (赛事)
 *
 *    @author		Jack
 *    @E-mail		123695069@qq.com
 */
class eventModel extends Model{

	//list and page
	function event_list_pro($bigwhere="", $page_size=20, $sort=" event_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = array();
		if(get("k"))
		{
			$where[] ="event_name like '%".get("k")."%'";
		}
		if(get("starttime")!="")
		{
			$where[] ="event_addtime>".strtotime(get("starttime"));
		}
		if(get("endtime")!="")
		{
			$where[] ="event_addtime<".strtotime(get("endtime"));
		}
		if(get("event_is_tj")!="")
		{
			$where[] ="event_is_tj='".get("event_is_tj")."'";
		}
		
		if(!empty($where)) {
			$where = implode(' and ',$where);
		}else{
			$where = ' 1 ';
		}
		
		$data["item"]=M("event")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{

		}
		$data["total"] = M("event")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function event_select_pro($bigwhere="",$limit=999999, $sort=" event_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("event")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("event")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>