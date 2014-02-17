<?php
/*
*
* bwvip.com
* 商城
*
*/
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}



$ac=$_G['gp_ac'];

//page 1
$page=$_G['gp_page'];
if(!$page)
{
	$page=1;
}
$page_size=$_G['gp_page_size'];
if(!$page_size)
{
	$page_size=10;
}
if($page==1)
{
	$page_start=0;
}
else
{
	$page_start=($page-1)*($page_size);
}
//page 2
$page2=$_G['gp_page'];
if(!$page2)
{
	$page2=1;
}
$page_size2=$_G['gp_page_size'];
if(!$page_size2)
{
	$page_size2=9;
}
if($page2==1)
{
	$page_start2=0;
}
else
{
	$page_start2=($page2-1)*($page_size2);
}

$root_path = dirname(dirname(dirname(dirname(__FILE__))));






//商品分类列表
if($ac == 'cats_list')
{
	$field_uid=$_G['gp_field_uid'];
	$parent_id=$_G['gp_parent_id'];
	
	if($field_uid!="")
	{
		$sql .=" and field_uid='".$field_uid."' ";
	}
	
	if($parent_id)
	{
		$sql .=" and parent_id='".$parent_id."' ";
	}
	else
	{
		$sql .=" and parent_id='0' ";
	}
	
	
	$list=DB::query("select item_cats_id,parent_id,is_parent,item_cats_name,item_cats_pic from tbl_item_cats where 1 ".$sql." order by item_cats_sort desc  ");
	while($row=DB::fetch($list))
	{
		
		if($row['item_cats_pic'])
		{
			$row['item_cats_pic']=$site_url."/".$row['item_cats_pic'];
			$row['item_cats_pic_info']=getimagesize($row['item_cats_pic']);
		}
		else
		{
			$row['item_cats_pic_info']=null;
		}
		
		$list_data[]=array_default_value($row,array('item_cats_pic_info'));
		
	}
	
	
	$data['title']		= "data";
	$data['data']     =  $list_data;
	
	if(!empty($list_data))
	{
		api_json_result(1,0,$app_error['event']['10502'],$data);
	}
	else
	{
		api_json_result(1,1,"没有找到分类",$data);
	}

	
}






//商品列表
if($ac == 'item_list')
{
	$field_uid=$_G['gp_field_uid'];
	$item_cats_id=$_G['gp_item_cats_id'];
	
	if($field_uid!="")
	{
		$sql .=" and field_uid='".$field_uid."' ";
	}
	
	if($item_cats_id)
	{
		$sql .=" and item_cats_id='".$item_cats_id."' ";
	}
	
	
	$total=DB::result_first("select count(item_id) from tbl_item where 1 ".$sql." ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{
	
		$list=DB::query("select item_id,parent_id,item_type,item_type_id,item_name,item_pic_small,item_intro from tbl_item where 1 ".$sql." order by item_sort desc  limit $page_start,$page_size ");
		while($row=DB::fetch($list))
		{
			
			if($row['item_pic_small'])
			{
				$row['item_pic_small']=$site_url."/".$row['item_pic_small'];
				$row['item_pic_small_info']=getimagesize($row['item_pic_small']);
			}
			else
			{
				$row['item_pic_small_info']=null;
			}
			
			$list_data[]=array_default_value($row,array('item_pic_small_info'));
			
		}
	}
	
	$data['title']		= "data";
	$data['data']     =  $list_data;
	
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
	
}




//商品详细
if($ac == 'item_detail')
{
	$field_uid=$_G['gp_field_uid'];
	$item_id=$_G['gp_item_id'];
	$pic_width=$_G['gp_pic_width'];
	
	if($field_uid!="")
	{
		$sql .=" and field_uid='".$field_uid."' ";
	}
	
	if($item_id)
	{
		
		$item_info=DB::fetch_first("select item_id,parent_id,item_type,item_type_id,item_name,item_price,item_price_old,item_num,item_num_canbuy,item_num_total,item_pic_bottom,item_pic,item_intro,item_content,item_addtime from tbl_item where item_id='".$item_id."' limit 1 ");
		
		$item_info['item_price']=$item_info['item_price']/100;
		$item_info['item_price_old']=$item_info['item_price_old']/100;
		$item_info['item_addtime']=date("Y-m-d",$item_info['item_addtime']);
		
		if($item_info['item_content'])
		{
			$item_info['item_content']=str_replace("src=\"/Public/editor/attached/image","src=\"".$site_url."/Public/editor/attached/image",$item_info['item_content']);
		}
		if($pic_width)
		{
			$item_info['item_content']=str_replace("<img","<img style=\"width:".$pic_width."px; margin:0 auto;\" ",$item_info['item_content']);
		}
		
		if($item_info['item_pic'])
		{
			$item_info['item_pic']=$site_url."/".$item_info['item_pic'];
			$item_info['item_pic_info']=getimagesize($item_info['item_pic']);
		}
		else
		{
			$item_info['item_pic_info']=null;
		}
		
		if($item_info['item_pic_bottom'])
		{
			$item_info['item_pic_bottom']=$site_url."/".$item_info['item_pic_bottom'];
			$item_info['item_pic_bottom_info']=getimagesize($item_info['item_pic_bottom']);
		}
		else
		{
			$item_info['item_pic_bottom_info']=null;
		}
		
		
		if($item_info['item_id'])
		{
			$sub_list=DB::query("select item_id,item_name,item_price,item_num,item_num_canbuy,item_intro from tbl_item where (parent_id='".$item_info['item_id']."' or item_id='".$item_info['item_id']."') and item_num>0 order by item_sort desc ");
			while($row=DB::fetch($sub_list))
			{
				$row['item_price']=$row['item_price']/100;
				
				$item_info['sub_list'][]=array_default_value($row);
			}
		}
		else
		{
			$item_info['sub_list']=null;
		}

		
		$data['title']	= "data";
		$data['data']   =  array_default_value($item_info,array('item_pic_info','item_pic_bottom_info','sub_list'));
		api_json_result(1,0,$app_error['event']['10502'],$data);
	
	
	}
	else
	{
		api_json_result(1,1,"没有找到该商品",$data);
	}
	
	

	
}





//加入购物车
if($ac == 'item_cart_insert')
{
	$field_uid=$_G['gp_field_uid'];
	if(!$field_uid)
	{
		$field_uid=0;
	}
	$uid=$_G['gp_uid'];
	
	$item_ids=explode(",",$_G['gp_ids']);
	$item_nums=explode(",",$_G['gp_nums']);
	if(count($item_ids) == count($item_nums))
	{
		for($i=0; $i<count($item_ids); $i++)
		{
			if($item_ids[$i])
			{
				$item_info=DB::fetch_first("select item_id,item_price,item_name,parent_id from tbl_item where item_id='".$item_ids[$i]."' ");
				if($item_info['item_id'])
				{
					$cart_id=DB::result_first("select item_cart_id from tbl_item_cart where item_id='".$item_ids[$i]."' and uid='".$uid."' ");
					if(!$cart_id)
					{
						$sql=" insert into tbl_item_cart (field_uid,uid,parent_id,item_id,item_name,item_buyinfo,item_price,item_num,item_cart_addtime) values('".$field_uid."','".$uid."','".$item_info['parent_id']."','".$item_info['item_id']."','".$item_info['item_name']."','','".$item_info['item_price']."','".$item_nums[$i]."','".time()."')  ";
						$res=DB::query($sql);
					}
					else
					{
						$sql=" update tbl_item_cart set item_num=item_num+".$item_nums[$i]." where item_cart_id='".$cart_id."' ";
						$res=DB::query($sql);
					}
				}
			}
		}
		api_json_result(1,0,'操作成功',$data);
	}
	else
	{
		api_json_result(1,1,'商品数和购买数不符，不能加入',$data);
	}
	
	
	

}




//购物车删除一条记录
if($ac == 'item_cart_delete')
{

	$uid=$_G['gp_uid'];
	$item_cart_id=$_G['gp_item_cart_id'];
	
	if($item_cart_id)
	{
		$sql=" delete from tbl_item_cart where item_cart_id='".$item_cart_id."' and uid='".$uid."' ";
		$res=DB::query($sql);
		
		api_json_result(1,0,'操作成功',$data);
	}
	else
	{
		api_json_result(1,1,'购物车ID不能为空',$data);
	}
	
}



//购物车列表
if($ac=="item_cart")
{
	$field_uid=$_G['gp_field_uid'];
	if(!$field_uid)
	{
		$field_uid=0;
	}
	$uid=$_G['gp_uid'];
	
	$list=DB::query("select parent_id from tbl_item_cart where field_uid='".$field_uid."' and uid='".$uid."' group by parent_id order by item_cart_addtime desc ");
	while($row=DB::fetch($list))
	{
		$parent_item_info=DB::fetch_first("select item_id,item_name,item_intro,item_pic_small from tbl_item where item_id='".$row['parent_id']."' ");
		$row['item_id']=$parent_item_info['item_id'];
		$row['item_name']=$parent_item_info['item_name'];
		$row['item_intro']=$parent_item_info['item_intro'];
		
		if($parent_item_info['item_pic_small'])
		{
			$row['item_pic_small']=$site_url."/".$parent_item_info['item_pic_small'];
			$row['item_pic_small_info']=getimagesize($row['item_pic_small']);
		}
		else
		{
			$row['item_pic_small']='';
			$row['item_pic_small_info']=null;
		}
		
		
		$row['sub_list']=null;
		if($row['parent_id'])
		{
			$sub_list=DB::query("select item_cart_id,item_id,item_name,item_price,item_num as item_num_buy,(select item_num_canbuy from tbl_item where item_id=tbl_item_cart.item_id) as item_num_canbuy,(select item_num from tbl_item where item_id=tbl_item_cart.item_id) as item_num,(select item_intro from tbl_item where item_id=tbl_item_cart.item_id) as item_intro from tbl_item_cart where field_uid='".$field_uid."' and uid='".$uid."' and parent_id='".$row['parent_id']."' ");
			while($sub_row=DB::fetch($sub_list))
			{
				$sub_row['item_price']=$sub_row['item_price']/100;
				
				$row['sub_list'][]=array_default_value($sub_row);
			}
		}
		
		
		$list_data[]=array_default_value($row,array('item_pic_small_info','sub_list'));
		
		
	}
	
	
	$data['title']	= "data";
	$data['data']   =  $list_data;
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
	
}





//填写订单信息
if($ac=='new_order')
{
	$field_uid=$_G['gp_field_uid'];
	if(!$field_uid)
	{
		$field_uid=0;
	}
	$uid=$_G['gp_uid'];
	
	$item_cart_ids=explode(",",$_G['gp_item_cart_ids']);
	$item_ids=explode(",",$_G['gp_ids']);
	$item_nums=explode(",",$_G['gp_nums']);

	if(count($item_cart_ids) == count($item_nums))
	{
		//change status
		for($i=0; $i<count($item_cart_ids); $i++)
		{
			$up=DB::query("update tbl_item_cart set item_cart_status=1,item_num='".$item_nums[$i]."' where item_cart_id='".$item_cart_ids[$i]."' ");
			
		}
		
		$order_money=0;
		//item_list
		$order_item_ids=array();
		$order_item_names=array();
		$list=DB::query("select parent_id from tbl_item_cart where field_uid='".$field_uid."' and uid='".$uid."' and item_cart_status=1 group by parent_id order by item_cart_addtime desc ");
		while($row=DB::fetch($list))
		{
			$parent_item_info=DB::fetch_first("select item_id,item_name,item_intro,item_pic_small from tbl_item where item_id='".$row['parent_id']."' ");
			$row['item_id']=$parent_item_info['item_id'];
			$row['item_name']=$parent_item_info['item_name'];
			$row['item_intro']=$parent_item_info['item_intro'];
			
			if($parent_item_info['item_pic_small'])
			{
				$row['item_pic_small']=$site_url."/".$parent_item_info['item_pic_small'];
				$row['item_pic_small_info']=getimagesize($row['item_pic_small']);
			}
			else
			{
				$row['item_pic_small']='';
				$row['item_pic_small_info']=null;
			}
			
			
			$row['sub_list']=null;
			if($row['parent_id'])
			{
				$sub_list=DB::query("select item_cart_id,item_id,item_name,item_price,item_num as item_num_buy,(select item_num_canbuy from tbl_item where item_id=tbl_item_cart.item_id) as item_num_canbuy,(select item_num from tbl_item where item_id=tbl_item_cart.item_id) as item_num from tbl_item_cart where field_uid='".$field_uid."' and uid='".$uid."' and parent_id='".$row['parent_id']."' and item_cart_status=1  ");
				while($sub_row=DB::fetch($sub_list))
				{
				
					$order_money =$order_money+$sub_row['item_price'];
					
					
					$sub_row['item_price']=$sub_row['item_price']/100;
					
					$order_item_ids[]=$sub_row['item_id'];
					$order_item_names[]=$sub_row['item_name'];
					
					
					$row['sub_list'][]=array_default_value($sub_row);
				}
			}
			
			
			$item_list[]=array_default_value($row,array('item_pic_small_info','sub_list'));
			
		}
		
		$order_item_idstr=implode(',',$order_item_ids);
		$order_item_namestr=implode(',',$order_item_names);
		
		//add order 
		$sj_num=mt_rand(10000000,99999999);
		$order_sn=date('Ymd',time())."-".$config_shanghuhao."-".$sj_num;
		$order_number=time();
		
		$aaa=DB::query("insert into tbl_order (field_uid,uid,order_number,order_sn,item_ids,item_names,order_money,order_status,order_addtime) values ('".$field_uid."','".$uid."','".$order_number."','".$order_sn."','".$order_item_idstr."','".$order_item_namestr."','".$order_money."','-1','".time()."') ");
		
		
		//field s
		$field_list[0]['name']="realname";
		$field_list[0]['name_cn']="姓名";
		$field_list[0]['type']="input";
		$field_list[0]['max_size']="50";
		
		$field_list[1]['name']="mobile";
		$field_list[1]['name_cn']="手机";
		$field_list[1]['type']="input";
		$field_list[1]['max_size']="50";
		
		$field_list[2]['name']="post";
		$field_list[2]['name_cn']="邮编";
		$field_list[2]['type']="input";
		$field_list[2]['max_size']="50";
		
		$field_list[3]['name']="address";
		$field_list[3]['name_cn']="地址";
		$field_list[3]['type']="input";
		$field_list[3]['max_size']="50";
	
		$data['title']	= "data";
		$data['data']   =  array(
			'item_list'=>$item_list,
			'field_list'=>$field_list,
			'order_money'=>$order_money,
			'order_sn'=>$order_sn
		);
		api_json_result(1,0,$app_error['event']['10502'],$data);
		
		
	}
	else
	{
		api_json_result(1,1,'商品数和购买数不符，不能加入',$data);
	}
	
	
	
	
}



//提交订单
if($ac=='new_order_save')
{
	$field_uid=$_G['gp_field_uid'];
	if(!$field_uid)
	{
		$field_uid=0;
	}
	$uid=$_G['gp_uid'];
	$order_sn=$_G['gp_order_sn'];
	$names=explode("^",$_G['gp_names'])
	$values=explode("^",$_G['gp_values'])
	
	if($order_sn)
	{
		/*
		if(count($names) == count($values))
		{
			$sql_str="";
			for($i=0; $i<count($names); $i++)
			{
				if($i==0)
				{
					$sql_str .=" ,".$names[$i]."='".$values[$i]."' ";
				}
				else
				{
					$sql_str .=" ".$names[$i]."='".$values[$i]."' ";
				}
			}
		}
		
		//$up=DB::query("update tbl_order set order_status=0,order_sn='".$order_sn."'  ".$sql_str."   where order_sn='".$order_sn."' ");
		*/
		echo "update tbl_order set order_status=0,order_sn='".$order_sn."'  ".$sql_str."   where order_sn='".$order_sn."' ";
		
		api_json_result(1,0,'提交成功，现在跳转支付流程',$data);
	}
	else
	{
		api_json_result(1,0,'订单编号必须填写',$data);
	}
	
	
}



//订单列表（历史记录）
if($ac=="order_list")
{
	
}



?>