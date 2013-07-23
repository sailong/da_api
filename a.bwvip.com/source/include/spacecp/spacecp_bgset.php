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
//echo "dsfasdfaf";
//var_dump($_POST);
//var_dump($_FILES);

$updir="static/space";        //上传的路径 

//如果有提交

if($_POST["sub"]){
    ///var_dump($_FILES);
    $fileone=$_FILES["fileimg"];            //第一个
    $filetwo=$_FILES["fileimgtwo"];         //第二个
    $bgcolor=$_POST["bgcolor"];       //第三个
    $bgcolor=trim($bgcolor);
    //var_dump($fileone);
    //第一个
    $path=$updir."/uid".$_G["uid"];
     if($fileone["error"]==0){
        $newname=$_G["uid"]."_bigheader";
        $field="headerpic";
        //echo $newname;
        $ak=uploadfile($fileone,$path,$newname,$field);
        //var_dump($ak);
        //showmessage($ak,"/home.php?mod=spacecp&ac=bgset");
     }
     //第二个
     if($filetwo["error"]==0){
        $newname=$_G["uid"]."_smallheader";
        $field="bgpic";
        //var_dump($filetwo);
        $ak=uploadfile($filetwo,$path,$newname,$field);
        //var_dump($ak);
        //showmessage($ak,"/home.php?mod=spacecp&ac=bgset");
     }
    if(!empty($bgcolor)){
        //先判断有没有这个数据
        $re=DB::query("select uid from ".DB::table("qiye_logo")." where uid=".$_G["uid"]);
        $rea=DB::fetch($re);
        $nowtime=time();
         if(empty($rea)){
             DB::insert("qiye_logo",array("bgcolor"=>$bgcolor,"uid"=>$_G['uid'],"inserttime"=>$nowtime));
             //showmessage("添加成功","/home.php?mod=spacecp&ac=qylogo");
             $str="添加成功";
             return $str;
         }else{
            DB::update("qiye_logo",array("bgcolor"=>$bgcolor,"inserttime"=>$nowtime),array("uid"=>$_G["uid"]));
            //showmessage("更新成功","/home.php?mod=spacecp&ac=qylogo");
            //$str="更新成功";
         }
    }
    showmessage("更新完成","/home.php?mod=spacecp&ac=bgset");
}


/*参数一$filename，上传的变量
*参数二$updir，上传的路径
*参数三$newname   新的文件名
*参数四$fild 要更新那个字段
*/

function uploadfile($filename,$updir,$newname,$fild){
    global $_G;
    if(empty($filename) || empty($updir) || empty($newname) || empty($fild)){
        $str="少参数";
        return $str;
    }
    if (is_uploaded_file($filename['tmp_name'])) {
        if(!is_dir($updir)) {
    		mkdir($updir, 0777);
    	}
    	chmod($updir, 0777);
        
        if($filename['error']>0){
            switch($filename['error']){
               case 3: 
               $str="只有部分文件被上传";
               return $str;
               exit;
               break;
               case 4: 
               $str="没有任何文件被上传！";
               return $str;
               exit;
               break;
            }
        }
        
       $maxsize=1000000;    //1M最大上传限制
    
       //$newname =$_G["uid"]."_qiyelogo"; //使用日期做文件名
       
       $name = $filename["name"];
       $type = $filename["type"];
       $size = $filename["size"];
       $tmp_name = $filename["tmp_name"];
    
      switch ($type) {
       case 'image/pjpeg' :
        $extend = ".jpg";
        break;
       case 'image/jpeg' :
        $extend = ".jpg";
        break;
      }
      if (empty ($extend)) {
       $str="类型不正确,只能使用JPG格式";
       return $str;
      }
      if ($size > $maxsize) {
       //showmessage("文件大小不能超过1M","/home.php?mod=spacecp&ac=qylogo");
       $str="文件大小不能超过1M";
       return $str;
      }
    
      $logopath=$updir.'/'.$newname.$extend;    //最终路径
      //var_dump($logopath);
      //var_dump($_FILES);
      if (move_uploaded_file($tmp_name, $logopath)) {
        //var_dump("adsfasafaf");
        //先判断有没有这个数据
        $re=DB::query("select uid from ".DB::table("qiye_logo")." where uid=".$_G["uid"]);
        $rea=DB::fetch($re);
        $nowtime=time();
         if(empty($rea)){
             DB::insert("qiye_logo",array($fild=>$newname.$extend,"uid"=>$_G['uid'],"inserttime"=>$nowtime));
             //showmessage("添加成功","/home.php?mod=spacecp&ac=qylogo");
             $str="添加成功";
             return $str;
         }else{
            DB::update("qiye_logo",array("inserttime"=>$nowtime,$fild=>$newname.$extend),array("uid"=>$_G["uid"]));
            //showmessage("更新成功","/home.php?mod=spacecp&ac=qylogo");
            $str="更新成功";
            return $str;
         }
      }else{
        //showmessage("上传失败","/home.php?mod=spacecp&ac=qylogo");
        $str="上传失败";
        return $str;
      }
    
    }else{
        //showmessage("请先选择文件","/home.php?mod=spacecp&ac=qylogo");
        $str="请先选择文件";
        return $str;
    }
    
}

if($_GET["ac"]=='bgset'){
    //读取logo
    $list=DB::query("select bgcolor,headerpic,bgpic from ".DB::table("qiye_logo")." where uid=".$_G["uid"]);
    $listre=DB::fetch($list);
    if(!empty($listre["headerpic"])){
        $listre["headerpic"]="static/space/uid{$_G[uid]}/".$listre["headerpic"];
    }
    if(!empty($listre["bgpic"])){
        $listre["bgpic"]="static/space/uid{$_G[uid]}/".$listre["bgpic"];
    }
    //$listpath=$updir."/".$listre["logo"];
    //echo $listpath;
    //var_dump($listre);
}


$templates='home/spacecp_bgset';
include_once(template($templates)); 


//include_once(template('home/spacecp_csqy'));
?>