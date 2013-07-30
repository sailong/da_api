<?php
/**
 *    #Case		bwvip
 *    #Page		event_applyModel.class.php (赛事报名)
 *
 *    @author		Jack
 *    @E-mail		123695069@qq.com
 */
class event_applyModel extends Model{

	//list and page
	function event_apply_list_pro($bigwhere="", $page_size=20, $sort=" event_apply_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;
		
		$where = " 1 ";
		
		if(get('event_id'))
		{
			$where .=" and event_id='".get('event_id')."' ";
		}
		
		if(get('fenzhan_id')!="")
		{
			$where .=" and fenzhan_id='".get("fenzhan_id")."' ";
		}

		
		
		if(get('event_apply_state')!="")
		{
			$where .=" and event_apply_state='".get("event_apply_state")."' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and event_apply_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$endtime = strtotime(get("endtime"))+86400;
			$where .=" and event_apply_addtime<".$endtime." ";
		}

		$data["item"]=M("event_apply")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["event_id"]!="")
			{
				$event=M()->query("select event_name from ".C("db_prefix")."event where event_id='".$data["item"][$i]["event_id"]."' ");
				$data["item"][$i]["event_name"]=$event[0]["event_name"];
			}
			if($data["item"][$i]["fenzhan_id"]!="")
			{
				$fenzhan=M()->query("select fenzhan_name from ".C("db_prefix")."fenzhan where fenzhan_id='".$data["item"][$i]["fenzhan_id"]."' ");
				$data["item"][$i]["fenzhan_name"]=$fenzhan[0]["fenzhan_name"];
				
				$fenzu=M()->query("select fenzu_id,fenzu_number,(select fenzu_name from tbl_fenzu where fenzu_id=tbl_fenzu_mingxi.fenzu_id) as fenzu_name,lun from ".C("db_prefix")."fenzu_mingxi where fenzhan_id='".$data["item"][$i]["fenzhan_id"]."' and uid='".$data['item'][$i]['uid']."' ");
				
				$lun_info=null;
				for($n=0; $n<count($fenzu); $n++)
				{
					$lun_info[]=$fenzu[$n];
				}
				$data["item"][$i]["lun_info"]=$lun_info;
				
				$lun=M()->query("select * from tbl_lun_mingxi where uid='".$data["item"][$i]["uid"]."' and fenzhan_id='".$data["item"][$i]["fenzhan_id"]."' ");
				
				$lun_arr=null;
				for($j=0; $j<count($lun); $j++)
				{
					$lun_arr[]=$lun[$j]['lun_id'];
				}
				//print_r($lun_arr);
				$data["item"][$i]["lun_arr"]=$lun_arr;
			}
		}
		$data["total"] = M("event_apply")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function event_apply_select_pro($bigwhere="",$limit=999999, $sort=" event_apply_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("event_apply")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("event_apply")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>