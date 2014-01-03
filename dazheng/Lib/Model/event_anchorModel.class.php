<?php
/**
 *    #Case		bwvip
 *    #Page		event_anchorModel.class.php (赛事播音)
 *
 *    @author		Jack
 *    @E-mail		123695069@qq.com
 */
class event_anchorModel extends Model{

	//list and page
	function anchor_list_pro($bigwhere="", $page_size=20, $sort=" event_anchor_sort desc,event_anchor_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = array();
		if(get("k"))
		{
			$where[] ="event_anchor_name like '%".get("k")."%'";
		}
		if(get("starttime")!="")
		{
			$where[] ="event_anchor_addtime>".strtotime(get("starttime"));
		}
		if(get("endtime")!="")
		{
			$where[] ="event_anchor_addtime<".strtotime(get("endtime"));
		}
		if(get("event_id")!="")
		{
			$where[] ="event_id='".get("event_id")."'";
		}
		
		if(!empty($where)) {
			$where = implode(' and ',$where);
		}else{
			$where = ' 1 ';
		}
		
		$data["item"]=M("event_anchor")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{

		}
		$data["total"] = M("event_anchor")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function anchor_select_pro($bigwhere="",$limit=999999, $sort=" event_anchor_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("event_anchor")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("event_anchor")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>