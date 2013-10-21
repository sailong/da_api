<?php

/**
 * [Discuz!] (C)2001-2099 Comsenz Inc.
 * This is NOT a freeware, use is subject to license terms
 *
 * $Id: home.php 22839 2011-05-25 08:05:18Z monkey $
 */ 
define ( 'APPTYPEID', 1 );
define ( 'CURSCRIPT', 'home' );

require_once '../source/class/class_core.php';
require_once '../source/function/function_home.php';

$discuz = & discuz_core::instance ();
$discuz->cachelist = $cachelist;
$discuz->init ();

$width = $_GET ['width'];
if(!$width)
{
	$width=960;
} 
  //横版缩放
 $agent = strtolower($_SERVER['HTTP_USER_AGENT']); 
    $iphone = (strpos($agent, 'iphone')) ? true : false; 
    $ipad = (strpos($agent, 'ipad')) ? true : false; 
    $android = (strpos($agent, 'android')) ? true : false; 
    if($iphone || $ipad)
    {    	
       $dguoqi=35/960;
    } else{
    	$dguoqi=12/960;
    }
   
 
$dguoqi1=$dguoqi*$width;

//page 1
$page=$_GET['page'];


if(!$page)
{
	$page=1;
}
$page = max(1,$page);
$page_size=$_GET['page_size'];
if(!$page_size)
{
	$page_size=10;
}
$page_start = ($page-1)*$page_size;

    ?>
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<title></title> 

<style type="text/css">  
body,div,ul,li{ 
padding:0; 
text-align:center; 
} 
img{width:90%}
td{padding:1px;}
td{text-align:center;font:<?php echo $dguoqi1;?>px "宋体";}
</style>   
</head> 
<body>
 
  
<table width="100%" border="0" align="center"  cellpadding="0" cellspacing="0" >
  
  <?php
    $total=DB::fetch_first("select count(album_id) as total from tbl_album");
	
	$total = $total['total'];
	$max_page=ceil($total/$page_size);
	if($page>=$max_page)
	{
		$page = $max_page;
		$page_start = ($page-1)*$page_size;
	}

	$list=DB::query("select album_id,album_name,album_sort,album_addtime from tbl_album where 1 order by album_sort desc,album_addtime desc limit $page_start,$page_size");
	//echo "select album_id,album_name,album_sort,album_addtime from tbl_album where 1 order by album_sort desc,album_addtime desc limit $page_start,$page_size";die;
	while($row = DB::fetch($list))
	{
		
		$photo=DB::fetch_first("select photo_url_small from tbl_photo where album_id='".$row['album_id']."' order by photo_addtime asc limit 1 ");
		
		if($photo)
		{
			$row['album_fenmian']='http://www.bwvip.com/'.$photo['photo_url_small'];//get_small_pic($site_url."/".$photo);
			$row['album_fenmian_info']=getimagesize($row['album_fenmian']);
			
		}
		else
		{
			$row['album_fenmian']="";
			$row['album_fenmian_info']=null;
		}
		$row['album_addtime']=date("Y-m-d",$row['album_addtime']);
		$list_data[]=$row;
	}
	
	$t=1;
 // $jdt=DB::query("SELECT *   FROM  pre_home_pic WHERE albumid=".$id." "); 

foreach($list_data as $key=>$val){
	
	?>
 
     <tr>
       <td align="center"><a href="http://wap.bwvip.com/album.php?id=<?php echo $val['album_id'];?>"><img src="<?php echo $val['album_fenmian'];?>"></a></td> 
     </tr>     
     <tr>
       <td align="center"><?php echo $val['album_name'];?></td> 
     </tr> 
 

 <?php } ?>
	<tr>
       <td align="center"><a href="http://wap.bwvip.com/album_list.php?page=<?php echo $page-1;?>">上一页</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://wap.bwvip.com/album_list.php?page=<?php echo $page+1;?>">下一页</a>&nbsp;&nbsp;&nbsp;&nbsp;<span>当前第<?php echo $page;?>页&nbsp;&nbsp;总共<?php echo $max_page;?>页</span></td> 
	</tr> 
</table>
</body>
<?php	
exit;  
?>