<?php
/**
 *    #Case		tankuang
 *    #Page		arcModel.class.php (文章)
 *
 *    @author		Jack
 *    @E-mail		68779953@qq.com
 */
class videoModel extends Model{

	//list and page  -- index
	function video_list_pro($bigwhere="", $page_size=20, $sort=" video_sort desc,video_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";
		if(get("k")!="")
		{
			$where .=" and video_name like '%".get("k")."%' ";
		}
		if(get("starttime")!="")
		{
			$where .=" and video_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and video_addtime<".strtotime(get("endtime"))." ";
		}
		
		
		$data["item"]=M("video")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		//echo M()->getLastSql();
		/* for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		} */
		$data["total"] = M("video")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function video_select_pro($bigwhere="",$limit=999999, $sort=" video_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("video")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("video")->where($where.$bigwhere)->count();

		return $data;
	}

	

}
?>