<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_index.php 22814 2011-05-24 05:42:54Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$gid=$_G['groupid'];   //组id  现在的是25

//这里面也需要判断用户的组id
$op = in_array($_GET['op'], array('list','search','del','edit','searchsub','slist')) ? $_GET['op'] : 'list';    


$updir = 'static/space/qiye_logo';    //上传的路径
$upfile=$_FILES["fileimg"];   //接收的file
if($_POST["sub"]){
    //判断用户是否通过http post上传
    if (is_uploaded_file($_FILES['fileimg']['tmp_name'])) {
    
        if(empty($upfile)){
            showmessage("图片必须传","/home.php?mod=spacecp&ac=qylogo");
        }
        
        if(!is_dir($updir)) {
    		mkdir($updir, 0777);
    	}
    	chmod($updir, 0777);
        
        
        if($_FILES['userfile']['error']>0)
{
        
        switch($_FILES['fileimg']['error'])
        {
                case 3: 
                showmessage("只有部分文件被上传","/home.php?mod=spacecp&ac=qylogo");
                break;
                case 4: 
                showmessage("没有任何文件被上传！","/home.php?mod=spacecp&ac=qylogo");
                break;
        }
}
        
        $maxsize=1000000;    //1M最大上传限制
    
       $newname =$_G["uid"]."_qiyelogo"; //使用日期做文件名
       $name = $upfile["name"];
       $type = $upfile["type"];
       $size = $upfile["size"];
       $tmp_name = $upfile["tmp_name"];
    
      switch ($type) {
       case 'image/pjpeg' :
        $extend = ".jpg";
        break;
       case 'image/jpeg' :
        $extend = ".jpg";
        break;
      }
      if (empty ($extend)) {
       showmessage("文件类型不正确,只能使用JPG格式","/home.php?mod=spacecp&ac=qylogo");
      }
      if ($size > $maxsize) {
       showmessage("文件大小不能超过1M","/home.php?mod=spacecp&ac=qylogo");
      }
    
      $logopath=$updir.'/'.$newname.$extend;    //最终路径
      //var_dump($logopath);
      //var_dump($_FILES);
      if (move_uploaded_file($tmp_name, $logopath)) {
        //var_dump("adsfasafaf");
        //先判断有没有这个数据
        $re=DB::query("select logo from ".DB::table("qiye_logo")." where uid=".$_G["uid"]);
        $rea=DB::fetch($re);
        $nowtime=time();
         if(empty($rea)){
             DB::insert("qiye_logo",array("logo"=>$newname.$extend,"uid"=>$_G['uid'],"inserttime"=>$nowtime));
             showmessage("添加成功","/home.php?mod=spacecp&ac=qylogo");
         }else{
            DB::update("qiye_logo",array("logo"=>$newname.$extend,"inserttime"=>$nowtime),array("uid"=>$_G["uid"]));
            showmessage("更新成功","/home.php?mod=spacecp&ac=qylogo");
         }
      }else{
        showmessage("上传失败","/home.php?mod=spacecp&ac=qylogo");
      }
    
    }else{
        showmessage("请先选择文件","/home.php?mod=spacecp&ac=qylogo");
    }
}

if($_GET["ac"]=='qylogo'){
    //读取logo
    $list=DB::query("select logo from ".DB::table("qiye_logo")." where uid=".$_G["uid"]);
    $listre=DB::fetch($list);
    $listpath=$updir."/".$listre["logo"];
    //echo $listpath;
    if(empty($listre)){
        $listpath="";
    }
}


$templates='home/spacecp_qylogo';
include_once(template($templates)); 


//include_once(template('home/spacecp_csqy'));
?>