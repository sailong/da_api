<?php
/**
 *    #Case		bwvip
 *    #Page		ItemAction.class.php (商品)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-11-04
 */
class itemModel extends Model{

	//list and page
	function item_list_pro($bigwhere="", $page_size=20, $sort=" item_sort desc,item_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("k")!="")
		{
			$where .=" and item_name like '%".get("k")."%' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and item_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and item_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("item")->where($where.$bigwhere)->field("item_id,field_uid,parent_id,item_cats_id,item_type,item_type_id,item_name,item_price,item_price_old,item_num,item_num_canbuy,item_num_total,item_pic,item_pic_small,item_pic_bottom,item_intro,item_content,item_sort,item_addtime")->order($sort)->page($page.",".$page_size)->select();
		/* for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		} */
		$data["total"] = M("item")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function item_select_pro($bigwhere="",$limit=999999, $sort=" item_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("item")->where($where.$bigwhere)->field("item_id,field_uid,parent_id,item_cats_id,item_type,item_type_id,item_name,item_price,item_price_old,item_num,item_num_total,item_pic,item_pic_small,item_pic_bottom,item_intro,item_content,item_sort,item_addtime")->order($sort)->limit($limit)->select();
		$data["total"]=M("item")->where($where.$bigwhere)->count();

		return $data;
	}

	//nopage select limit 
	function item_select_page_pro($bigwhere="",$limit=999999, $sort=" item_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;
		$where = " 1 ";
		$page_size = 5;
		if(get("k")!="")
		{
			$where .=" and item_name like '%".get("k")."%' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and item_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and item_addtime<".strtotime(get("endtime"))." ";
		}
		$data["item"]=M("item")->where($where.$bigwhere)->field("item_id,field_uid,parent_id,item_cats_id,item_type,item_type_id,item_name,item_price,item_price_old,item_num,item_num_total,item_pic,item_pic_small,item_pic_bottom,item_intro,item_content,item_sort,item_addtime")->order($sort)->limit($limit)->page($page.",".$page_size)->select();
		
		for($i=0;  $i<count($data['item']); $i++)
		{
			if($data["item"][$i]['item_id']!="")
			{
				$data["item"][$i]['sub']=M('item')->where(' parent_id='.$data["item"][$i]['item_id'].' ')->order($sort)->select();
				/*
				for($j=0; $j<count($data["item"][$i]['sub']); $j++)
				{
					$if_sub_select=M()->query("select admin_role_menu_id from tbl_admin_role_menu where  admin_menu_id='".$data["item"][$i]['sub'][$j]['admin_menu_id']."' ");
					$data["item"][$i]['sub'][$j]['if_sub_select']==$if_sub_select[0]['admin_role_menu_id'];
					//print_r($if_sub_select);
				}
				*/

			}
		}
		
		$data["total"]=M("item")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}
	
	

}
?>