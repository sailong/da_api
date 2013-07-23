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

$gid=$_G['groupid'];   //组id  现在的是22
if($gid!='22'){
    header("Location:/home.php?mod=space");
    exit;
}

//这里面也需要判断用户的组id
$op = in_array($_GET['op'], array('list','add','del','edit','editsub')) ? $_GET['op'] : 'list';

//echo "dsfasdfaf";
//var_dump($_POST);
//var_dump($_FILES);
$url="/home.php?mod=spacecp&ac=ershou";
$updir="static/space";        //上传的路径 
if($_POST["sub"] && $op=='add'){
    //这一块已经废弃了，不用了
    ///var_dump($_FILES);
    $fileone=$_FILES["myfile"];   //图片
    $title=trim($_POST["title"]);
    $content=trim($_POST["content"]);
    $price=trim($_POST["price"]); 
    $bgcolor=trim($bgcolor);
    //var_dump($fileone);
    $path=$updir."/uid".$_G["uid"];
     if($fileone["error"]==0){
            $newname=$_G["uid"]."_ershou_".time()."_".rand(1,100);    //新的文件名
            $name = $fileone["name"];
            $type = $fileone["type"];
            $size = $fileone["size"];
            $tmp_name = $fileone["tmp_name"];
            $maxsize=1000000;    //1M最大上传限制
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
            showmessage($str,"/home.php?mod=spacecp&ac=ershou");
          }
          if ($size > $maxsize) {
           $str="文件大小不能超过1M";
           showmessage($str,"/home.php?mod=spacecp&ac=ershou");
          }
          $nowtime=time();
        $logopath=$path.'/'.$newname.$extend;    //最终路径
        if (move_uploaded_file($tmp_name, $logopath)) {
            DB::insert("ershou_info",array("publishid"=>$_G['uid'],"title"=>$title,"content"=>$content,"price"=>$price,"pic"=>$newname.$extend,"addtime"=>$nowtime));
            showmessage("添加成功",$url);
        }else{
            showmessage("上传失败",$url);
        }
        
     }
}
if($op=='list'){
    $limitpage=2;   //每页显示多少个
    $limitpage = mob_perpage($limitpage);
	$page=empty($_GET["page"])?1:$_GET["page"];  //page是必须的一样的
	$page=trim(intval($page));
    $start = ($page-1)*$limitpage;   //开始的条数
    ckstart($start, $limitpage);
    $ertt=DB::query("SELECT id,userid,title,content,price,pic FROM pre_ershou_info where userid={$_G[uid]} ORDER BY ADDTIME DESC limit {$start},".$limitpage);
    while($va = DB::fetch($ertt)) {
        $va["title"]=utf8Substr($va["title"],0,12);
        $va["content"]=utf8Substr($va["content"],0,30);
        $lerf[]=$va;
    }
    
    $theurl = 'home.php?mod=spacecp&ac=ershou'; //地址
	//判断总条数
    $countnum = DB::result(DB::query("SELECT COUNT(0) num FROM pre_ershou_info WHERE userid=".$_G["uid"]));
    $disppage = multi($countnum, $limitpage, $page, $theurl);
    
 
}
//编辑
if($op=='edit'){
    $id=$_GET["id"];
    if(empty($id)){
        showmessage("参数传递失败",$url);
    }
    $sqla="SELECT userid,title,content,price,pic FROM pre_ershou_info WHERE userid=".$_G[uid]." AND id=".$id;
    $ab=DB::query($sqla);
    $ty=DB::fetch($ab);
    //var_dump($ty);
}
//编辑提交
if($op=='editsub'){
    //var_dump($_POST);
    $id=$_POST["id"];
    if(empty($id)){
        showmessage("参数传递失败",$url);
    }
    $t=$_POST["title"];
    $c=$_POST["content"];
    $price=$_POST["price"]; 
    $flag=DB::update("ershou_info",array("title"=>$t,"content"=>$c,"price"=>$price),array("id"=>$id,"userid"=>$_G["uid"]));
    if($flag){
        showmessage("更新成功",$url);
    }else{
        showmessage("更新失败",$url);
    }
    
}
//删除
if($op=='del'){
    //var_dump($_POST);
    $arr=$_POST["del"];
    if(empty($arr)){
        showmessage("非法提交",$url);
    }
    if(!empty($arr[0])){
        foreach($arr as $value){
            $ty=DB::delete("ershou_info",array("id"=>$value,"userid"=>$_G["uid"]));
        }
        if($ty){
            showmessage("删除成功",$url);
        }
    }
}



$templates='home/spacecp_ershou';
include_once(template($templates)); 

?>