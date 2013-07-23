<?php
/**
 *    #Case		bwvip
 *    #Page		Baofen_userAction.class.php (报分员)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-05-29
 */
class baofen_userModel extends Model{

	//list and page
	function baofen_user_list_pro($bigwhere="", $page_size=20, $sort=" addtime desc ") 
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

		if(get("k")!="")
		{
			$where .=" and baofen_user_name like '%".get("k")."%' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and baofen_user_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and baofen_user_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("baofen_user")->where($where.$bigwhere)->field("baofen_user_id,username,password,event_id,fenzhan_id,field_id,dongs,iteamid,onlymark,addtime")->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["fenzhan_id"]!="")
			{
				$fenzhan=M()->query("select fenzhan_name from ".C("db_prefix")."fenzhan where fenzhan_id='".$data["item"][$i]["fenzhan_id"]."' ");
				$data["item"][$i]["fenzhan_name"]=$fenzhan[0]["fenzhan_name"];
			}
		}
		$data["total"] = M("baofen_user")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function baofen_user_select_pro($bigwhere="",$limit=999999, $sort=" addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("baofen_user")->where($where.$bigwhere)->field("baofen_user_id,username,password,event_id,fenzhan_id,field_id,dongs,iteamid,onlymark,addtime")->order($sort)->limit($limit)->select();
		$data["total"]=M("baofen_user")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>