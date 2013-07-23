<?php
/**
*      [Discuz!] (C)2001-2099 Comsenz Inc.
*      This is NOT a freeware, use is subject to license terms
*
*      $Id: daz_app_view_guess_tag.php 19158 2012/3/5 08:21:50Z angf $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}



$tag_id       = getgpc('tag_id');
$guess_id     = getgpc('guess_id');
$guess_object = getgpc('guess_object');
$ac           = getgpc('ac');



/*用户对明星竞猜动作*/
if($ac=='vote_guessing'){
	$and_where = '';
	if($guess_object==2 && $_G['gp_relevance_value']) $and_where =" and relevance_value = '".urldecode($_G['gp_relevance_value'])."'";
	 $user_submit_guess=array(
		'guess_id'     => $guess_id,
		'tag_id'       => $tag_id,
		'relevance_id' => getgpc('qx_uid'), //关联ID = 球星ID
		'guess_object' => $guess_object,
		'record_time'  => time(),
		'uid'          => $_G['uid'],
		);
	DB::insert('daz_guess_record ',$user_submit_guess,true);//添加记录

	DB::query(" UPDATE ".DB::table('daz_guess_options')." SET `join_guess_users` = CONCAT(join_guess_users,'','".$_G['uid'].",'),`guess_num`=guess_num+1 WHERE guess_id ='".$guess_id."' and guess_tag_id = '".$tag_id."' and guess_object ='".$guess_object."' and relevance_id ='".getgpc('qx_uid')."'".$and_where);//修改原有的投票人和数码

	DB::query(" UPDATE ".DB::table('daz_guessing')." SET `total_guess_num` = total_guess_num+1 WHERE gu_id='".$guess_id."'");

	if(DB::insert_id())	{   return true;}else{	return false;}
	exit;
}

$is_guess = DB::fetch_first("SELECT `record_id` FROM ".DB::table('daz_guess_record')." where uid='".$_G['uid']."' and tag_id='".$tag_id."' and guess_id='".$guess_id."' and guess_object='".$guess_object."'");


/*竞猜活动信息*/
$guess_info  = DB::fetch_first("SELECT * FROM ".DB::table("daz_guessing")." WHERE gu_id='".$guess_id."'");



/*参加竞猜人数 进行补零操作*/
$join_user_num  = !empty($guess_info['total_guess_num']) ? $guess_info['total_guess_num'] : '1';
if(count($join_user_num)<6){
   for($i=count($join_user_num);$i<6;$i++){
    	$bu_ling .='0';
   }
}
$join_user_num = $bu_ling.$join_user_num;




/*活动下标签云*/
$attr_query = DB::query(" select `attr_id`,`attr_show_value` From ".DB::table('daz_attr')." where attr_id IN (".$guess_info['guess_tag'].")" );
while($result_attrs = DB::fetch($attr_query)){
   $attrs[$result_attrs['attr_id']]=$result_attrs['attr_show_value'];
}




/*针对球星 用户信息*/
if($guess_object==1){
   /*原来的版本*/
  // $qx_query = DB::query(" SELECT cm.`uid`,cm.`username` FROM ".DB::table('common_member')." as cm LEFT JOIN ".DB::table('home_saishi_csqy')." as sc ON sc.userid=cm.uid  where cm.uid IN( SELECT `relevance_id` FROM ".DB::table('daz_guess_options')." where guess_id='".$guess_id."' and guess_tag_id='".$tag_id."' and guess_object =1)  order by sc.seq asc" );

   /*获取赛事下 球星 球员列表*/
   $qx_query = DB::query(" select cmp.uid,cmp.realname as username FROM ".DB::table('daz_guessing')." as dzg LEFT JOIN ".DB::table('home_saishi_csqy')." as sc ON  sc.groupid = dzg.saishi_id LEFT JOIN ".DB::table('common_member_profile')." as cmp ON sc.userid =cmp.uid  where dzg.gu_id = '".$guess_id."' order by sc.seq asc");


   while($qx_list_result = DB::fetch($qx_query) ){
		if($is_guess==""){
				$a_tag = "<a href=\"javascript:;\" onclick=\"showDialog('你确定选：".$qx_list_result['username']."','confirm','您确定吗？','vote_guessing(".$guess_id.",".$tag_id.",".$qx_list_result['uid'].",".$guess_object.")')\"><div class=\"cornerBL\"></div></a>";
		}elseif($is_guess || $guess_info['end_time']>time()){
				$message ="你已经参加了本标签的竞猜";
				if(time()>$guess_info['end_time']) $message =" 活动已经结束啦，等待下次吧！";
				$a_tag ="<a onclick=\"showDialog('".$message."<br> 结果将在 ".date('Y-m-d',$guess_info['end_time'])." 公布结果 ')\"><div class=\"cornerBL\"></div></a>";
		}
		$guess_ob_list[$qx_list_result['uid']]['username']   = $qx_list_result['username'];
		$guess_ob_list[$qx_list_result['uid']]['guess_num']  = $qx_list_result['guess_num'];
		$guess_ob_list[$qx_list_result['uid']]['uid']        = $_G['uid'];
		$guess_ob_list[$qx_list_result['uid']]['a']          = $a_tag;
		$guess_ob_list[$qx_list_result['uid']]['pic']        = avatar($qx_list_result['uid'], 'middle', true, false,false);
   }

}



/*针对国际 国际信息*/
elseif($guess_object==2){
   $gj_query = DB::query(" SELECT * FROM ".DB::table('daz_guess_options')." where guess_id='".$guess_id."' and guess_tag_id='".$tag_id."' and guess_object =2 ");
   while($gj_list_result = DB::fetch($gj_query) ){
	   if($is_guess==""){
			$a_tag ="<a onclick=\"vote_guessing(".$guess_id.",".$tag_id.",0,".$guess_object.",'".urlencode($gj_list_result['relevance_value'])."')\"><div class=\"cornerTR\"></div></a>";
	   }else{
			$message ="你已经参加了本标签的竞猜";
			if(time()>$guess_info['end_time']) $message =" 活动已经结束啦，等待下次吧！";
			$a_tag ="<a onclick=\"showDialog('".$message."<br> 结果将在 ".date('Y-m-d',$guess_info['end_time'])." 公布结果 ')\"><div class=\"cornerBL\"></div></a>";
	   }

		$guess_ob_list[$gj_list_result['op_id']]['relevance_value']  = $gj_list_result['relevance_value'];
		$guess_ob_list[$gj_list_result['op_id']]['guess_num']        = $gj_list_result['guess_num'];
		$guess_ob_list[$gj_list_result['op_id']]['a']                = $a_tag;
   }
}

/*竞猜结果头像*/

$g_jieguo_query = DB::query(" SELECT `join_guess_users` FROM ".DB::table('daz_guess_options')." WHERE guess_id='".$guess_id."' and guess_tag_id='".$tag_id."' and guess_object = '".$guess_object."' and is_answer=1");
$user_id_string = "";
while($result = DB::fetch($g_jieguo_query)){
	if($result){
       $user_id_string .= $result['join_guess_users'];
	   $explode_user  = explode(',',$result['join_guess_users']);
	   foreach($explode_user as $value){
		 if($value){
		   $jieguo_users[$value]['img'] =  avatar($value, 'small', true, false,false);
		   $jieguo_users[$value]['uid'] =  $value;
		 }
	   }
	}
}

$user_query = DB::query(" SELECT `username`,`uid` FROM ".DB::table('ucenter_members')." WHERE uid IN(".$user_id_string."0)");
while($user_result = DB::fetch($user_query)){
   $users[$user_result['uid']] = $user_result['username'];
}

include_once(template('daz_app/view_guess_tag'));
?>