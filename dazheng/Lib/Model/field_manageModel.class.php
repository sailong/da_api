<?php
/**
 *    #Case		tankuang
 *    #Page		userModel.class.php (用户)
 *
 *    @author		Jack
 *    @E-mail		68779953@qq.com
 */
class field_manageModel extends Model{

	//list and page
	function field_list_pro($bigwhere="", $page_size=20, $sort="field_addtime desc") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("starttime")!="")
		{
			$where .=" and field_addtime>=".strtotime(get("starttime"))." ";
		}
		if(get("k")!="")
		{
			//$where .=" and field_name like '%".get("k")."%' ";
			$where .=" and field_uid='".get("k")."' ";
		}
		if(get("endtime")!="")
		{
			$endtime=strtotime(get("endtime"))+24*3600;;
			$where .=" and field_addtime<=$endtime ";
		}

 //联表查询信息
		$data["item"]=M("field")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		$data["total"]=M("field")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function field_select_pro($bigwhere="",$limit=999999, $sort=" field_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("field")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("field")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>