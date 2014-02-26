<?php
/**
 *    #Case		tankuang
 *    #Page		msg_logModel.class.php (短信)
 *
 *    @author		Jack
 *    @E-mail		68779953@qq.com
 */
class msg_taskModel extends Model{

	//list and page
	function msg_task_list_pro($bigwhere="", $page_size=20, $sort=" msg_task_id desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";
		
		if(get("field_uid"))
		{
			$where .=" and field_uid='".get("field_uid")."' ";
		}

		if(get("mobile"))
		{
			$where .=" and mobile like '%".get("mobile")."%' ";
		}
		/* if(get("code"))
		{
			$where .=" and code='".get("code")."' ";
		}
		if(get("k"))
		{
			$where .=" and content like '%".get("k")."%' ";
		} */

		if(get("starttime")!="")
		{
			$where .=" and msg_task_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and msg_task_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("msg_task")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		//echo M()->getLastSql();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["msg_task_id"]!="")
			{
				$msg_log=M()->query("select code from tbl_msg_log where mobile='".$data["item"][$i]["mobile"]."' order by msg_log_id desc limit 1");
				$data["item"][$i]["code"]=$msg_log[0]["code"];
			}
		}
		$data["total"] = M("msg_task")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();
		
		return $data;
	}


	//nopage select limit 
	function msg_task_select_pro($bigwhere="",$limit=999999, $sort=" msg_task_id desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("msg_task")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("msg_task")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>