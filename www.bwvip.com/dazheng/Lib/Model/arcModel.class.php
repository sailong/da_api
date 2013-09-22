<?php
/**
 *    #Case		tankuang
 *    #Page		arcModel.class.php (文章)
 *
 *    @author		Jack
 *    @E-mail		68779953@qq.com
 */
class arcModel extends Model{

	//list and page  -- admin
	function arc_admin_list_pro($bigwhere="", $page_size=20, $sort=" arc_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("k"))
		{
			$where .=" and arc_name like '%".get("k")."%' ";
		}

		if(get("arc_state")!="")
		{
			$where .=" and arc_state='".get("arc_state")."' ";
		}
		
		if(get("language")!="")
		{
			$where .=" and language='".get("language")."' ";
		}
		

		if(get("arctype_id"))
		{
			$where .=" and arctype_id='".intval(get("arctype_id"))."'  ";
		}

		if(get("starttime")!="")
		{
			$where .=" and arc_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and arc_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("arc")->field("arc_id,arc_name,arctype_id,arc_viewtype,arc_top,arc_pic,arc_sort,arc_state,arc_addtime,arc_statetime,is_video")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
			
			if($data["item"][$i]["arctype_id"]!="")
			{
				$type=M()->query("select arctype_name from ".C("db_prefix")."arctype where  arctype_id='".$data["item"][$i]["arctype_id"]."' ");
				$data["item"][$i]["arctype_name"]=$type[0]["arctype_name"];
			}

		}
		$data["total"] = M("arc")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//list and page  -- index
	function arc_list_pro($bigwhere="", $page_size=20, $sort=" arc_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";
		

		if(get("starttime")!="")
		{
			$where .=" and arc_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and arc_addtime<".strtotime(get("endtime"))." ";
		}
		
		
		$data["item"]=M("arc")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("arc")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function arc_select_pro($bigwhere="",$limit=999999, $sort=" arc_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("arc")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("arc")->where($where.$bigwhere)->count();

		return $data;
	}


	function arc_detail_side($arctype_id="",$limit=10,$sort=" arc_addtime desc ")
	{
		
		$arctype_data=M("arctype")->where("arctype_id=".$arctype_id)->find();
  
		$sub_arctype=D("arctype")->arctype_select_pro(" and arctype_parent_id='".$arctype_data['arctype_parent_id']."'  and arctype_type='A'  ");
		for($j=0; $j<count($sub_arctype['item']); $j++)
		{
			$str .='<dl class="newsRightdl">';
			$str .='<dt><a target="_blank" href="'.U('home/arc/arc_list',array('arctype_id'=>$sub_arctype['item'][$j]['arctype_id'])).'">'.$sub_arctype['item'][$j]['arctype_name'].' >></a></dt>';
	
			$data=M("arc")->where(" arc_state=1 and arctype_id='".$sub_arctype['item'][$j]['arctype_id']."' ")->order($sort)->limit($limit)->select();
			for($i=0; $i<count($data); $i++)
			{
				$str .='<dd><a href="'.U('home/arc/arc_detail',array('arc_id'=>$data[$i]['arc_id'])).'">'.$data[$i]['arc_name'].'</a></dd> ';
			}
	
			$str .='</dl>';
		}
		

		return $str;
	
	}


	
	

}
?>