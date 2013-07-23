<?php
/*******
*auto:xgw
* date:2012年2月16日
* info:取赛事的数据
* *********/

include 'source/function/function_group.php';

$gid=$_G['groupid'];   //组id  现在的是25
//是否显示管理员登陆
//var_dump($_G["uid"]);
if($_G["uid"]==$_GET["uid"]){
   $string="yes";
}else{
   $string="no";
}
//echo $string;

space_merge($space, 'count');



/**赛事用户变成 赛事提醒用户 下面是对应的 赛事体系用户的uid**/
$event_system = array('1888968','1888967','1888969');
$event_id = $uid;
if(in_array($uid,$event_system)){
    $event_id = DB::result_first("select rm_event from ".DB::table("common_member_profile")." where uid = ".$uid);
}


//新的取用户信息
$query = DB::query("SELECT uid, fans_count, follow_count,topic_count,username FROM jishigou_members where uid=".$uid);
		while($value=DB::fetch($query)) {
		$username=$value['username'];
		$fans_count=$value['fans_count'];     //粉丝数
		$follow_count=$value['follow_count'];      //关注数
		$topic_count=$value['topic_count'];	      //微博数

		$fans='weibo/index.php?mod='.$username.'&code=fans';
		$fansurl='home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.base64_encode($fans);

		$follow='weibo/index.php?mod='.$username.'&code=follow';
		$followurl='home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.base64_encode($follow);

		$wbs='weibo/';
		$wbsurl='home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.base64_encode($wbs);
}




//取真实姓名和积分
$query = DB::query("SELECT b.uid,b.field1,b.realname,b.bio,b.field2,b.field3,a.credits FROM ".DB::table('common_member_profile')." as b INNER JOIN ".DB::table('common_member')." as a ON a.uid=b.uid where a.uid=$uid");
	 $value = DB::fetch($query);
	  // var_dump($value);
		$realname=$value['realname'];		//真实姓名
		$bio=$info=$value['bio'];       //个人简介
        //$info=$value["field2"];   //公司简介
		$field3=$value['field3'];     //企业logo  如果没有，是不是给一个默认的
		$credits=$value['credits'];     //积分
        $qiyename=$value["field1"];    //企业名称
		//var_dump($qiyename);




//取博客
$blogsql = DB::query("SELECT blogid,uid,username,subject FROM ".DB::table('home_blog')."  where uid=$uid ORDER BY dateline DESC limit 6");
while($rowblog = DB::fetch($blogsql)) {
    $rowblog["subject"]=utf8Substr($rowblog["subject"],0,14);
	$leftblog[] = $rowblog;
}



//var_dump($leftblog);
//取相册
$album=DB::query("SELECT albumid,albumname,username, picnum, pic FROM ".DB::table('home_album')." WHERE uid ={$uid} and picnum>=1 ORDER BY updatetime DESC LIMIT 0,6 ");
while($rowalbum = DB::fetch($album)) {
    $rowalbum["albumname"]=utf8Substr($rowalbum["albumname"],0,5);
	$albumre[] = $rowalbum;
}




//var_dump($albumre);
//取参赛明星
function cansaimingxing($islimit){
    global $uid,$event_id;
    if($islimit=='all'){
        $islimit='';
    }else{
        $islimit='limit 6';
    }
    if($uid=='1000333'){
        $listqiuxing="SELECT uid AS userid,realname FROM pre_home_dazbm WHERE game_s_type={$uid} AND pay_status=1 ORDER BY `addtime` DESC ".$islimit;
    }else{
        $listqiuxing="select a.userid,b.username,c.realname from pre_home_saishi_csqy a left join pre_ucenter_members b on a.userid=b.uid left join pre_common_member_profile c on a.userid=c.uid where a.groupid=".$event_id." order by a.seq asc ".$islimit;
    }
    //echo $listqiuxing;
	$listre=DB::query($listqiuxing);
	while ($rowre=DB::fetch($listre)){
		if(!empty($rowre["realname"])){
			$rowre["cname"]=$rowre["realname"];
            $rowre["subname"]=utf8Substr($rowre["cname"],0,6);
		}
		$cansaire[]=$rowre;
	}
    return $cansaire;
}



$cansaire=cansaimingxing();       //只取6个
$allmingxing=cansaimingxing('all');    //所有的参赛球员
//var_dump($allmingxing);


$query = DB::query("select t.id, t.name from jishigou_tag as t left join jishigou_my_topic_tag as mt on mt.tag_id=t.id where mt.user_id='$uid' group by mt.tag_id order by t.dateline desc limit 3");
while($row = mysql_fetch_assoc($query)) {
	$topic[] = $row;
}

//标签
$labela = DB::query("select tag_name from jishigou_user_tag_fields where uid='$uid' order by id desc limit 8");
while($row = mysql_fetch_assoc($labela)) {
	$tags[] = $row;
}


//参赛场地

$sqlcd=DB::query("select a.cdid,b.fieldimg,c.field1 from pre_home_saishi_jbqc a,pre_common_field b,pre_common_member_profile c where a.cdid=b.uid and a.cdid=c.uid and a.groupid=".$event_id." order by a.seq asc limit 1");
		$cdarr=DB::fetch($sqlcd);
		//数据库中有的结尾少了一个g所以要判断一下
		if(substr($cdarr["fieldimg"],-3)=='.jp'){
			$cdarr["fieldimg"]=str_replace(".jp",".jpg",$cdarr["fieldimg"]);
		}
        $cdarr["field1"]=utf8Substr($cdarr["field1"],0,14);

//var_dump($cdarr);
//全部参赛场地
$allchangdi=DB::query("SELECT a.cdid,b.fieldimg,c.field1 FROM pre_home_saishi_jbqc a,pre_common_field b,pre_common_member_profile c WHERE a.cdid=b.uid AND a.cdid=c.uid AND a.groupid=".$uid." ORDER BY a.seq ASC limit 16");
while($cdarra=DB::fetch($allchangdi)){
    //数据库中有的结尾少了一个g所以要判断一下
    if(substr($cdarra["fieldimg"],-3)=='.jp'){
        $cdarra["fieldimg"]=str_replace(".jp",".jpg",$cdarra["fieldimg"]);
    }
    $cdarra["field1"]=utf8Substr($cdarra["field1"],0,20);
    $endarr[]=$cdarra;
}

//*********截取中文字符
function utf8Substr($str, $from, $len)
{
   return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
   '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
   '$1',$str);
}

//赛事信息
$ssinfo=DB::query("select id,name,start,end,groupid,address from pre_groupinfo_25 where groupid=".$uid);
$ssre=DB::fetch($ssinfo);
//var_dump($ssre);


//视频
$qvdio = DB::query("select hv.vid, hv.videoid, hv.videotypeid, hv.uid, hv.username, hv.title, hvp.* from ".DB::table('home_video')." as hv left join ".DB::table('home_videopath')." as hvp on hvp.vpid=hv.vpid where hv.uid='$uid' AND hvp.vpid IS NOT NULL AND hvp.filepath IS NOT NULL order by hv.dateline desc limit 6");
while($row = mysql_fetch_assoc($qvdio)) {
	$row['dateline'] = date('Y-m-d', ($row['dateline'] ? $row['dateline'] : '0'));
	$row['title'] = strlen($row['title']) <= 9 ? $row['title'] : mb_substr($row['title'], '0', '5', 'utf-8');
	$spacevideo[] = $row;
}
//var_dump($spacevideo);


//读取logo
$list=DB::query("select logo from ".DB::table("qiye_logo")." where uid=".$uid);
$listre=DB::fetch($list);
$listpath="static/space/qiye_logo/".$listre["logo"];
//echo $listpath;
if(empty($listre)){
    $listpath="";
}

//var_dump($album[albumname]);
//var_dump($space[views]);
//左侧广告一
$leftad=DB::query("SELECT imglogo,adverturl FROM pre_home_advert WHERE id=19");
$leftre=DB::fetch($leftad);
//左侧广告二
$leftsql=DB::query("SELECT imglogo,adverturl FROM pre_home_advert WHERE id=26");
$lefttwo=DB::fetch($leftsql);

//是否关注
$row = DB::fetch_first("select id from jishigou_buddys where uid='".$_G['uid']."' and buddyid='$uid' limit 1");
if($row) {
	$guanzhu['buddy'] = '1';
} else {
	$guanzhu['buddy'] = '0';
}





/*
*获取赛事下 所有明星的 部分粉丝团
* @param array star_ids
* return array $fans_list
* author angf
*/
function get_event_star_fans($star_ids='') {
    if(!empty($star_ids)){
        foreach($star_ids as $value){
            $ids.= $value.',';
        }
        $id_string = substr($ids,0,strlen($ids)-1);
        $query =  DB::query(" select b.uid, b.buddyid,p.realname from jishigou_buddys as b LEFT JOIN pre_common_member_profile as p ON p.uid = b.uid LEFT JOIN pre_common_member AS pcm  ON pcm.uid =p.uid where b.buddyid IN (".$id_string.") and pcm.groupid=10 GROUP BY b.uid ORDER BY b.id desc limit 9");
        while($result = DB::fetch($query)){
            $result['avatar'] =  avatar($result['uid'],'middle',true);
            $result['realname'] =  utf8Substr($result["realname"],0,5);
            $rows[] = $result;
        }
       return $rows;
    }
}


/*获取赛事或者赛事体系的 【整体】 左右的广告
 *$type 广告类型 0 赞助商 1 大正竞猜 2 赛事行程
 *return array;
 *author angf
*/
function get_event_ads($ad_type=0,$uid="",$_substr=0) {
    global $_G;
    if($uid){
        $query=DB::query("select name,href,logo from pre_zanzhushang where uid=".$uid." and type=".$ad_type." order by seq asc limit 20");
        while($result=DB::fetch($query)){
            if($_substr)  $result["name"]=utf8Substr($result["name"],0,5);  //截取
            $ads[]=$result;
        }
        return $ads;
    }
}

/*
 * 获取专家  球星的列表
 * $groupid  主用户uid $user_type 用户类型 0= 球星 1= 专家
 * return array
 * author angf
 */
 function get_event_experts($groupid,$user_type=0) {
    if($groupid){
        $listqiuxing="select a.userid,a.seq,b.username,c.realname from pre_home_saishi_csqy a left join pre_ucenter_members b on a.userid=b.uid left join pre_common_member_profile c on a.userid=c.uid where a.groupid=".$groupid." and a.user_type='".$user_type."' order by a.seq asc";
        $listre=DB::query($listqiuxing);
        while ($row=DB::fetch($listre)){
            $earr[]=$row;
        }
        return $earr ;
    }
 }

?>