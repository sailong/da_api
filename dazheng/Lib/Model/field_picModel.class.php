<?php
/**
 *    #Case		tankuang
 *    #Page		adModel.class.php (广告)
 *
 *    @author		Jack
 *    @E-mail		68779953@qq.com
 */
class field_picModel extends Model{

	//list and page
	function field_pic_list_pro($bigwhere="", $page_size=20, $sort=" field_pic_sort desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";
		
		/* if(get("field_uid"))
		{
			$where .=" and field_uid='".get("field_uid")."' ";
		} */
		if(get("k") !== '')
		{
			$where .=" and field_uid='".get("k")."' ";
		}

	/* 	if(get("ad_app"))
		{
			$where .=" and ad_app='".get("ad_app")."' ";
		}
		if(get("ad_type"))
		{
			$where .=" and ad_type='".get("ad_type")."' "; 
		}*/
		/* if(get("k"))
		{
			$where .=" and field_pic_name like '%".get("k")."%' ";
		} */

		if(get("starttime")!="")
		{
			$where .=" and field_pic_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and field_pic_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("field_pic")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		//echo M()->getLastSql();
	/* 	for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		} */
		$data["total"] = M("field_pic")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function field_pic_select_pro($bigwhere="",$limit=999999, $sort=" field_pic_sort desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("field_pic")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("field_pic")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>