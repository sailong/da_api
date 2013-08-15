<?php
/**
 *    #Case		bwvip
 *    #Page		AlbumAction.class.php (相册)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-06
 */
class albumModel extends Model{

	//list and page
	function album_list_pro($bigwhere="", $page_size=20, $sort=" album_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("k")!="")
		{
			$where .=" and album_name like '%".get("k")."%' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and album_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and album_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("album")->where($where.$bigwhere)->field("album_id,uid,album_name,album_sort,album_addtime")->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("album")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function album_select_pro($bigwhere="",$limit=999999, $sort=" album_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("album")->where($where.$bigwhere)->field("album_id,uid,album_name,album_sort,album_addtime")->order($sort)->limit($limit)->select();
		$data["total"]=M("album")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>