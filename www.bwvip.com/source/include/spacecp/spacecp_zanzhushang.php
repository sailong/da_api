<?php

/**
 *      date:2012年3月5日
 *      author:xgw
 *      info:赞助商页面
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$gid=$_G['groupid'];   //组id  现在的是25

//var_dump($gid);
//var_dump($_G['uid']);
//这里面也需要判断用户的组id
$op = in_array($_GET['op'], array('list','del','edit','add','addsub','editseq')) ? $_GET['op'] : 'list';

//添加
if($op=='addsub'){
    $myname=trim($_POST["myname"]); //名称
    $myhref=trim($_POST["href"]);   //连接
    $upfile=$_FILES["myfile"];      //接收的file
    $seq=trim($_POST["seq"]);       //排序
    $ad_type = getgpc('type');      //广告类型
    if(empty($seq)){
        $seq=100;
    }



    //var_dump($upfile);
    if(empty($upfile)){
        showmessage("图片必须传","/home.php?mod=spacecp&ac=zanzhushang");
    }
    //$updir="/uploadfile/zanzhushang/";       //上传路径
    $updir = 'uploadfile/zanzhushang/'.date('Ym');
    if(!is_dir($updir)) {
		mkdir($updir, 0777);
	}
	chmod($updir, 0777);

    $maxsize=1000000;    //1M最大上传限制

   $newname = date("YmdHis"); //使用日期做文件名
   $name = $upfile["name"];
   $type = $upfile["type"];
   $size = $upfile["size"];
   $tmp_name = $upfile["tmp_name"];
   $extend  ="";
      switch ($type) {
       case 'image/pjpeg' :
        $extend = ".jpg";
        break;
       case 'image/jpeg' :
        $extend = ".jpg";
        break;
       case 'image/gif' :
        $extend = ".gif";
        break;
       case 'image/png' :
        $extend = ".png";
        break;
      }


  if (empty ($extend) && $type) {
   showmessage("文件类型不正确,只能使用JPG GIF PNG 格式","/home.php?mod=spacecp&ac=zanzhushang");
  }
  if (($size > $maxsize) && $type) {
   $maxpr = $maxsize / 1000;
   showmessage("文件大小不能超过1M","/home.php?mod=spacecp&ac=zanzhushang");
  }

  if($extend){
        $rand=rand(1,1000);
        $logopath=$updir.'/'.$_G['uid'].'_'.date('YmdHis')."_".$rand.$extend;    //最终路径
  }

  if ($type) move_uploaded_file($tmp_name, $logopath);
  DB::insert("zanzhushang",array("name"=>$myname,"logo"=>$logopath,"href"=>$myhref,"uid"=>$_G['uid'],"seq"=>$seq,"inserttime"=>time(),'type'=>$ad_type));
  showmessage("添加成功","/home.php?mod=spacecp&ac=zanzhushang");
}
//默认list
if($op=='list'){
    //var_dump("asdfasfaf");
    //echo "select id,name,href,logo,uid,seq from ".DB::table("zanzhushang")." where uid=".$_G[uid]." order by seq asc";
    $ky=DB::query("select * from ".DB::table("zanzhushang")." where uid=".$_G[uid]." order by seq asc limit 20");
    while($row=DB::fetch($ky)){
        $endrow[]=$row;
    }
    //var_dump($endrow);

}
//删除
if($op=='del'){
    //var_dump($_POST);
    $arr=$_POST["del"];
    if(count($arr)>=1){
        foreach($arr as $value){
            DB::delete("zanzhushang",array("id"=>$value,"uid"=>$_G[uid]));
        }
         showmessage("删除成功","/home.php?mod=spacecp&ac=zanzhushang");
    }else{

        showmessage("没有选择赞助商","/home.php?mod=spacecp&ac=zanzhushang");
    }
}
//编辑排序
if($op=='editseq'){

    $id=$_POST["myid"];
    $seq=$_POST["seq"];
    $name=$_POST["my"];
    $ad_type = getgpc('type');

    if(empty($id) || empty($seq) || empty($name)){
        showmessage('参数错误',"/home.php?mod=spacecp&ac=zanzhushang");
    }
    //var_dump($_POST);
    DB::update("zanzhushang",array("seq"=>$seq,"name"=>$name,'type'=>$ad_type),array("id"=>$id,"uid"=>$_G["uid"]));
    showmessage('修改成功',"home.php?mod=spacecp&ac=zanzhushang");

}

if($op=='edit'){
    $id=$_GET["id"];
    $fk=DB::query("select * from pre_zanzhushang where id=".$id);
    $fkre=DB::fetch($fk);
    //var_dump($fkre);

}


$navtitle="赞助商设置";
$templates='home/spacecp_zanzhushang';
include_once(template($templates));

?>