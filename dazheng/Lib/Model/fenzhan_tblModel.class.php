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
	function fenzhan_list_pro($bigwhere="", $page_size=20, $sort=" addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";
		
		if(get('event_id'))
		{
			$where .=" and event_id='".get('event_id')."' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and fenzhan_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and fenzhan_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("fenzhan")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		
		foreach($data["item"] as $key=>&$val) {
		    if($val["event_id"]!="")
			{
				$event=M('event')->where("event_id='{$val["event_id"]}'")->find();
			    $val["event_name"]=$event["event_name"];
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