<?php
/**
 *    #Case		bwvip
 *    #Page		categoryAction.class.php (分类)
 *
 *    @Author		
 *    @E-mail		123695069@qq.com
 *    @Date			2013-08-06
 */
class categoryAction extends field_publicAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function category()
	{
		$field_uid = $_SESSION['field_uid'];
		$list=D("category")->category_list_pro(" and field_uid='{$field_uid}'");
		//var_dump($list);die;
		$category_list = category_father('key_val');
		
		$this->assign('category_father',$category_list);
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","分类管理");
    	$this->display();
	}

	public function category_add()
	{
		
		$this->assign('category_father',category_father());
		$this->assign("page_title","分类管理");
    	$this->display();
	}
	/* //获取随机字符串
	public function get_randmod_str(){
		$str = 'abcdABCefgD69EFhigkGHI7nm8JKpqMNrs3PQRtuS5vw4TxyU1VWzXYZ20';
		$len = strlen($str); //得到字串的长度;

		//获得随即生成的积分卡号
		$s = rand(0, 1);
		$serial = '';

		for($s=1;$s<=10;$s++)
		{
		   $key     = rand(0, $len-1);//获取随机数
		   $serial .= $str[$key];
		}

	   //strtoupper是把字符串全部变为大写
	   $serial = strtoupper(substr(md5($serial.time()),10,10));
	   if($s)
	   {
		  $serial = strtoupper(substr(md5($serial),mt_rand(0,22),10));
	   }
	   
	   return $serial;
	} */

	public function category_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			
			$data["field_uid"]=post("field_uid");
			
			$data["category_name"]=post("category_name");
			$data["category_type"]=post("category_type");
			$data["category_sort"]=post("category_sort");
			$data["category_addtime"]=time();
			
			$list=M("category")->add($data);
			$this->success("添加成功",U('field/category/category'));
		}
		else
		{
			$this->error("不能重复提交",U('field/category/category_add'));
		}

	}


	public function category_edit()
	{
		if(intval(get("category_id"))>0)
		{
			$data=M("category")->where("category_id=".intval(get("category_id")))->find();
			
			$this->assign("data",$data);
			$this->assign('category_father',category_father());
			$this->assign("page_title","分类管理");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function category_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["field_uid"]=post("field_uid");
			$data["category_id"]=post("category_id");
			$data["category_name"]=post("category_name");
			$data["category_type"]=post("category_type");
			$data["category_sort"]=post("category_sort");
			
			$list=M("category")->save($data);
			$this->success("修改成功",U('field/category/category'));			
		}
		else
		{
			$this->error("不能重复提交",U('field/category/category'));
		}

	}

	public function category_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("category")->where("category_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function category_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_user_ticket set user_ticket_state=1 where user_ticket_id=".$ids_arr[$i]." ");
			}
			if($res)
			{
				echo "succeed^审核成功";
			}
			else
			{
				echo "error^审核失败";
			}			
			
		}
	}

	public function category_detail()
	{
		if(intval(get("category_id"))>0)
		{
			$data=M("category")->where("category_id=".intval(get("user_ticket_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["user_ticket_name"]."门票领取");
				$this->display();
			}
			else
			{
				$this->error("您该问的信息不存在");	
			}
			
		}
		else
		{
			$this->error("您该问的信息不存在");
		}

	}


	

}
?>