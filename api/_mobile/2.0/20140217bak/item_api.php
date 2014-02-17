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

//当前文件所在路径
$current_path = dirname(__FILE__); 


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
	
	
	$total=DB::result_first("select count(item_id) from tbl_item where parent_id=0 ".$sql." ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{
	
		$list=DB::query("select item_id,parent_id,item_type,item_type_id,item_name,item_pic_small,item_intro from tbl_item where  parent_id=0 ".$sql." order by item_sort desc  limit $page_start,$page_size ");
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
			$sub_list=DB::query("select item_id,item_name,item_price,item_num,item_num_canbuy,item_intro from tbl_item where (parent_id='".$item_info['item_id']."') and item_num>0 order by item_sort desc ");
			while($row=DB::fetch($sub_list))
			{
				$row['item_price']=(string)($row['item_price']/100);
				
				
				$item_info['sub_list'][]=array_default_value($row,array('item_price'));
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
					$cart_id=DB::result_first("select item_cart_id from tbl_item_cart where item_id='".$item_ids[$i]."' and uid='".$uid."' and item_cart_status=0 ");
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
	$item_cart_ids=explode(",",$_G['gp_item_cart_ids']);
	
	for($i=0; $i<count($item_cart_ids); $i++)
	{
		if($item_cart_ids[$i])
		{
			$sql=" delete from tbl_item_cart where item_cart_id='".$item_cart_ids[$i]."' and uid='".$uid."' ";
			$res=DB::query($sql);
		}
	}
	
	api_json_result(1,0,'操作成功',$data);
	
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
	
	$list=DB::query("select parent_id from tbl_item_cart where field_uid='".$field_uid."' and uid='".$uid."' and parent_id>0 and item_cart_status=0 group by parent_id order by item_cart_addtime desc ");
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
			$sub_list=DB::query("select item_cart_id,item_id,item_name,item_price,item_num as item_num_buy,(select item_num_canbuy from tbl_item where item_id=tbl_item_cart.item_id) as item_num_canbuy,(select item_num from tbl_item where item_id=tbl_item_cart.item_id) as item_num,(select item_intro from tbl_item where item_id=tbl_item_cart.item_id) as item_intro from tbl_item_cart where field_uid='".$field_uid."' and uid='".$uid."' and parent_id='".$row['parent_id']."' and item_cart_status=0 ");
			while($sub_row=DB::fetch($sub_list))
			{
				$sub_row['item_price']=(string)$sub_row['item_price']/100;
				
				
				$row['sub_list'][]=array_default_value($sub_row,array('item_price'));
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
	$old_order_sn=$_G['gp_old_order_sn'];
	
	$item_cart_ids=explode("','",$_G['gp_item_cart_ids']);
	$item_ids=explode(",",$_G['gp_ids']);
	$item_nums=explode(",",$_G['gp_nums']);
	
	if(count($item_ids) == count($item_nums))
	{
		
		//如果没有传item_cart_id，就先加入购物车
		if(!$item_cart_ids[0])
		{
		
			for($i=0; $i<count($item_ids); $i++)
			{
				if($item_ids[$i])
				{
					$item_info=DB::fetch_first("select item_id,item_price,item_name,parent_id from tbl_item where item_id='".$item_ids[$i]."' ");
					if($item_info['item_id'])
					{
						$cart_id=DB::result_first("select item_cart_id from tbl_item_cart where item_id='".$item_ids[$i]."' and uid='".$uid."' and item_cart_status=0 ");
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
			
			
			//getlist
			$ids=$_G['gp_ids'];
			if($ids)
			{
				$item_cart_ids=array();
				$new_cart_list=DB::query("select item_cart_id from tbl_item_cart where item_id in (".$ids.") and uid='".$uid."' and item_cart_status=0  ");
				while($row=DB::fetch($new_cart_list))
				{
					$item_cart_ids[]=$row['item_cart_id'];
				}
			}
			
	
			
		}
		//end
		
		
		//更改cart_num 数量
		for($i=0; $i<count($item_cart_ids); $i++)
		{
			$up=DB::query("update tbl_item_cart set item_num='".$item_nums[$i]."' where item_cart_id='".$item_cart_ids[$i]."' ");
		}
		
		
		$get_item_cart_ids=$item_cart_ids;
		$item_cart_ids_str=implode("','",$get_item_cart_ids);

		$order_money=0;
		//item_list
		$order_item_ids=array();
		$order_item_names=array();
		$table_arr=array();
		$list=DB::query("select parent_id from tbl_item_cart where field_uid='".$field_uid."' and uid='".$uid."'  and item_cart_id in (".$item_cart_ids_str.") group by parent_id order by item_cart_addtime desc ");
		//and item_cart_status=0 
		
		
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
				$sub_list=DB::query("select item_cart_id,item_id,item_name,item_price,item_num as item_num_buy,(select item_num_canbuy from tbl_item where item_id=tbl_item_cart.item_id) as item_num_canbuy,(select item_num from tbl_item where item_id=tbl_item_cart.item_id) as item_num,(select item_intro from tbl_item where item_id=tbl_item_cart.item_id) as item_intro,(select ext_table_name from tbl_item where item_id=tbl_item_cart.item_id) as ext_table_name from tbl_item_cart where field_uid='".$field_uid."' and uid='".$uid."' and parent_id='".$row['parent_id']."' and item_cart_id in (".$item_cart_ids_str.")  ");//  and item_cart_status=0
				
				while($sub_row=DB::fetch($sub_list))
				{
		
					$order_money =$order_money+$sub_row['item_price'];
					$sub_row['item_price']=$sub_row['item_price']/100;
					$order_item_ids[]=$sub_row['item_id'];
					$order_item_names[]=$sub_row['item_name'];
					
					$table_arr[$sub_row['ext_table_name']]=$sub_row['ext_table_name'];
					
					//获取数量
					for($j=0; $j<count($get_item_cart_ids); $j++)
					{
						if($get_item_cart_ids[$j]==$sub_row['item_cart_id'])
						{
							$sub_row['item_num_buy']=$item_nums[$j];
						}
						
					}
					
					
					$row['sub_list'][]=array_default_value($sub_row);
				}
			}
			
			
			$item_list[]=array_default_value($row,array('item_pic_small_info','sub_list'));
			
		}
		
		$order_item_idstr=implode(',',$order_item_ids);
		$order_item_namestr=implode(',',$order_item_names);
		$order_item_numstr=implode(',',$item_nums);
		
		//add order 
		$sj_num=mt_rand(10000000,99999999);
		$order_sn=date('Ymd',time())."-".$config_shanghuhao."-".$sj_num;
		
		
		//如果是旧订单
		if($old_order_sn)
		{
			$order_info=DB::fetch_first("select * from tbl_order where order_sn='".$old_order_sn."' and uid='".$uid."' limit 1");
			/* echo "select * from tbl_order where order_sn='".$old_order_sn."' and uid='".$uid."' limit 1";
			echo 444;
			echo '<pre>';
			var_dump($order_info);
			echo 555;die; */
		}
		else
		{
			//如果是新订单
			$order_number=time();
			
			$aaa=DB::query("insert into tbl_order (field_uid,uid,order_number,order_sn,item_ids,item_nums,item_names,order_money,order_status,order_addtime,order_lasttime) values ('".$field_uid."','".$uid."','".$order_number."','".$order_sn."','".$order_item_idstr."','".$order_item_numstr."','".$order_item_namestr."','".$order_money."','-1','".time()."','".time()."') ");
			$order_info=DB::fetch_first("select * from tbl_order where order_sn='".$order_sn."' and uid='".$uid."' limit 1");
		}
		
		//field s
		$field_list=array();
		$field_list[0]['name']="order_realname";
		$field_list[0]['name_cn']="姓名";
		$field_list[0]['type']="input";
		$field_list[0]['type_more']=null;
		$field_list[0]['max_size']="50";
		$field_list[0]['value']=$order_info['order_realname'];
		
		$field_list[1]['name']="order_mobile";
		$field_list[1]['name_cn']="手机";
		$field_list[1]['type']="input";
		$field_list[1]['type_more']=null;
		$field_list[1]['max_size']="50";
		$field_list[1]['value']=$order_info['order_mobile'];
		
		$field_list[2]['name']="order_post";
		$field_list[2]['name_cn']="邮编";
		$field_list[2]['type']="input";
		$field_list[2]['type_more']=null;
		$field_list[2]['max_size']="50";
		$field_list[2]['value']=$order_info['order_post'];
		
		$field_list[3]['name']="order_address";
		$field_list[3]['name_cn']="地址";
		$field_list[3]['type']="input";
		$field_list[3]['type_more']=null;
		$field_list[3]['max_size']="50";
		$field_list[3]['value']=$order_info['order_address'];
		
		//这里增加外表，引入其他字段，并去重
		

		$field_array=array();
		$new_field_array=array();
		foreach($table_arr as $key=>$val)
		{
			//$new_arr=include('./api/_mobile/'.$versions.'/data/'.$table_arr[$i].'_array_data.php');
			$new_arr=include($current_path.'/data/'.$val.'_array_data.php');
			
			$table_info = DB::fetch_first("select * from {$val} where order_id='".$order_info['order_id']."' limit 1");
			foreach($new_arr as $key=>$val){
				$val['value']=$table_info[$val['name']];
				$new_arr[$key] = $val;
			}
			
			$new_field_array=array_merge($new_field_array,$new_arr);
		}
		
		foreach($new_field_array  as $key=>$val){
			unset($new_field_array[$key]);
			$new_field_array[$val['name']] = $val;
		}
		
		foreach($new_field_array  as $key=>$val){
			unset($new_field_array[$key]);
			$field_array[] = $val;
		}
		
		//$field_array=array_flip(array_flip($field_array));
		//$field_array=array_del_chongfu($field_array);
		//$field_array=unique_arr($field_array,true);
		
		//print_r($field_array);
	
		//合并在一起
		$field_list=array_merge($field_list,$field_array);
		
		for($i=0; $i<count($field_list); $i++)
		{
			$field_list[$i]=array_default_value($field_list[$i],array('type_more'));
		}
	
		$data['title']	= "data";
		$data['data']   =  array(
			'item_list'=>$item_list,
			'field_list'=>$item_list ? $field_list : null,
			'order_money'=>$order_money,
			'order_sn'=>$item_list? $order_sn : ''
		);
		api_json_result(1,0,$app_error['event']['10502'],$data);
		
		
	}
	else
	{
		api_json_result(1,1,'商品数和购买数不符，不能加入',$data);
	}
	
	
}

if($ac=='test_w_order'){
echo '<pre>';
 		$field_list=array();
		$field_list[0]['name']="order_realname";
		$field_list[0]['name_cn']="姓名";
		$field_list[0]['type']="input";
		$field_list[0]['type_more']=null;
		$field_list[0]['max_size']="50";
		$field_list[0]['value']=$order_info['order_realname'];
		
		$field_list[1]['name']="order_mobile";
		$field_list[1]['name_cn']="手机";
		$field_list[1]['type']="input";
		$field_list[1]['type_more']=null;
		$field_list[1]['max_size']="50";
		$field_list[1]['value']=$order_info['order_mobile'];
		
		$field_list[2]['name']="order_post";
		$field_list[2]['name_cn']="邮编";
		$field_list[2]['type']="input";
		$field_list[2]['type_more']=null;
		$field_list[2]['max_size']="50";
		$field_list[2]['value']=$order_info['order_post'];
		
		$field_list[3]['name']="order_address";
		$field_list[3]['name_cn']="地址";
		$field_list[3]['type']="input";
		$field_list[3]['type_more']=null;
		$field_list[3]['max_size']="50";
		$field_list[3]['value']=$order_info['order_address'];
		$table_arr['tbl_item_order_bmw'] = 'tbl_item_order_bmw';
		$table_arr['tbl_item_order_lpga'] = 'tbl_item_order_lpga';
		echo '<pre>';
		var_dump($field_list);
		$field_array=array();
		foreach($table_arr as $key=>$val)
		{
			//$new_arr=include('./api/_mobile/'.$versions.'/data/'.$table_arr[$i].'_array_data.php');
			$new_arr=include($current_path.'/data/'.$val.'_array_data.php');
			$field_array=$field_array+$new_arr;
		}
		var_dump($field_array);
		//$field_array=array_flip(array_flip($field_array));
		//$field_array=array_del_chongfu($field_array);
		//$field_array=unique_arr($field_array,true);
		//print_r($field_array);
	
		//合并在一起
		$field_list=array_merge($field_list,$field_array);
		var_dump($field_list);die;

		for($i=0; $i<count($field_list); $i++)
		{
			$field_list[$i]=array_default_value($field_list[$i],array('type_more'));
		}
		
		var_dump($field_list);
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
	$names=explode("^",$_G['gp_names']);
	$values=explode("^",$_G['gp_values']);
	
	if($order_sn)
	{
		$table_arr=array();
		$order_info=DB::fetch_first("select order_id,item_ids,item_nums from tbl_order where order_sn='".$order_sn."' ");
		$item_ids=explode(",",$order_info['item_ids']);
		$item_nums=explode(",",$order_info['item_nums']);
		for($i=0; $i<count($item_ids); $i++)
		{
			$item_nums[$i] = $item_nums[$i]?$item_nums[$i]:0;
			$cart_info=DB::fetch_first("select item_cart_id,item_num,(select ext_table_name from tbl_item where item_id=tbl_item_cart.item_id) as ext_table_name from tbl_item_cart where item_id='".$item_ids[$i]."' and item_cart_status=0 ");
			if($cart_info['item_cart_id'])
			{
				$up=DB::query("update tbl_item_cart set item_num='".$item_nums[$i]."',item_cart_status=1,order_id='".$order_info['order_id']."' where item_cart_id='".$cart_info['item_cart_id']."' ");
				
				$table_arr[]=$cart_info['ext_table_name'];
			}
			
			$up2=DB::query("update tbl_item set item_num=item_num-".$item_nums[$i]." where item_id='".$item_ids[$i]."' ");
			
		}
	
		$field_arr=array();
		$field_arr[0]['name']='order_id';
		$field_arr[0]['value']=$order_info['order_id'];
					
		if(count($names) == count($values))
		{
			$n=0;
			$sql_str="";
			$order_sql_str="";
			for($i=0; $i<count($names); $i++)
			{
				
				if($names[$i]=="order_realname" )
				{
					$order_sql_str .=" ,".$names[$i]."='".$values[$i]."' ";
					$n=$n+1;
					$family_name = mb_strcut($values[$i], 0, 3, 'utf-8'); 
					$field_arr[$n]['name']='family_name';
					$field_arr[$n]['value']=$family_name;
					$n=$n+1;
					$name = mb_strcut($values[$i], 3, 8, 'utf-8');
					$field_arr[$n]['name']='name';
					$field_arr[$n]['value']=$name;
				}elseif($names[$i]=="order_mobile")
				{
					$order_sql_str .=" ,".$names[$i]."='".$values[$i]."' ";
					$n=$n+1;
					$field_arr[$n]['name']='phone';
					$field_arr[$n]['value']=$values[$i];
				}elseif($names[$i]=="order_post")
				{
					$order_sql_str .=" ,".$names[$i]."='".$values[$i]."' ";
					$n=$n+1;
					$field_arr[$n]['name']='postcode';
					$field_arr[$n]['value']=$values[$i];
				}elseif($names[$i]=="order_address" )
				{
					$order_sql_str .=" ,".$names[$i]."='".$values[$i]."' ";
					$n=$n+1;
					$field_arr[$n]['name']='address';
					$field_arr[$n]['value']=$values[$i];
				}else{
					$n=$n+1;
					$field_arr[$n]['name']=$names[$i];
					$field_arr[$n]['value']=$values[$i];
				}
				
			}
			$n=$n+1;
			$field_arr[$n]['name']='addtime';
			$field_arr[$n]['value']=time();
		}
		
	
		
		//获取到的POST，保存到不同的表里
		$table_arr=array_unique($table_arr);
		
		for($i=0; $i<count($table_arr); $i++)
		{
			//$new_sql=include('./api/_mobile/'.$versions.'/data/'.$table_arr[$i].'_sql_data.php');
			$new_sql=include($current_path.'/data/'.$table_arr[$i].'_sql_data.php');
			for($j=0; $j<count($field_arr); $j++)
			{
				$new_sql=str_replace("{value_".$field_arr[$j]['name']."}",$field_arr[$j]['value'],$new_sql);
			}
			$new_sql = preg_replace("/{\w*}/",'',$new_sql);
			$ups=DB::query($new_sql);
			
		}
		
		$up=DB::query("update tbl_order set order_status=0,order_lasttime='".time()."'  ".$order_sql_str."   where order_sn='".$order_sn."' ");
		//echo "update tbl_order set order_status=0,order_lasttime='".time()."'  ".$order_sql_str."   where order_sn='".$order_sn."' ";
		api_json_result(1,0,'提交成功，现在跳转支付流程',$data);
	}
	else
	{
		api_json_result(1,0,'订单编号必须填写',$data);
	}

	
}





//修改订单状态
if($ac=='order_status')
{

	$field_uid = $_G['gp_field_uid'];
	$uid=$_G['gp_uid'];
	$order_sn=$_G['gp_order_sn'];
	$status=$_G['gp_status'];
	
	if($order_sn)
	{
		$up=DB::query("update tbl_order set order_status='".$status."',order_lasttime='".time()."' where order_sn='".$order_sn."' and uid='".$uid."' ");
		
		$order_info=DB::fetch_first("select order_id,item_ids,item_nums,order_mobile,order_realname,order_address,order_post from tbl_order where order_sn='".$order_sn."' ");
		
		//如果支付成功，修改购物车记录状态 并 添加到 我的门票
		if($status==1)
		{
			$up=DB::query("update tbl_item_cart set item_cart_status=2 where order_id='".$order_info['order_id']."' ");
			$msg="恭喜!支付成功";
			//如果是商品，添加到我的门票
			
			
			$item_id_arr=explode(",",$order_info['item_ids']);
			$item_num_arr=explode(",",$order_info['item_nums']);
			for($i=0; $i<count($item_id_arr); $i++)
			{
				
				$item_id=$item_id_arr[$i];
				$item_num=$item_num_arr[$i];
				$item_info=DB::fetch_first("select * from tbl_item where item_id='".$item_id."' ");
				
				if($item_info['item_type']=="ticket")
				{
				
					$user_ticket_mobile = $order_info['order_mobile'];
					$user_ticket_realname = $order_info['order_realname'];
					$user_ticket_address = $order_info['order_address'];
					$user_ticket_company_post = $order_info['order_post'];
					
					$user_ticket_code = get_randmod_str();
					$user_ticket_addtime = time();
					$user_ticket_status=1;
					
					
					
					$event_id=$item_info['event_id'];
					$ticket_times=$item_info['ticket_times'];
					$ticket_starttime=$item_info['ticket_starttime'];
					$ticket_endtime=$item_info['ticket_endtime']; 
					$ticket_price = $item_info['item_price'];
					$ticket_type = $item_info['ticket_type'];
					
					//生成二维码
					$phone = mt_rand(1000000000,9999999999);
					$erweima_path = erweima($phone);
					$user_ticket_codepic = $erweima_path;
					
					$row=explode("/",$user_ticket_codepic);
					$user_ticket_code=str_replace(".png","",$row[4]);
					
					//发系统消息
					$user_ticket_info = array(
						'event_id' => $event_id,
						'uid'      => $uid,
						'user_ticket_codepic' => $user_ticket_codepic
					);
					sys_message_add_return($user_ticket_info);
					
					
					$sql = "insert into tbl_user_ticket(uid,ticket_id,event_id,ticket_type,user_ticket_code,user_ticket_codepic,user_ticket_realname,user_ticket_address,user_ticket_mobile,user_ticket_imei,user_ticket_company,user_ticket_company_post,user_ticket_status,user_ticket_addtime,ticket_times,ticket_starttime,ticket_endtime,ticket_price,ip,sheng,city,field_uid) values('{$uid}','{$item_id}','{$event_id}','{$ticket_type}','{$user_ticket_code}','{$user_ticket_codepic}','{$user_ticket_realname}','{$user_ticket_address}','{$user_ticket_mobile}','{$user_ticket_imei}','{$user_ticket_company}','{$user_ticket_company_post}','{$user_ticket_status}','{$user_ticket_addtime}','{$ticket_times}','{$ticket_starttime}','1381053600','{$ticket_price}','{$ip}','{$sheng}','{$city}','{$field_uid}')";
					$res = DB::query($sql);
					
					
					//自动关注 start
					$list=DB::query("select uid from tbl_user_ticket where event_id='".$event_id."' and uid<>'".$uid."'  ");
					while($user = DB::fetch($list) )
					{
						
						$aaa=DB::fetch_first("select id from jishigou_buddys where uid='".$uid."' and buddyid='".$user['uid']."' ");
						if(empty($aaa))
						{
							$res=DB::query("insert into jishigou_buddys (uid,buddyid,grade,remark,dateline,description,buddy_lastuptime) values ('".$uid."','".$user['uid']."','1','','".time()."','','".time()."') ");
						}

						$bbb=DB::fetch_first("select id from jishigou_buddys where uid='".$user['uid']."' and buddyid='".$uid."' ");
						if(empty($bbb))
						{
							$res=DB::query("insert into jishigou_buddys (uid,buddyid,grade,remark,dateline,description,buddy_lastuptime) values ('".$user['uid']."','".$uid."','1','','".time()."','','".time()."') ");
					
						}
						
					}
					//自动关注 end
		
		
				}
	
				
			}
			
			
			
			
			
			//添加到我的门票  end
		}
		else if($status==2)
		{
			$msg="订单已提交，正在审核中。。";
		}
		else if($status==3)
		{
			$msg="支付失败";
		}
		else if($status==4)
		{
			$msg="订单取消成功";
		}
		else
		{
			
		}
		
		api_json_result(1,0,$msg,$data);
	}
	else
	{
		api_json_result(1,0,'订单编号必须填写',$data);
	}

	
}









//订单列表（历史记录）
if($ac=="order_list")
{
	$field_uid=$_G['gp_field_uid'];
	$uid=$_G['gp_uid'];
	
	if($field_uid!="")
	{
		$sql .=" and field_uid='".$field_uid."' ";
	}
	
	if($uid)
	{
		$sql .=" and uid='".$uid."' ";
	}
	
	
	$total=DB::result_first("select count(order_id) from tbl_order	where order_status>=0 ".$sql." ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{
	
		$list=DB::query("select order_id,order_number,order_sn,item_ids,item_names,order_money,order_status,order_addtime from tbl_order where order_status>=0 ".$sql." order by order_lasttime desc limit $page_start,$page_size ");
		while($row=DB::fetch($list))
		{
			
			if($row['order_status']==0)
			{
				$row['order_status_text']="待支付";
			}
			else if($row['order_status']==1)
			{
				$row['order_status_text']="支付成功";
			}
			else if($row['order_status']==2)
			{
				$row['order_status_text']="审核中";
			}
			else if($row['order_status']==3)
			{
				$row['order_status_text']="支付失败";
			}
			else
			{
				$row['order_status_text']="待支付";
			}
			
			$row['order_money']=$row['order_money']/100;
			$row['order_addtime']=date("Y-m-d",$row['order_addtime']);
			
			
			
			//商品
			$order_item_ids=array();
			$order_item_names=array();
			if($row['item_ids'])
			{
			
				$item_ids=$row['item_ids'];
				
				$list2=DB::query("select parent_id from tbl_item where field_uid='".$field_uid."' and item_id in (".$item_ids.") group by parent_id order by parent_id desc ");
				while($row2=DB::fetch($list2))
				{
					$parent_item_info=DB::fetch_first("select item_id,item_name,item_intro,item_pic_small from tbl_item where item_id='".$row2['parent_id']."' ");
					$row2['item_id']=$parent_item_info['item_id'];
					$row2['item_name']=$parent_item_info['item_name'];
					$row2['item_intro']=$parent_item_info['item_intro'];
					
					if($parent_item_info['item_pic_small'])
					{
						$row2['item_pic_small']=$site_url."/".$parent_item_info['item_pic_small'];
						$row2['item_pic_small_info']=getimagesize($row2['item_pic_small']);
					}
					else
					{
						$row2['item_pic_small']='';
						$row2['item_pic_small_info']=null;
					}
					
					
					$row2['sub_list']=null;
					if($row2['parent_id'])
					{
						$sub_list2=DB::query("select item_cart_id,item_id,item_name,item_price,item_num as item_num_buy,(select item_num_canbuy from tbl_item where item_id=tbl_item_cart.item_id) as item_num_canbuy,(select item_num from tbl_item where item_id=tbl_item_cart.item_id) as item_num from tbl_item_cart where field_uid='".$field_uid."' and uid='".$uid."' and parent_id='".$row2['parent_id']."' and item_id in (".$item_ids.")  and item_cart_status>=1 and order_id='".$row['order_id']."' ");
						while($sub_row=DB::fetch($sub_list2))
						{
							$sub_row['item_price']=$sub_row['item_price']/100;
							$row2['sub_list'][]=array_default_value($sub_row);
						}
					}
					
					
					$row['item_list'][]=array_default_value($row2,array('item_pic_small_info','sub_list'));
					
				}
				
			}
			else
			{
				$row['item_list']=null;
			}
		
		
		
			
			$list_data[]=array_default_value($row,array('item_list'));
			
		}
	}
	
	$data['title']		= "data";
	$data['data']     =  $list_data;
	
	api_json_result(1,0,$app_error['event']['10502'],$data);
}





//获取新单号
if($ac=="new_order_sn")
{
	
	$field_uid=$_G['gp_field_uid'];
	$uid=$_G['gp_uid'];
	$order_sn=$_G['gp_order_sn'];
	
	//add order 
	$sj_num=mt_rand(10000000,99999999);
	$new_order_sn=date('Ymd',time())."-".$config_shanghuhao."-".$sj_num;
	
	$up=DB::query("update tbl_order set order_sn='".$new_order_sn."' where order_sn='".$order_sn."' and uid='".$uid."' and field_uid='".$field_uid."'  ");
	
	
	$data['title']	= "data";
	$data['data']   =  array(
		'order_sn'=>$new_order_sn
	);
	
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
}





//获取随机字符串
function get_randmod_str(){
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

}



//生成二维码成功返回路径，失败返回 false
function erweima($phone)
{
	
    //如果没有就生成二维码
	$path_erweima_core = dirname(dirname(dirname(dirname(__FILE__))));
	
	require_once($path_erweima_core."/tool/phpqrcode/qrlib.php");
	$prefix = $path_erweima_core;
	$save_path="/upload/erweima/";
	$now_date = date("Ymd",time());
	$full_save_path=$path_erweima_core.$save_path.$now_date."/";

	if(!file_exists($prefix.$save_path))
	{
		mkdir($prefix.$save_path);
	}
	if(!file_exists($full_save_path))
	{
		$a = mkdir($full_save_path);
	}
	
	$pic_filename=$full_save_path.$phone.".png";
	$sql_save_path = $save_path.$now_date.'/'.$phone.".png";
	$errorCorrectionLevel = "L";
	$matrixPointSize=9;
	$margin=1;
	
	QRcode::png($phone, $pic_filename, $errorCorrectionLevel, $matrixPointSize, $margin); 
	
	if(file_exists($pic_filename))
	{
		return $sql_save_path;
	}
	else
	{
		return false;
	}
}


//添加系统消息
function sys_message_add_return($user_ticket_info)
{
	$sys_event_id = $user_ticket_info['event_id'];
	
	$sql = "select field_uid,event_name from tbl_event where event_id='{$sys_event_id}'";

	$sys_event_info = DB::fetch_first($sql);
	$sys_field_uid=$sys_event_info['field_uid'];
	if(empty($sys_field_uid)){
		$sys_field_uid = 0;
	}
	$field_uid=$sys_field_uid;
	if($user_ticket_info["uid"])
	{
		$sys_uid=$user_ticket_info["uid"];
	}
	else
	{
		$sys_uid=0;
	}
	$uid=$sys_uid;
	$message_title=$sys_event_info['event_name']."门票申请成功";

	$n_title=$message_title;
	$n_content=$message_title;
	
	$message_extinfo=array('action'=>"system_msg");	
	
	$msg_content = json_encode(array('n_title'=>urlencode($n_title), 'n_content'=>urlencode($n_content),'n_extras'=>$message_extinfo));

	$smessage_content=$msg_content;
	$receiver_type=3;//3:指定用户
	$message_pic=$user_ticket_info['user_ticket_codepic'];
	

	
	$message_totalnum=0;
	$message_sendnum=0;
	$message_errorcode="";
	$message_errormsg="";
	$message_addtime=time();
	
	$sql = "insert into tbl_sys_message(field_uid,uid,message_title,message_content,receiver_type,message_pic,message_totalnum,message_sendnum,message_errorcode,message_errormsg,message_addtime) values('{$field_uid}','{$uid}','{$message_title}','{$message_content}','{$receiver_type}','{$message_pic}','{$message_totalnum}','{$message_sendnum}','{$message_errorcode}','{$message_errormsg}','{$message_addtime}')";
	$rs = DB::query($sql);

	if($rs!=false)
	{
		return true;
	}
	else
	{				
		return false;
	}

}


?>