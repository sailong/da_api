<?php
/**
 *    #Case		bwvip
 *    #Page		field_aboutModel.class.php (球场介绍)
 *
 *    @author		Jack
 *    @E-mail		123695069@qq.com
 */
class field_aboutModel extends Model{

	//list and page
	function field_about_list_pro($bigwhere="", $page_size=20, $sort=" about_sort asc,about_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";
		
		if(get('about_type'))
		{
			$where .=" and about_type='".get('about_type')."' ";
		}
		
		if(get('language'))
		{
			$where .=" and language='".get('language')."' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and about_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and about_addtime<".strtotime(get("endtime"))." ";
		}
	    if(get('k'))
		{
			$where .=" and about_name like '%".get('k')."%' ";
		}

		$data["item"]=M("field_about")->where($where.$bigwhere)->field("about_id,category_id,field_uid,about_name,about_type,about_content,about_tel,about_tel2,about_replynum,about_sort,about_addtime,about_pic,about_more")->order($sort)->page($page.",".$page_size)->select();
		//echo M('field_about')->getLastSql();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
			if($data['item'][$i]['about_pic'])
			{
				//$extname=end(explode(".",$data['item'][$i]['about_pic']));
				$data['item'][$i]['about_pic_small']=$data['item'][$i]['about_pic'];//$data['item'][$i]['about_pic'].'_small.'.$extname;
			}
		}
		$data["total"] = M("field_about")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function field_about_select_pro($bigwhere="",$limit=999999, $sort=" about_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("field_about")->where($where.$bigwhere)->field("about_id,field_uid,about_name,about_type,about_content,about_tel,about_tel2,about_replynum,about_sort,about_addtime,about_pic,about_more")->order($sort)->limit($limit)->select();
		$data["total"]=M("field_about")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>