<?php
/**
 *    #Case		bwvip
 *    #Page		blogModel.class.php (消息推送)
 *
 *    @author		Jack
 *    @E-mail		123695069@qq.com
 */
class blogModel extends Model{

	//list and page
	function blog_list_pro($bigwhere="", $page_size=20, $sort=" dateline desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";


		$data["item"]=M("blog")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("blog")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function blog_select_pro($bigwhere="",$limit=999999, $sort=" message_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("blog")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("blog")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>