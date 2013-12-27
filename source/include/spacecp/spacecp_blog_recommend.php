<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$gid=$_G['groupid'];
if($gid!='21'){
    header("Location:/");
    exit;
}
//取出来所有的博客
$op=trim($_GET["operation"]);
$url="/home.php?mod=spacecp&ac=blog_recommend";
//list
$blog=DB::query("SELECT a.blogid,a.uid,a.username,a.subject,b.blogid AS flag FROM pre_home_blog AS a LEFT JOIN pre_blog_recommend b ON a.blogid=b.blogid WHERE a.uid=".$uid." ORDER BY a.`dateline` DESC");
while($row=DB::fetch($blog)){
    $listarr[]=$row;
}
//var_dump($listarr);

if($op=='add'){
    $time=time();
    $userid=$_G["uid"];
    $blogid=trim($_GET["id"]);
    if(empty($blogid)){
        showmessage('缺少参数',$url);
    }
    
    $flag=DB::insert("blog_recommend",array("blogid"=>$blogid,"userid"=>$userid,"dateline"=>$time));
    if($flag){
        showmessage('推荐成功',$url);
    }else{
        showmessage('操作失败',$url);
    }
}
if($op=='cancel'){
    $userid=$_G["uid"];
    $blogid=$_GET["id"];
    if(empty($blogid)){
        showmessage('缺少参数',$url);
    }
    $flag=DB::delete("blog_recommend",array("blogid"=>$blogid,"userid"=>$userid));      //直接删除
    if($flag){
        showmessage('取消成功',$url);
    }else{
        showmessage('取消失败',$url);
    }
}

$template = 'home/spacecp_blog_recommend';
include template($template);
?>