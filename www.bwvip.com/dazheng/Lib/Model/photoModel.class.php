<?php
/**
 *    #Case		bwvip
 *    #Page		PhotoAction.class.php (照片)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-06
 */
class photoModel extends Model{

	//list and page
	function photo_list_pro($bigwhere="", $page_size=20, $sort=" photo_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("k")!="")
		{
			$where .=" and photo_name like '%".get("k")."%' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and photo_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and photo_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("photo")->where($where.$bigwhere)->field("photo_id,uid,album_id,photo_name,photo_url,photo_addtime")->order($sort)->page($page.",".$page_size)->select();
		//echo M()->getLastSql();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["album_id"]!="")
			{
				$user=M()->query("select album_name from ".C("db_prefix")."album where album_id='".$data["item"][$i]["album_id"]."' ");
				$data["item"][$i]["album_name"]=$user[0]["album_name"];
			}
		}
		$data["total"] = M("photo")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function photo_select_pro($bigwhere="",$limit=999999, $sort=" photo_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("photo")->where($where.$bigwhere)->field("photo_id,uid,album_id,photo_name,photo_url,photo_addtime")->order($sort)->limit($limit)->select();
		$data["total"]=M("photo")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>