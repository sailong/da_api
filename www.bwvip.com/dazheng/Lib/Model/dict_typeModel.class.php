<?php
/**
 *    #Case		kalatai
 *    #Page		dict_typeModel.class.php (字典分类)
 *
 *    @author		Jack
 *    @E-mail		123695069@qq.com
 */
class dict_typeModel extends Model{

	//list and page
	function dict_type_list_pro($bigwhere="", $page_size=20, $sort=" dict_type_id desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("starttime")!="")
		{
			$where .=" and dict_type_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and dict_type_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("dict_type")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("dict_type")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function dict_type_select_pro($bigwhere="",$limit=999999, $sort=" dict_type_id desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("dict_type")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("dict_type")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>