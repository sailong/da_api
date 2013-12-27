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
/* echo '<pre>';
var_dump($_SERVER); */
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

$title_name = '信息列表';
if(strpos($_SERVER['REQUEST_URI'],'album.php'))
{
	$title_name = '相片列表';
}elseif(strpos($_SERVER['REQUEST_URI'],'album_list.php'))
{
	$title_name = '相册列表';
}

    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0"/>
<meta name="MobileOptimized" content="236"/>
<meta http-equiv="Cache-Control" content="no-cache"/>
<title><?php echo $title_name; ?></title>
<style type="text/css">
body{margin:0;padding:0;font-size:12pt;color:#000000; line-height:150%; font-family:"微软雅黑","宋体";}
p,h2,h3,h4,ul,li{margin:0;padding:0;list-style:none;}
img{margin:0;padding:0;border:none;width:100%;}
.tuijian a:link,.tuijian a:visited{text-decoration:none; font-size:12pt;color:#000000;}
.tuijian a:hover{text-decoration:none;color:000;}
.wrap{width:100%;height:auto;}
h3{width:95%;font-size:18px;line-height:25px; height:60px; background:url(images/line-bg.png) no-repeat left bottom;margin:20px 0 10px 0;}
h2{width:95%;margin:20px auto 0;font-size:18px;height:40px;line-height:25px;background:url(images/line-bg.png) no-repeat left bottom;}
.tuijian{width:100%;line-height:30px;margin: 20px auto 10px;}
.tuijian .tu{width:13px;float:left;padding-top:10px;margin-right:10px;}
.bottom{width:100%;margin:40px auto;}

</style>
</head>
<body>
<div class="wrap">
	<div class="top"><img src="images/album_img/dazheng-top.png" /></div>
    <div style="padding:0 20px;">
<div class="content">
    	<h3><?php echo $title_name; ?></h3><br />
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
        <h4><a href="http://wap.bwvip.com/album.php?id=<?php echo $val['album_id'];?>"><img src="<?php echo $val['album_fenmian'];?>" /></a></h4>
        <p style="margin-bottom:50px;font-weight:bold; font-size:18px;"><?php echo $val['album_name'];?></p>
 <?php } ?>
  <p style="margin-bottom:50px;font-weight:bold; font-size:18px;"><a href="http://wap.bwvip.com/album_list.php?page=<?php echo $page-1;?>">上一页</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://wap.bwvip.com/album_list.php?page=<?php echo $page+1;?>">下一页</a>&nbsp;&nbsp;&nbsp;&nbsp;<span>当前第<?php echo $page;?>页&nbsp;&nbsp;总共<?php echo $max_page;?>页</span></p>   
</div>
</div>
<?php
$userAgent = $_SERVER['HTTP_USER_AGENT'];
if(strpos($userAgent,"iPhone") || strpos($userAgent,"iPad") || strpos($userAgent,"iPod") || strpos($userAgent,"iOS"))
{
	$dz_down_url = DB::result_first("select app_version_file from tbl_app_version where app_version_type='ios' and field_uid=0 order by app_version_addtime desc limit 1 ");//"https://itunes.apple.com/us/app/da-zheng-gao-er-fu-golf/id642016024?ls=1&mt=8";
	
	
}
else if(strpos($userAgent,"Android"))
{
	$dz_down_url = DB::result_first("select app_version_file from tbl_app_version where app_version_type='android' and field_uid=0 order by app_version_addtime desc limit 1 ");
	
}
if($dz_down_url){
?>

		
<div class="center"><a href="<? echo $dz_down_url; ?>"><img src="images/album_img/jinru-button.png" /></a></div>

<?php } ?>
<div class="wrap">
<h2>相关推荐</h2>
<div style="padding:0 20px 40px;">

<?php
if($_GET['gp_uid']){
	$where = " and uid = '".getgpc('uid')."'";
}
if($_GET['gp_id']){
	$where .= " and blogid>{$_GET['gp_id']} ";
}
$where = '';
$num =3;
$uid = getgpc('uid');
/*最新博客*/

$new_blogs_query = DB::query(" select `blogid`,`subject` from ".DB::table('home_blog')." where 1=1 order by blogid desc".$where." limit ".$num);
//echo " select `blogid`,`subject` from ".DB::table('home_blog')." where 1=1 order by blogid desc".$where." limit ".$num;

while($new_blogs_result = DB::fetch($new_blogs_query)){
	$new_blogs_list[$new_blogs_result['blogid']]= $new_blogs_result['subject'];
}

foreach($new_blogs_list as $key=>$val){

?>
<div class="tuijian">
<p class="tu"><img src="images/dian.png" /></p>
<p><a href="http://wap.bwvip.com/index.php?mod=news&do=details&id=<?php echo $key; ?>&uid=<?php echo $uid; ?>" target="_blank"><?php echo $val; ?></a></p></div>
<?php } ?>

</div>
    
</div>
</body>
</html>
<?php	
exit;  
?>