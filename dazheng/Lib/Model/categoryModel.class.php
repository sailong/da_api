<?php
/**
 *    #Case		bwvip
 *    #Page		categoryAction.class.php (门票领取)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-06
 */
class categoryModel extends Model{

	//list and page
	function category_list_pro($bigwhere="", $page_size=20, $sort=" category_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("k")!="")
		{
			$where .=" and category_name like '%".get("k")."%' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and category_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$endtime = strtotime(get("endtime")) + 86400;
			$where .=" and category_addtime<".$endtime." ";
		}

		$data["item"]=M("category")->where($where.$bigwhere)->field("category_id,category_name,field_uid,category_type,category_sort,category_addtime")->order("category_sort asc,".$sort)->page($page.",".$page_size)->select();
		//echo M()->getLastSql();
		//var_dump($data["item"]);die;
		$data["total"] = M("category")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function category_select_pro($bigwhere="",$limit=999999, $sort=" category_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("category")->where($where.$bigwhere)->field("category_id,uid,ticket_id,ticket_type,category_code,category_codepic,category_realname,category_nums,category_sex,category_age,category_address,category_cardtype,category_card,category_mobile,category_imei,category_company,category_company_post,category_status,category_addtime")->order($sort)->limit($limit)->select();
		$data["total"]=M("category")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>