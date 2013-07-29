<?php
/**
 *    #Case		bwvip
 *    #Page		qiutong_orderModel.class.php (球童预约明细)
 *
 *    @author		Jack
 *    @E-mail		123695069@qq.com
 */
class qiutong_orderModel extends Model{

	//list and page
	function qiutong_order_list_pro($bigwhere="", $page_size=20, $sort=" qiutong_order_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;
		$starttime = strtotime(get("starttime"));
        $endtime = strtotime(get("endtime"));
        $endtime = intval($endtime)+86400;
        $where = " 1=1 ";
		$where .= " and a.qiutong_id=b.qiutong_id";
		if(get("starttime")!="")
		{
			$where .=" and a.qiutong_order_addtime>=".$starttime." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and a.qiutong_order_addtime<".$endtime." ";
		}
		if($bigwhere) {
		    $where .=" and ";
		}

		$data["item"]=M("qiutong_order a,tbl_qiutong b")->field('a.uid as user_uid,a.qiutong_order_id,a.field_uid,a.qiutong_order_date,a.qiutong_order_teetime,a.qiutong_order_state,b.uid as qiutong_uid,b.qiutong_number,b.qiutong_name,b.qiutong_name_en,b.qiutong_photo,b.qiutong_content,a.qiutong_order_addtime')->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		//field('a.uid as user_uid,a.qiutong_order_id,a.field_uid,a.qiutong_order_date,a.qiutong_order_teetime,a.qiutong_order_state,b.uid as qiutong_uid,b.qiutong_number,b.qiutong_name,b.qiutong_name_en,b.qiutong_photo,b.qiutong_content');
		//echo M()->getLastSql();die;
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_uid"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_uid"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("qiutong_order a,tbl_qiutong b")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function qiutong_order_select_pro($bigwhere="",$limit=999999, $sort=" qiutong_order_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("qiutong_order")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("qiutong_order")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>