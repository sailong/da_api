<?php
/**
 *    #Case		tankuang
 *    #Page		userModel.class.php (用户)
 *
 *    @author		Jack
 *    @E-mail		68779953@qq.com
 */
class userModel extends Model{

	//list and page
	function user_list_pro($bigwhere="", $page_size=20, $sort=" pre_common_member.uid desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("starttime")!="")
		{
			$where .=" and pre_common_member.regdate>=".strtotime(get("starttime"))." ";
		}
		if(get("k")!="")
		{
			$where .=" and pre_common_member_profile.realname like '%".get("k")."%' ";
		}if(get("mobile")!="")
		{$m=get("mobile");
			$where .=" and pre_common_member_profile.mobile = '$m' ";
		}
		if(get("endtime")!="")
		{
			$endtime=strtotime(get("endtime"))+24*3600;;
			$where .=" and pre_common_member.regdate<=$endtime ";
		}

 //联表查询信息
		$db = M( "common_member","pre_" );
		$fix ="pre_";
		$table = $fix."common_member";
		$table2 = $fix."common_member_profile";
		 $data["item"] = $db -> field( "$table.*,$table2.*,$table.regdate as regdate" ) ->
         join( "$table2 on $table.uid=$table2.uid" ) ->
         where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select(); 
		 //print_r($db -> field()); 
		$data["total"] = $db -> field( "$table.*,$table2.*,$table.regdate as regdate" ) ->
         join( "$table2 on $table.uid=$table2.uid" ) ->
         where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function user_select_pro($bigwhere="",$limit=999999, $sort=" regdate desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("common_member","pre_")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("common_member","pre_")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>