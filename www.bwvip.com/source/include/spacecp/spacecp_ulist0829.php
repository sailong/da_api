<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_upload.php 22318 2011-04-29 09:34:15Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once './source/class/class_core.php';
//设置标题
$uid = !empty($_GET['uid']) ? $_GET['uid'] : $_G['uid']; 
$getstat = array();
	$getstat = getusrarry($uid);
	
	$ecname=getecprefix();
	
 $navtitle=$getstat['usrnickname'].'的选手管理'; 
$op= !empty($_GET['op']) ? $_GET['op'] : 'list';
$do=$_GET['do'];
$gid=$_GET['gid'];
$team=$_POST['team'];
$fz_id= !empty($_POST['fz_id']) ? $_POST['fz_id'] : '10000';

 $flist = array();
     $query = DB::query("SELECT * FROM ".DB::table('fenzhan')." where  sid=$uid and is_delete=0");
		while ($value = DB::fetch($query)) {
			$flist[] = $value;
	}
$cklist=$_GET['qstr']; 
 


/*赛事选手列表*/
if($op=='list'){ 

	
	//删除选手
	if($do=='del'){
		
		if($gid){DB::query("update ".DB::table('common_member_profile')." set  is_delete=1 WHERE uid=$gid"); 
		 
		showmessage('已删除成功', dreferer(), array(), array('showdialog' => 1, 'closetime' => true));
		}
		
	}
	
   

	//选手移到分站
	if($do=='move') {
		
	 $uids = $_POST['uids'];
	 
	 $team = $_POST['team'];
	 $fz_id = $_POST['fz_id'];
	 $li=explode("`", $uids);  
	 foreach ( $li as $key => $value ) { 
		
		//弹出框 添加分站
		if($fz_id){	
		$count = DB::result_first("SELECT count(1) FROM ".DB::table('fenzhan_members')." WHERE  uid=$value and sid=$uid"); 
		
			$realname = DB::result_first("SELECT realname FROM ".DB::table('common_member_profile')." WHERE uid='$value'");
			if($count) 
			{
			 showmessage('已添加'.$realname.'选手', "home.php?mod=spacecp&ac=ulist&op=list", array(), array('showdialog' => 1, 'closetime' => true));	
			} 
			
	        $team = DB::result_first("SELECT team FROM ".DB::table('common_member_profile')." WHERE  uid=$value and sid=$uid"); 
	        $team_name = DB::result_first("SELECT team_name FROM ".DB::table('golf_team')." WHERE sid=$uid"); 
			
			$sql='insert into  '.DB::table('fenzhan_members')." (fz_id,uid,realname,sid,team_id,team_name)values($fz_id,$value,'$realname',$uid,$team,'".$team_name."')";
			$row = DB::query($sql);
			}
		}
	//弹出框 添加到本赛事
	if($team){	
	 $realname = DB::result_first("SELECT realname FROM ".DB::table('common_member_profile')." WHERE uid='$value'"); 
	$count = DB::result_first("SELECT count(1) FROM ".DB::table('common_member_profile')." WHERE team='$team' and uid=$value and sid=$uid"); 
	if($count) 
	{
	 showmessage('已添加'.$realname.'选手', "home.php?mod=spacecp&ac=ulist&op=fzhy", array(), array('showdialog' => 1, 'closetime' => true));	
	}
	  
	$row = DB::query('update '.DB::table('common_member_profile')." set team='$team',sid=$uid where uid='$value'"); 
	
	} 
		

	 showmessage('已成功添加', "home.php?mod=spacecp&ac=ulist", array(), array('showdialog' => 1, 'closetime' => true));	
 
} 

	
   $tlist = array();
     $query = DB::query("SELECT * FROM ".DB::table('golf_team')." where  sid=$uid");
		while ($value = DB::fetch($query)) {
			$tlist[] = $value;
	}
	$ggid = $_POST['ggid']; 
	$realname =trim($_POST['realname']);
	
	if($ggid)
	{
		$strwhere=" and uid='$ggid'";
		
	}
	if($realname)
	{
		$strwhere=$strwhere." and realname like '%$realname%'";
		
	} 
	
	
	$perpage = 20;
	$page = empty($_GET['page'])?0:intval($_GET['page']);
	if($page<1) $page = 1;
	$start = ($page-1)*$perpage;
	ckstart($start, $perpage);
	if($team)
	{$wh="&team=".$team;
	 $strwhere=$strwhere." and team=".$team;
	}
	if($fz_id)
	{$wh=$wh."&team=".$team;
	 //$strwhere=$strwhere." and fz_id=".$team;
	}
	
	$count = DB::result(DB::query("SELECT count(*) FROM ".DB::table('common_member_profile')."  WHERE sid='$uid' and is_delete=0 $strwhere ORDER BY uid DESC"), 0);
	 $uslist = array();
	if($count) {
		if($page > 1 && $start >=$count) {
			$page--;
			$start = ($page-1)*$perpage;
		}
	$query = DB::query("SELECT * FROM ".DB::table('common_member_profile')."  WHERE sid='$uid' and is_delete=0 $strwhere ORDER BY uid DESC LIMIT $start,$perpage");
		while ($value = DB::fetch($query)) {
			$uslist[] = $value;
		}
	}
	
	$multi = multi($count, $perpage, $page, "home.php?mod=spacecp&ac=ulist".$wh);

}



/*赛事选手积分排行列表*/
if($op=='jlist'){ 

	
	   

  
	$perpage = 20;
	$page = empty($_GET['page'])?0:intval($_GET['page']);
	if($page<1) $page = 1;
	$start = ($page-1)*$perpage;
	ckstart($start, $perpage);
	if($team)
	{$wh="&team=".$team;
	 $strwhere=$strwhere." and team=".$team;
	}
	if($fz_id)
	{$wh=$wh."&team=".$team;
	 //$strwhere=$strwhere." and fz_id=".$team;
	}
	
	$count = DB::result(DB::query("SELECT count(*) FROM ".DB::table('common_member_profile')."  WHERE sid='$uid' and is_delete=0 $strwhere ORDER BY jifen DESC"), 0);
	 $uslist = array();
	if($count) {
		if($page > 1 && $start >=$count) {
			$page--;
			$start = ($page-1)*$perpage;
		}
	$query = DB::query("SELECT * FROM ".DB::table('common_member_profile')."  WHERE sid='$uid' and is_delete=0 $strwhere ORDER BY jifen DESC LIMIT $start,$perpage");
		while ($value = DB::fetch($query)) {
			$uslist[] = $value;
		}
	}
	
	$multi = multi($count, $perpage, $page, "home.php?mod=spacecp&ac=ulist&op=jlist".$wh);

}




/*修改选手信息*/
	if($op=='edit') {
		
		$arr = DB::fetch_first('select uid,realname, team,qdname,sid from '.DB::table('common_member_profile')." where uid='$gid'"); 
		$tlist = array();
		 $query = DB::query("SELECT * FROM ".DB::table('golf_team')." where  sid=$uid");
			while ($value = DB::fetch($query)) {
				$tlist[] = $value;
			}
				//$arr['realname'];
		$id = $_POST['gid'];
		$team = $_POST['team'];
		$qdname = $_POST['qdname'];
		$realname =trim($_POST['realname']);
		if($id){
		$row = DB::query('update '.DB::table('common_member_profile')." set realname='$realname',team='$team',qdname='$qdname' where uid='$id'");
		$team_name=getteamname($team);
		$row = DB::query('update '.DB::table('fenzhan_members')." set realname='$realname',team_id='$team',team_name='$team_name' where uid='$id'");
		
			showmessage('已修改成功', "home.php?mod=spacecp&ac=ulist", array(), array('showdialog' => 1, 'closetime' => true));
		}
	}
/*分站选手管理*/
if($op=='fzhy'){  
	$gid = $_GET['gid']; 
	$ggid = $_POST['ggid']; 
	$realname =trim($_POST['realname']);
	
	if($ggid)
	{
		$strwhere=" and uid='$ggid'";
		
	}
	if($realname)
	{
		$strwhere=$strwhere." and realname like '%$realname%'";
		
	} 
	//删除分站会员
	if($do=='del')
	{
		
		if($gid){DB::query("delete  from ".DB::table('fenzhan_members')." WHERE uid=$gid"); 
		 
		showmessage('已删除成功', dreferer(), array(), array('showdialog' => 1, 'closetime' => true));
		}
	}
	 
	
   $tlist = array();
     $query = DB::query("SELECT * FROM ".DB::table('fenzhan')." where  sid=".$_G['uid']." and is_delete=0");
		while ($value = DB::fetch($query)) {
			$tlist[] = $value;
	} 
	$perpage = 20;
	$page = empty($_GET['page'])?0:intval($_GET['page']);
	if($page<1) $page = 1;
	$start = ($page-1)*$perpage;
	ckstart($start, $perpage);
	
	if($fz_id)
	{$wh="&fz_id=".$fz_id;
	 $strwhere=$strwhere." and fz_id=".$fz_id;
	}
	 
	
	$count = DB::result(DB::query("SELECT count(*) FROM ".DB::table('fenzhan_members')."  WHERE sid='$uid'  $strwhere "), 0);
	 $uslist = array();
	if($count) {
		if($page > 1 && $start >=$count) {
			$page--;
			$start = ($page-1)*$perpage;
		}
	$query = DB::query("SELECT * FROM ".DB::table('fenzhan_members')."  WHERE  sid='$uid'  $strwhere ORDER BY fzm_id DESC LIMIT $start,$perpage");
		while ($value = DB::fetch($query)) {
			$uslist[] = $value;
		}
	}
	
	$multi = multi($count, $perpage, $page, "home.php?mod=spacecp&ac=ulist&op=fzhy".$wh);

}


/*分站队籍管理*/
if($op=='fzdj'){   
	$fz_id = $_GET['fz_id']; 
	$team_id = $_GET['team_id'];  	
	$ge = $_GET['ge'];  
	$sid=$uid;
//添加分站
   if($do=='addfz')
	{	
		$fenz_name=$_POST['fenz_name'];	
		if($fenz_name){			
		$row = DB::query('insert into '.DB::table('fenzhan')." (fenz_name,sid) values ('$fenz_name','$sid')");
		showmessage('已添加成功', "home.php?mod=spacecp&ac=ulist&op=fzdj&do=fz", array(), array('showdialog' => 1, 'closetime' => true));
		}
	}
//添加队籍
	   if($do=='adddj')
	{	
		$team_name=$_POST['team_name'];	  
		if($team_name){		
		$row = DB::query('insert into '.DB::table('golf_team')." (team_name,sid) values ('$team_name','$sid')");
		showmessage('已添加成功', "home.php?mod=spacecp&ac=ulist&op=fzdj&do=dj", array(), array('showdialog' => 1, 'closetime' => true));
		}
	}
   //修改分站
	if($do=='fz'&&$ge=='edit')
	{		
		if($fz_id){
			
		$fzlist = array();
		 $query = DB::query("SELECT * FROM ".DB::table('fenzhan')." where  fz_id=$fz_id");
			while ($value = DB::fetch($query)) {
				$fzlist[] = $value;
			}
		}
	}
	//修改队籍
	if($do=='dj'&&$ge=='edit')
	{		
		if($team_id){
			
		$gflist = array();
		 $query = DB::query("SELECT * FROM ".DB::table('golf_team')." where  team_id=$team_id");
			while ($value = DB::fetch($query)) {
				$gflist[] = $value;
			}
		}
	}
   
    //修改分站
	if($do=='fz'&&$ge=='update')
	{		
	
		$fenz_name=$_POST['fenz_name'];
		
		$fz_id=$_POST['fz_id'];
		if($fz_id){DB::query("update ".DB::table('fenzhan')." set fenz_name='$fenz_name'  WHERE fz_id=$fz_id"); 
		 
		showmessage('已修改成功', 'home.php?mod=spacecp&ac=ulist&op=fzdj&do=fz', array(), array('showdialog' => 1, 'closetime' => true));
		}
	}
	//修改队籍
	if($do=='dj'&&$ge=='update')
	{		
		$team_name=$_POST['team_name'];
		
		$team_id=$_POST['team_id'];
		if($team_id){DB::query("update ".DB::table('golf_team')." set team_name='$team_name'  WHERE team_id=$team_id"); 
		 
		showmessage('已修改成功', 'home.php?mod=spacecp&ac=ulist&op=fzdj&do=dj', array(), array('showdialog' => 1, 'closetime' => true));
		}
	}
   
	//删除分站
	if($do=='fz'&&$ge=='del')
	{		
		if($fz_id){DB::query("delete  from ".DB::table('fenzhan')." WHERE fz_id=$fz_id"); 
		 
		showmessage('已删除成功', dreferer(), array(), array('showdialog' => 1, 'closetime' => true));
		}
	}	
	
	
	//删除队籍
	if($do=='dj'&&$ge=='del')
	{		
		if($team_id){DB::query("delete  from ".DB::table('golf_team')." WHERE team_id=$team_id"); 
		 
		showmessage('已删除成功', dreferer(), array(), array('showdialog' => 1, 'closetime' => true));
		}
	}
	//队籍列表
	if($do=='dj'){
		 $dlist = array();
		 $query = DB::query("SELECT * FROM ".DB::table('golf_team')." where  sid=$uid");
			while ($value = DB::fetch($query)) {
				$dlist[] = $value;
		} 
	}
	//分站列表
	
	if($do=='fz'){
	   $tlist = array();
		 $query = DB::query("SELECT * FROM ".DB::table('fenzhan')." where  sid=$uid  and is_delete=0");
			while ($value = DB::fetch($query)) {
				$tlist[] = $value;
		} 
	}
	
	 

}

/*球场管理*/
if($op=='qcgl'){ 

	
   
	$gid = $_GET['gid']; 
	$ggid = $_POST['field_id']; 
	$realname =trim($_POST['field_name']);
	
	if($ggid)
	{
		$strwhere=" and field_id='$ggid'";
		
	}
	if($realname)
	{
		$strwhere=$strwhere." and field_name like '%$realname%'";
		
	} 
	//删除球场
	if($do=='del')
	{
		
		if($gid){DB::query("delete  from ".DB::table('saishi_qiuc')." WHERE field_id=$gid"); 
		 
		showmessage('已删除成功', dreferer(), array(), array('showdialog' => 1, 'closetime' => true));
		}
	}
	
	
	 
	
	$perpage = 20;
	$page = empty($_GET['page'])?0:intval($_GET['page']);
	if($page<1) $page = 1;
	$start = ($page-1)*$perpage;
	ckstart($start, $perpage); 
	 
	$sql="SELECT count(*) FROM ".DB::table('saishi_qiuc')."  WHERE sid='$uid'  $strwhere ";
	//echo $sql;
	$count = DB::result(DB::query($sql), 0);
	 $uslist = array();
	if($count) {
		if($page > 1 && $start >=$count) {
			$page--;
			$start = ($page-1)*$perpage;
		}
	$query = DB::query("SELECT * FROM ".DB::table('saishi_qiuc')."  WHERE  sid='$uid'  $strwhere ORDER BY id DESC LIMIT $start,$perpage");
		while ($value = DB::fetch($query)) {
			$uslist[] = $value;
		}
	}
	
	$multi = multi($count, $perpage, $page, "home.php?mod=spacecp&ac=ulist&op=qcgl".$wh);

}
 

/*添加选手*/
if($op=='add') {
	$ggid = $_POST['ggid']; 
	$realname =trim($_POST['realname']);
	
	if($ggid)
	{
		$strwhere=" and uid='$ggid'";
		
	}
	if($realname)
	{
		$strwhere=$strwhere." and realname like '%$realname%'";
		
	}
	
	if(($ggid)||($realname))
	{ 
 	$glist = array();
     $query = DB::query('select uid,realname,team,qdname,sid from '.DB::table('common_member_profile').' where 1=1 '.$strwhere.' limit 0,1000');
		while ($value = DB::fetch($query)) {
			$glist[] = $value;
		} 
	}
			//$arr['realname'];
	$id = $_POST['gid'];
	$team = $_POST['team'];
	$qdname = $_POST['qdname'];
	$realname =trim($_POST['realname']);
	if($id){
	$row = DB::query('update '.DB::table('common_member_profile')." set realname='$realname',team='$team',qdname='$qdname' where uid='$id'");
		showmessage('已修改成功', "home.php?mod=spacecp&ac=ulist", array(), array('showdialog' => 1, 'closetime' => true));
	}
}  

/*添加球场*/
if($op=='czqc') {

//地区
$query = DB::query('select * from '.DB::table('common_district')." where upid=0");
while($value = DB::fetch($query)) {
	$area[] = $value;
} 

$fuid = $_POST['fuid']; 
$sid=$uid;
 $field_name = DB::result_first("SELECT fieldname FROM ".DB::table('common_field')." WHERE uid='$fuid'"); 
if($do=='add'){
	$row = DB::query('insert into '.DB::table('saishi_qiuc')." (field_id,field_name,sid) values ('$fuid','$field_name','$sid')");
		showmessage('已修改成功', "home.php?mod=spacecp&ac=ulist&op=qcgl", array(), array('showdialog' => 1, 'closetime' => true));
	}
}  

/*报分员管理*/
if($op=='bfgl'){ 

	
 //所有报分人员，18个
    $bf=DB::query("SELECT a.id,a.username,a.password,a.sid,a.fieldid,a.hole,b.realname AS ss_name,c.`realname` AS qc_name FROM `".DB::table('nd_baofen_users')."` AS a LEFT JOIN ".DB::table('common_member_profile')." AS b ON a.sid=b.uid LEFT JOIN pre_common_member_profile AS c ON a.`fieldid`=c.`uid` where a.sid='$uid'");
    while($row=DB::fetch($bf)){
        $bfarr[]=$row;
    }  
	if($_GET['do']=='del'){
		
		$bid=getgpc("bid");
		if(!empty($bid)){
			$flag=DB::delete("nd_baofen_users",array("id"=>$bid));
			if($flag){ 
				showmessage('删除成功', "home.php?mod=spacecp&ac=ulist&op=bfgl", array(), array('showdialog' => 1, 'closetime' => true));
			}else{
				showmessage("删除失败",$url);
			}
		}else{
			showmessage("参数失败",$url);
		}
		
	}
	
		if($_GET['do']=='add'){
		$ge = $_POST['ge']; 
			
		    if($ge=='add') { 
				$uname=getgpc("username");
				$pwd=getgpc("pwd");
				$sid=$uid;
				//$pwd=md5($pwd);
				//判断用户名是否存在
				$have=DB::fetch_first("SELECT username FROM ".DB::table('nd_baofen_users')." WHERE username='".$uname."' and password='".$pwd."' and sid=$uid");
				if(empty($have["username"])){
					$flag=DB::insert("nd_baofen_users",array("username"=>$uname,"password"=>$pwd,"sid"=>$sid));
					if($flag){ 
						 showmessage('添加成功', "home.php?mod=spacecp&ac=ulist&op=bfgl", array(), array('showdialog' => 1, 'closetime' => true));
					}else{
						showmessage("添加失败",$url);
					}
				}else{
					showmessage("用户名已经存在",$url);
				}
			}else{
				$str1= "用户名：<input type='text' name='username' id='username' /><br /><br />";
				$str1.= "密&nbsp;&nbsp;码：<input type='text' name='pwd' id='pwd' /><br /><br />"; 
		    }
		}
	
	 if($_GET['do']=='dong'){
	
		 $bid = $_GET['bid']; 
			//分站	  
			 $tlist = array();
				 $query = DB::query("SELECT * FROM ".DB::table('fenzhan')." where  sid=$uid  and is_delete=0");
					while ($value = DB::fetch($query)) {
						$tlist[] = $value;
				} 
				
			//球场
			 $uslist = array();
			$query = DB::query("SELECT * FROM ".DB::table('saishi_qiuc')."  WHERE  sid='$uid'");
				while ($value = DB::fetch($query)) {
					$uslist[] = $value;
			} 
		  
		 //如果有填写内容显示出来
		 if($_POST['up']==1){
			$sid=$uid;   //赛事id
			$qcid=getgpc("field_id");     //球场id
			$qd=getgpc("qd");		//球洞
			$fz_id=getgpc("fz_id");    //分站
			$bid=getgpc("bid");   //报分员id
			if(empty($bid)){
			   showmessage("参数失败",$url);
			}
			if(!empty($qd)){
				foreach($qd as $key=>$t){
					if($key==0){
						$hole=$t;
					}else{
						$hole.=",".$t;
					}
				}
			} 
	
			$flag=DB::update("nd_baofen_users",array("sid"=>$sid,"fieldid"=>$qcid,"fz_id"=>$fz_id,"hole"=>$hole),array("id"=>$bid));
			if($flag){ 
		        showmessage('球洞分配成功', "home.php?mod=spacecp&ac=ulist&op=bfgl", array(), array('showdialog' => 1, 'closetime' => true));
			}else{
				showmessage("球洞分配失败",$url);
			}
			 
			 
			 }
		 
			$ishave=DB::fetch_first("SELECT * FROM ".DB::table('nd_baofen_users')." WHERE id=".$bid);
			$harr=explode(",",$ishave["hole"]); 
			 for($i=1;$i<=18;$i++){
				if(in_array($i,$harr)){
					$str.='球洞'.$i.'<input type="checkbox" name="qd[]" checked="true" value="'.$i.'" />&nbsp;&nbsp;';
				}else{
					$str.='球洞'.$i.'<input type="checkbox" name="qd[]" value="'.$i.'" />&nbsp;&nbsp;';
				}
			}
	 }

}

 
 
 
$templates = 'home/spacecp_ulist';
include_once template($templates);

//获取队籍名字
function getteamname($team_id) {
		$teame_name = DB::result_first("SELECT team_name FROM ".DB::table('golf_team')." WHERE team_id='$team_id' "); 
	return $teame_name;
}//获取分站名字
function getfzname($fz_id) {
		$fenz_name = DB::result_first("SELECT fenz_name FROM ".DB::table('fenzhan')." WHERE fz_id='$fz_id' "); 
	return $fenz_name;
}

 
?>