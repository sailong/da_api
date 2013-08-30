<?php

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
 

$ac=$_G['gp_ac']; 
 
//一级地区列表
if($ac=="list")
{ 
	//地区
	$query = DB::query('select id, name from '.DB::table('common_district')." where level=1");
	while($value = DB::fetch($query)) {
		 $rowname= array();
		//$area= $value;
		$qur = DB::query('select id, name from '.DB::table('common_district')." where upid=".$value[id]);
		while($row = DB::fetch($qur)) { 
		
			//$area[$value[id]][$value[id]]= $value;
			//$area[$value[id]][$row[id]]= $row; 
			
		$rowname[]=$row[name];
		} 
		
		$area[]=array($value[id]=>$value[name], 
			'name'=>array($rowname
                                     )
           );
	} 
	 
		//print_r($area);
        $data['title']	= "area";
		$data['data']   =  $area;
	if(!empty($area))
	{ 
		api_json_result(1,0,$api_error['district']['10020'],$data);
	}
	else
	{
		api_json_result(1,1,"没有内容",$data);
	}

} 
 
?>