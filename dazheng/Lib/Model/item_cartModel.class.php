<?php
/**
 *    #Case		bwvip
 *    #Page		Item_cartAction.class.php (购物车)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-11-04
 */
class item_cartModel extends Model{

	//list and page
	function item_cart_list_pro($bigwhere="", $page_size=20, $sort=" item_cart_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("k")!="")
		{
			$where .=" and item_name like '%".get("k")."%' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and item_cart_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and item_cart_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("item_cart")->where($where.$bigwhere)->field("item_cart_id,field_uid,uid,parent_id,item_id,item_name,item_buyinfo,item_price,item_num,item_cart_addtime")->order($sort)->page($page.",".$page_size)->select();
		/* for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["uid"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["uid"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
			
		} */
		foreach($data["item"] as $key=>$val)
		{
			$data["item"][$key]['item_price'] = $val['item_price'] ? $val['item_price'] : 0;
			//$data["item"][$key]['item_price_old'] = $val['item_price_old'] ? $val['item_price_old'] : 0;
			$data["item"][$key]['item_price'] = $val['item_price']/100;
			//$data["item"][$key]['item_price_old'] = $val['item_price_old']/100;
		}
		$data["total"] = M("item_cart")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function item_cart_select_pro($bigwhere="",$limit=999999, $sort=" item_cart_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("item_cart")->where($where.$bigwhere)->field("item_cart_id,field_uid,uid,parent_id,item_id,item_name,item_buyinfo,item_price,item_num,item_cart_addtime")->order($sort)->limit($limit)->select();
		$data["total"]=M("item_cart")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>