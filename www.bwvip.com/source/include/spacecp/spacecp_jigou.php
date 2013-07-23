<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_profile.php 24010 2011-08-19 07:35:13Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$ac = trim($_GET['ac']);
$operation = in_array($_GET['op'], array('shenqing', 'xunzhang', 'ites')) ? trim($_GET['op']) : 'xunzhang';

//头部菜单的切换
if(in_array($operation, array('shenqing', 'xunzhang', 'ites'))) {
	$opactives = array($operation =>'class=a');
}
/**
    * 功能：
    *   上传图片
    * 参数说明：
    *   $imgpath:    FILE变量

    * 返回值：
    *   1:$imgpath   上传图片成功后的完整图片路径

    *   2:false      上传图片失败
 *   2:false      上传图片失败失败
    * 版本：
    *   v1.0 07年8月5日作第1次修改，
    */ 
 
  function upload_image($imgpath)
     {
       $name=$imgpath["name"];
       $tmp_name=$imgpath["tmp_name"];
    $type=$imgpath["type"];
       $size=$imgpath["size"];
       $uploadfile = "./data/attachment/album/upload/".time()."_".$imgpath['name'];
       
    $maxsize=500*1024;                                           //最大允许上许文件大小
    if($name=="")                                             //文件名为空
  {
    echo"<script>alert('请先选择要上传的图片文件!'); 
         window.history.back();</script>";
  }
       if($type!="image/pjpeg" && $type!="image/jpeg" && $type!="image/gif")//文件类型不在指定范围
     {
    echo"<script>alert('上传文件只可以是JPEG或GIF类型的!');
    window.history.back();</script>";
    exit;
  }
       if($size>$maxsize)                                       //超过规定大小
     {
     echo"<script>alert('上传文件大小不能超过500K! ');window.history.back();</script>";
     exit;
   }
     
    if(move_uploaded_file($tmp_name,$uploadfile))
         return $uploadfile;
    else if (copy($tmp_name,$uploadfile))
        return $uploadfile;
       else
        return false;    

  }



/* 勋章增加 */
if($_GET['do']=="xunzhang"){
$mdimg=$_FILES["mdimg"];
upload_image($mdimg);

$mdimg="./data/attachment/album/upload/".time()."_".$mdimg['name'];

$mditem=$_POST['mditem'];
$mditem=implode(",",$mditem);

DB::insert('medals', array('compid' => $space['uid'],'mdid' => $_POST['mdid'],'mdname' => $_POST['mdname'],'mdimg' => $mdimg,'mdcontent' => $_POST['mdcontent'],'mditem' => $mditem));
showmessage('勋章提交成功', 'home.php?mod=spacecp&ac=jigou&op=xunzhang');
}
/* 勋章删除 */
if($_GET['do']=="xunzhang" || $_GET['cz']=="del"){
$xzid=$_GET['id'];
DB::query("DELETE FROM ".DB::table('medals')." WHERE id='$xzid'");
showmessage('勋章删除成功', 'home.php?mod=spacecp&ac=jigou&op=xunzhang');
}

	/* 机构类别调用 */
	//$compclass = array();
	//$query = DB::query("SELECT id,sortname FROM ".DB::table('team_sort')." order by id");
	//while ($sort = DB::fetch($query)) {
		//$compclass[] = $sort;
	//}
	
	/* 勋章展示 */
	$xunzhang = array();
	$query = DB::query("SELECT * FROM ".DB::table('medals')." where compid=".$space['uid']." order by mdid");
	while ($xz = DB::fetch($query)) {
		$xunzhang[] = $xz;
	}
	/* 勋章修改 */
	$cz=trim($_GET['cz']);
	if($_GET['do']=="xunzhang" || $_GET['cz']=="update"){
	$xzid=$_GET['id'];
	$xzxg = DB::fetch_first("SELECT * FROM ".DB::table("medals")." WHERE compid='$_G[uid]' AND id='$xzid'");
	}
	
	if($_GET['do']=="xunzhangg" || $_GET['cz']=="updates"){
	$xzid=$_GET['id'];
	DB::update('medals', array('mdname' => $_POST['mdname'],'mdimg' => $mdimg,'mdcontent' => $_POST['mdcontent']), array('id'=>$xzid));
	showmessage('勋章修改成功', 'home.php?mod=spacecp&ac=jigou&op=xunzhang');
	}

include template("home/spacecp_jigou");



?>