<?php
/**
 *    #Case		bwvip
 *    #Page		OrderAction.class.php (订单)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-11-04
 */
class orderModel extends Model{

	//list and page
	function order_list_pro($bigwhere="", $page_size=20, $sort=" order_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("k")!="")
		{
			$where .=" and order_name like '%".get("k")."%' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and order_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and order_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("order")->where($where.$bigwhere)->field("order_id,field_uid,uid,order_number,order_sn,item_ids,item_names,order_money,order_status,order_paytime,order_realname,order_mobile,order_post,order_address,order_addtime")->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["uid"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["uid"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("order")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function order_select_pro($bigwhere="",$limit=999999, $sort=" order_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("order")->where($where.$bigwhere)->field("order_id,field_uid,uid,order_number,order_sn,item_ids,item_names,order_money,order_status,order_paytime,order_realname,order_mobile,order_post,order_address,order_addtime")->order($sort)->limit($limit)->select();
		$data["total"]=M("order")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>