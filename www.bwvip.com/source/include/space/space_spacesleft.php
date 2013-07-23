<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: space_index.php 19160 2010-12-20 08:57:24Z liulanbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$uid = $_GET['uid'];


if(empty($_G['uid'])) {
	//关注
	$query = DB::query("select b.remark, m.uid, m.username from jishigou_buddys as b left join jishigou_members as m on b.buddyid=m.uid where b.uid='$uid' limit 4");
	while($row = mysql_fetch_assoc($query)) {
		$buddys[] = $row;
	}

	//博客
	$query = DB::query("SELECT b.uid, b.subject, bf.message FROM ".DB::table('home_blog')." b LEFT JOIN ".DB::table('home_blogfield')." bf ON bf.blogid=b.blogid WHERE b.uid='$uid' order by b.blogid desc limit 8");
	while($row = mysql_fetch_assoc($query)) {
		$blogs[] = $row;
	}

	//粉丝
	$query = DB::query("select b.remark, m.uid, m.username from jishigou_buddys as b left join jishigou_members as m on b.uid=m.uid where b.buddyid='$uid' limit 4");
	while($row = mysql_fetch_assoc($query)) {
		$fans[] = $row;
	}

	//访问
	$query = DB::query("SELECT uid, vuid, vusername FROM ".DB::table('home_visitor')." WHERE uid='$uid' order by dateline desc limit 6");
	while($row = mysql_fetch_assoc($query)) {
		$visit[] = $row;
	}
echo '<pre />';
print_r($buddys);
	include_once(template('home/spaces_left'));
}
?>