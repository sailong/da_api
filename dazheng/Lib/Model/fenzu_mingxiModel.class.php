<?php
/**
 *    #Case		bwvip
 *    #Page		Fenzu_mingxiAction.class.php (分组明细)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-05-29
 */
class fenzu_mingxiModel extends Model{

	//list and page
	function fenzu_mingxi_list_pro($bigwhere="", $page_size=20, $sort=" addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("k")!="")
		{
			$where .=" and fenzu_mingxi_name like '%".get("k")."%' ";
		}
		
		if(get('event_id'))
		{
			$where .=" and event_id='".get('event_id')."' ";
		}
		if(get('fenzhan_id')!="")
		{
			$where .=" and fenzhan_id='".get("fenzhan_id")."' ";
		}
		if(get('lun')!="")
		{
			$where .=" and lun='".get("lun")."' ";
		}
		
		if(get("starttime")!="")
		{
			$where .=" and fenzu_mingxi_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and fenzu_mingxi_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("fenzu_mingxi")->where($where.$bigwhere)->field("fenzu_mingxi_id,uid,realname,event_id,fenzhan_id,field_id,fenzu_id,am_pm,start_time,tee,chadian,golf_team_id,golf_team_name,onlymark,addtime")->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["fenzu_id"]!="")
			{
				$user=M()->query("select fenzu_id,fenzu_number,fenzu_name,lun from ".C("db_prefix")."fenzu where fenzu_id='".$data["item"][$i]["fenzu_id"]."' ");
				
				$data["item"][$i]["fenzu_name"]=$user[0]["fenzu_name"];
			}
		}
		$data["total"] = M("fenzu_mingxi")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function fenzu_mingxi_select_pro($bigwhere="",$limit=999999, $sort=" addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("fenzu_mingxi")->where($where.$bigwhere)->field("fenzu_mingxi_id,uid,realname,event_id,fenzhan_id,field_id,fenzu_id,am_pm,start_time,tee,chadian,golf_team_id,golf_team_name,onlymark,addtime")->order($sort)->limit($limit)->select();
		$data["total"]=M("fenzu_mingxi")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>