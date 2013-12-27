<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_portalcategory.php 21674 2011-04-07 08:09:05Z zhangguosheng $
 */

if(!defined('IN_DISCUZ') || !defined('IN_DISCUZ')) {
	exit('Access Denied');
}

cpheader();
$op=in_array($_GET["op"],array("album","del","did","add","page","search"))?$_GET["op"]:'album';
shownav('dazheng', '挑战赛推荐');
$navmenu[0] = array('相册推荐', 'realbum&op=album', $operation == 'album');
showsubmenu('挑战赛推荐', $navmenu);
echo "<br />";
showformheader("realbum&op=search");
echo "uid：<input type='text' name='suid' />&nbsp;&nbsp;&nbsp;用户名：<input type='text' name='suname' />&nbsp;&nbsp;标题<input type='text' name='stitle' /><br /><br />";
showsubmit("searchsubmit");
showformfooter();
echo "<br /><br />";
//所有分类
    $cata=DB::query("SELECT * FROM `pre_recommend_group` WHERE group_id>42");
    while($ro=DB::fetch($cata)){
        $cataarr[]=$ro;
    }
//列出来所有的
$all=DB::query("SELECT a.cid,b.uid,a.sort,a.rectype,b.username,b.albumname,b.albumname,b.albumid,b.pic FROM pre_home_recommend AS a LEFT JOIN pre_home_album AS b ON a.cid=b.albumid WHERE a.rectype=43");
while($arow=DB::fetch($all)){
    $arowarr[]=$arow;
}

//if(submitcheck('searchsubmit', true)) {
if($op=='search'){
         //echo "<pre>";
        //print_r($_POST);
        $limitpage=10;   //每页显示多少个
        //$limitpage = mob_perpage($limitpage);
	    $page=empty($_GET["page"])?1:$_GET["page"];  //page是必须的一样的
	    $page=trim(intval($page));
        $start = ($page-1)*$limitpage;   //开始的条数
        //ckstart($start, $limitpage);
        
        
        $sid=empty($_POST["suid"])?$_GET["suid"]:$_POST["suid"];
        $suname=empty($_POST["suname"])?$_GET["suname"]:$_POST["suname"];
        $stitle=empty($_POST["stitle"])?$_GET["stitle"]:$_POST["stitle"];

        if(!empty($sid)){
            $sid=intval($sid);
            $strid=" a.uid=".$sid."";
            $where='where'.$strid;
            $wh="&suid=".$sid;
        }
        if(!empty($suname)){
            $strname=" a.username ='".$suname."'";
            $where="where".$strname;
            $wh="&suname=".$suname;
        }
        if(!empty($stitle)){
            $strtitle=" a.albumname = '".$stitle."'";
             $where='where'.$strtitle;
             $wh="&stitle=".$stitle;
        }
        if(empty($sid) && empty($suname) && empty($stitle)){
            $where='';
        }
        //echo "SELECT albumid,albumname,uid,picnum,pic FROM pre_home_album ".$where;
        //$sq=DB::query("SELECT albumid,albumname,uid,username,picnum,pic,rectype FROM pre_home_album ".$where);
        $sq=DB::query("SELECT b.cid,a.uid,b.sort,b.rectype,a.username,a.albumname,a.albumid,a.pic FROM pre_home_album AS a LEFT JOIN pre_home_recommend AS b ON b.cid=a.albumid ".$where." limit ".$start.",".$limitpage);
        
        while($row=DB::fetch($sq)){
            $seararr[]=$row;
        }
       
        //print_r($seararr);
       
        $theurl = 'admin.php?action=realbum&op=search'.$wh; //地址

	    //判断总条数
        $countnum = DB::result(DB::query("SELECT count(0) num FROM pre_home_album AS a LEFT JOIN pre_home_recommend AS b ON b.cid=a.albumid ".$where));

        //判断 如果用户随便输入一个大数,有没有超出最高限度
        $allpage=ceil($countnum/$limitpage);
        //echo $allpage;
        if($page>$allpage){
            header("Location:/admin.php?action=realbum");
            exit;
        }
     
        $disppage = multi($countnum, $limitpage, $page, $theurl);

       
        listhtml($seararr);
        echo $disppage;
    //}
    //elseif($op=='del'){
       // echo "video";
      // print_r($_GET);
    //}
}
if($op=='album' && !submitcheck('searchsubmit', true)){
    listhtml($arowarr);
}
if($op=='add'){
    //echo "ddsadfaf";
    //echo "<pre>";
    //var_dump($_POST);
    
    $id=$_POST["uid"];
    $uname=$_POST["username"];
    $sort=$_POST["sort"];
    $rectype=$_POST["rectype"];
    $albumid=$_POST["albumid"];
    
    if(empty($id) || empty($uname) || empty($rectype) || empty($albumid)){
        cpmsg("参数错误","action=realbum");
    }
    $flag=DB::insert("home_recommend",array("cid"=>$albumid,"uid"=>$id,"sort"=>$sort,"username"=>$uname,"groupid"=>"50","rectype"=>$rectype,"dateline"=>time()));
    if($flag){
        cpmsg("推荐成功","action=realbum");
    }else{
        cpmsg("推荐失败","action=realbum");
    }
}
if($op=='del'){
    //echo "<pre>";
    //print_r($_POST);
    /*
    [rectype] => 43
    [sub] => 取消推荐
    [uid] => 4
    [username] => aaa   rectype=43 AND cid= AND uid=
    [albumid] => 4
    */
    $rectype=$_POST["rectype"];
    $albumid=$_POST["albumid"];
    $sort=$_POST["sort"];
    $id=$_POST["uid"];
    if($_POST["sub"]=='取消推荐'){
        $flag=DB::delete("home_recommend",array("rectype"=>$rectype,"cid"=>$albumid,"uid"=>$id));
        if($flag){
            cpmsg("取消成功","action=realbum");
        }else{
            cpmsg("取消失败","action=realbum");
        }
    }elseif($_POST["sub"]=='重新推荐'){
        $flag=DB::update("home_recommend",array("rectype"=>$rectype,"sort"=>$sort),array("cid"=>$albumid,"uid"=>$id));
        if($flag){
            cpmsg("推荐成功","action=realbum");
        }else{
            cpmsg("推荐失败","action=realbum");
        }
    }
    
}
function listhtml($seararr){
    global $cataarr;
    if(!empty($seararr)){
            echo "<table width='100%'>";
             echo "<tr><td>排序</td><td>用户名</td><td>缩略图</td><td>标题</td><td>分类</td><td>操作</td></tr>";
             foreach($seararr as $lie){
                if(empty($lie["sort"])){
                    $lie["sort"]=0;
                }
                if($lie["cid"]){
                    showformheader("realbum&op=del");
                }else{
                    showformheader("realbum&op=add");
                }
                 echo "<tr><td><input type='text' value='".$lie["sort"]."' name='sort' /></td><td>".$lie["username"]."</td><td><img src='data/attachment/album/".$lie["pic"]."'  width='150' height='80'></td><td>".$lie["albumname"]."</td><td><select name='rectype'>";
                 foreach($cataarr as $v){
                    if($v["group_id"]==$lie["rectype"]){
                        echo "<option value='".$v["group_id"]."' selected=''>".$v["group_name"]."</option>";
                    }else{
                        echo "<option value='".$v["group_id"]."'>".$v["group_name"]."</option>";   
                    }
                    
                 }
                 if($lie["cid"]){
                    echo "</select></td><td><input type='submit' value='取消推荐' name='sub' /> | <input type='submit' value='重新推荐' name='sub' /></td>";
                 }else{
                    echo "</select></td><td><input type='submit' value='推荐' name='sub' /></td>";   
                 }
                 
                 
                  echo "<input type='hidden' value='".$lie["uid"]."' name='uid' />";
                 echo "<input type='hidden' value='".$lie["username"]."' name='username' />";
                 echo "<input type='hidden' value='".$lie["albumid"]."' name='albumid' />";
                 echo "</tr>";
                 showformfooter();
             }
            echo "</table>";
        }
}
?>