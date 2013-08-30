<?php
/**
 *    #Case		bwvip
 *    #Page		FenzuAction.class.php (赛事分组)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-05-29
 */
class fenzuModel extends Model{

	//list and page
	function fenzu_list_pro($bigwhere="", $page_size=20, $sort=" fenzu_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";
		
		if(get('event_id'))
		{
			$where .=" and event_id='".get('event_id')."' ";
		}
		if(get('lun'))
		{
			$where .=" and lun='".get('lun')."' ";
		}
		
		if(get('fenzhan_id'))
		{
			$where .=" and fenzhan_id='".get('fenzhan_id')."' ";
		}

		if(get("k")!="")
		{
			$where .=" and fenzu_name like '%".get("k")."%' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and fenzu_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and fenzu_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("fenzu")->where($where.$bigwhere)->field("fenzu_id,fenzu_number,fenzu_name,fenzu_tee,lun,fenzu_start_time,fenzu_ampm,event_id,fenzhan_id,fenzu_addtime")->order($sort)->page($page.",".$page_size)->select();
		foreach($data["item"] as $key=>&$val)
		{
			if($val["fenzhan_id"]!="")
			{
				$fenzhan=M('fenzhan')->field('fenzhan_name')->where("fenzhan_id='{$val["fenzhan_id"]}'")->find();//query("select fenzhan_name from ".C("db_prefix")."fenzhan where fenzhan_id='".$data["item"][$i]["fenzhan_id"]."' ");
				$val["fenzhan_name"]=$fenzhan["fenzhan_name"];
			}
		    if($val["event_id"]!="")
			{
			    $event=M('event')->where("event_id='{$val["event_id"]}'")->find();
			    $val["event_name"]=$event["event_name"];
			}
		}
		$data["total"] = M("fenzu")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function fenzu_select_pro($bigwhere="",$limit=999999, $sort=" fenzu_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("fenzu")->where($where.$bigwhere)->field("fenzu_id,fenzu_number,fenzu_name,fenzu_tee,lun,fenzu_start_time,fenzu_ampm,event_id,fenzhan_id,fenzu_addtime")->order($sort)->limit($limit)->select();
		$data["total"]=M("fenzu")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>