<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$sql="SELECT * FROM zdy_getauto WHERE TYPE=2 order by addtime desc";
	$re=DB::query($sql);
	while($row=DB::fetch($re)){
		if($row["addtime"]){
			$row["addtime"]=date("Y-m-d H:i:s",$row["addtime"]);
		}
		$newarr[]=$row;
}


if(!submitcheck('export')) {
	cpheader();
    showtips("<li>点导出，导出所有的订单</li><li>订单是按照最新订单排序</li>");
	
	showformheader("tianyi&op=export");
	echo '<input type="submit" value="导出excel" name="export" class="btn" />';
	showformfooter();
	showtableheader("");
	showsubtitle(array('所选号码','合约计划' , '合约期限', '入网姓名', '入网身份证', '身份证地址', '联系手机','收货人姓名','收货人身份证','收货人手机','收货人地址','订单时间')); 
	foreach($newarr as $value){
		showtablerow('class="td25"','',array($value["mobile"],$value["plan"],$value["qixian"],$value["username"],$value["zhengjian"],$value["address"],$value["lxdh"],$value["acname"],$value["acsfz"],$value["actel"],$value["acaddress"],$value["addtime"]));
	}
	showtablefooter();
}else{
	$field=array(
            'mobile'=>'所选号码',
            "plan"=>"合约计划",
            'qixian'=>'合约期限',
            'username'=>'入网姓名',
            'zhengjian'=>'入网身份证号',
            'address'=>'身份证地址',
            'lxdh'=>'联系电话',
            'acname'=>'收货人姓名',
            'acsfz'=>'收货人身份证',
            'actel'=>'收货人手机',
            'acaddress'=>'收货人地址',
            'addtime'=>'下单日期',
        );
	myexcel($newarr, $field);
}


function myexcel ($array,$field){
    header("Content-type: text/html; charset=utf-8");
	header("Content-type:application/vnd.ms-excel");     //格式
	header("Content-Disposition:filename=export.xls");    //生成的文件名
	echo '<head><style>td{vnd.ms-excel.numberformat:@}</style></head>';
	echo "<table width='100%' border='1'>";
	echo "<tr>";
	foreach($field as $key=>$vk){
		echo  "<td>".iconv("UTF-8", "gb2312",$vk)."</td>";
	}
    echo "</tr>";
	foreach($array as $key=>$value){
		echo "<tr>";
        foreach($field  as $k=>$v){
			if($value["$k"]){
				echo "<td>".iconv("UTF-8", "gb2312",$value["$k"])."</td>";
			}
		}
		echo "</tr>";
	}
}


?>