<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: space_doing.php 19158 2010-12-20 08:21:50Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$op = in_array($_GET['op'], array('list','add','addsub','view')) ? $_GET['op'] : 'list';

$gid=$_G['groupid'];   //组id  现在的是22

space_merge($space, 'count');

$updir="static/space";        //上传的路径 
$url="home.php?mod=space&uid=".$uid."&do=ershou&op=add";
if($op=='addsub' && $_POST["sub"]){
    //var_dump($_POST);
    if(!$_G["uid"]){
        showmessage("请先登录",$url);
    }
    
    
    $fileone=$_FILES["myfile"];   //图片
    $title=trim($_POST["title"]);
    $content=trim($_POST["content"]);
    $content=htmlspecialchars($content);
    $price=trim($_POST["price"]); 
    $acid=trim($_POST["acid"]);
    if(empty($title)){
        showmessage("标题不能为空",$url);
    }
    if(empty($content)){
        showmessage("内容不能为空",$url);
    }
    if(empty($fileone)){
        showmessage("图片不能为空",$url);
    }
    if(empty($price)){
        showmessage("价格不能为空",$url);
    }
    
    
    $url="home.php?mod=space&uid=$acid&do=ershou";
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
            showmessage($str,$url);
          }
          if ($size > $maxsize) {
           $str="文件大小不能超过1M";
           showmessage($str,$url);
          }
          $nowtime=time();
        $logopath=$path.'/'.$newname.$extend;    //最终路径
        if (move_uploaded_file($tmp_name, $logopath)) {
            DB::insert("ershou_info",array("userid"=>$acid,"publishid"=>$_G['uid'],"title"=>$title,"content"=>$content,"price"=>$price,"pic"=>$newname.$extend,"addtime"=>$nowtime));
            showmessage("添加成功",$url);
        }else{
            showmessage("上传失败",$url);
        }
        
     }else{
        showmessage("文件上传失败",$url);
     }
    
    
}
if($op=='view'){
    $id=trim($_GET["id"]);
    if(empty($id)){
        showmessage("参数失败",$url);
    }
    $sql="SELECT a.id,a.userid,b.username,c.realname,a.title,a.content,a.price,a.pic FROM pre_ershou_info a LEFT JOIN pre_ucenter_members b ON a.publishid=b.uid LEFT JOIN pre_common_member_profile c ON a.publishid=c.uid WHERE id=".$id;
    $re=DB::query($sql);
    $relist=DB::fetch($re);
    if(empty($relist["realname"])){
        $relist["name"]=$relist["username"];
    }else{
        $relist["name"]=$relist["realname"];
    }
    //var_dump($relist);
}
if($op=='list'){
    $limitpage=10;   //每页显示多少个
    $limitpage = mob_perpage($limitpage);
	$page=empty($_GET["page"])?1:$_GET["page"];  //page是必须的一样的
	$page=trim(intval($page));
    $start = ($page-1)*$limitpage;   //开始的条数
    ckstart($start, $limitpage);
    
    
    $sql="SELECT a.id,a.userid,b.username,c.realname,a.title,a.content,a.price,a.pic FROM pre_ershou_info a LEFT JOIN pre_ucenter_members b ON a.publishid=b.uid LEFT JOIN pre_common_member_profile c ON a.publishid=c.uid WHERE userid={$uid} order by a.addtime desc limit ".$start.",".$limitpage;
    $re=DB::query($sql);
    
    while($all=DB::fetch($re)){
        if(empty($all["realname"])){
            $all["name"]=$all["username"];
        }else{
            $all["name"]=$all["realname"];
        }
        $all["title"]=utf8Substr($all["title"],0,18);
        $all["content"]=utf8Substr($all["content"],0,50);
        $allarr[]=$all;
    }
    $theurl = 'home.php?mod=space&uid='.$uid.'&do=ershou'; //地址

	//判断总条数
    $countnum = DB::result(DB::query("SELECT COUNT(0) num FROM pre_ershou_info WHERE userid=".$uid));
    $disppage = multi($countnum, $limitpage, $page, $theurl);
    
   //var_dump($allarr);
}


$templates='home/space_ershou';

include_once(template($templates));    

?>