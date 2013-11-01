<?php
/**
 *    #Case		bwvip
 *    #Page		app_versionModel.class.php (客户端版本)
 *
 *    @author		Jack
 *    @E-mail		123695069@qq.com
 */
class app_versionModel extends Model{

	//list and page
	function app_version_list_pro($bigwhere="", $page_size=20, $sort=" app_version_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";
		
		if(get("app_version_type")!="")
		{
			$where .=" and app_version_type='".get("app_version_type")."' ";
		}
		
		if(get("k")!="")
		{
			$where .=" and app_version_name like '%".get("k")."%' ";
		}
		if(get("starttime")!="")
		{
			$where .=" and app_version_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and app_version_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("app_version")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("app_version")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function app_version_select_pro($bigwhere="",$limit=999999, $sort=" app_version_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("app_version")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("app_version")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>