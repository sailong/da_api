<?php
/**
 *    #Case		bwvip
 *    #Page		BaomingAction.class.php (报名)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2014-02-13
 */
class zimeiti_applyModel extends Model{

	//list and page
	function zimeiti_apply_list_pro($bigwhere="", $page_size=20, $sort=" zimeiti_apply_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("k")!="")
		{
			$where .=" and field_uid like '%".get("k")."%' ";
		}
		if(get("mobile")!="")
		{
			$where .=" and zimeiti_apply_mobile like '%".get("mobile")."%' ";
		}
		if(get("uid")!="")
		{
			$where .=" and uid='".get("uid")."' ";
		}
		if(get("zimeiti_apply_role")!="")
		{
			$where .=" and zimeiti_apply_role='".get("zimeiti_apply_role")."' ";
		}
		if(get("fenzhan_id")!="")
		{
			$where .=" and fenzhan_id='".get("fenzhan_id")."' ";
		}
		if(get("zimeiti_apply_status")!="")
		{
			$where .=" and zimeiti_apply_status='".get("zimeiti_apply_status")."' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and zimeiti_apply_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and zimeiti_apply_addtime<".strtotime(get("endtime"))." ";
		}
		
		$data["item"]=M("zimeiti_apply")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		//->field("baoming_id,uid,event_id,fenzhan_id,baoming_realname,baoming_mobile,baoming_email,baoming_country,baoming_chadian,baoming_sex,baoming_is_waika,baoming_waika_fenzhan_id,baoming_note,baoming_source,baoming_status,baoming_addtime")
		//echo M()->getLastSql();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["uid"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["uid"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
			/* if($data["item"][$i]["event_id"]!="")
			{
				$user=M()->query("select event_name from ".C("db_prefix")."event where  event_id='".$data["item"][$i]["event_id"]."' ");
				$data["item"][$i]["event_name"]=$user[0]["event_name"];
			} */
			if($data["item"][$i]["field_uid"]!="")
			{
				$user=M()->query("select field_name from tbl_field where field_uid='".$data["item"][$i]["field_uid"]."' ");
				$data["item"][$i]["field_name"]=$user[0]["field_name"];
			}
			/* if($data["item"][$i]["fenzhan_id"]!="")
			{
				$user=M()->query("select fenzhan_name from ".C("db_prefix")."fenzhan where  fenzhan_id='".$data["item"][$i]["fenzhan_id"]."' ");
				$data["item"][$i]["fenzhan_name"]=$user[0]["fenzhan_name"];
			} */
		}
		$data["total"] = M("zimeiti_apply")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function zimeiti_apply_select_pro($bigwhere="",$limit=999999, $sort=" zimeiti_apply_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("zimeiti_apply")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		//->field("baoming_id,uid,event_id,fenzhan_id,baoming_realname,baoming_mobile,baoming_email,baoming_country,baoming_chadian,baoming_sex,baoming_is_waika,baoming_waika_fenzhan_id,baoming_note,baoming_source,baoming_status,baoming_addtime")
		$data["total"]=M("zimeiti_apply")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}