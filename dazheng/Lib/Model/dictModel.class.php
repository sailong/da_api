<?php
/**
 *    #Case		kalatai
 *    #Page		DictModel.class.php (字典)
 *
 *    @author		Jack
 *    @E-mail		123695069@qq.com
 */
class DictModel extends Model{

	//list and page
	function dict_list_pro($bigwhere="", $page_size=50, $sort=" dict_sort asc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("dict_type_id")!="")
		{
			$where .=" and dict_type=".get("dict_type_id")." ";
		}

		if(get("starttime")!="")
		{
			$where .=" and dict_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and dict_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("dict")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("dict")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function dict_select_pro($bigwhere="",$limit=999999, $sort=" dict_sort asc ") 
	{
		
		$where = " 1 ";

		$data=M("dict")->where($where.$bigwhere)->order($sort)->limit($limit)->select();

		return $data;
	}


	//nopage select limit and count
	function dict_select_count_pro($bigwhere="",$limit=999999, $sort=" dict_sort asc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("dict")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("dict")->where($where.$bigwhere)->count();

		return $data;
	}


	//detail
	function dict_detail_pro($bigwhere="") 
	{
		
		$where = " 1 ";
		$data=M("dict")->where($where.$bigwhere)->find();

		return $data;
	}


	

}
?>