<?php
/*******
*auto:xgw
* date:2012年2月27日
* info:取球场的信息
* *********/
include 'source/function/function_group.php';
//是否显示管理员登陆
if($_G["uid"]==$_GET["uid"]){
   $string="yes";
}else{
   $string="no";
}


//新的取用户信息
$query = DB::query("SELECT uid, fans_count, follow_count,topic_count,username FROM jishigou_members  where uid=$uid");
    while($value=DB::fetch($query)) {
		$username=$value['username'];
		$fans_count=$value['fans_count'];
		$follow_count=$value['follow_count'];
		$topic_count=$value['topic_count'];

		$fans='weibo/index.php?mod='.$username.'&code=fans';
		$fansurl='home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.base64_encode($fans);

		$follow='weibo/index.php?mod='.$username.'&code=follow';
		$followurl='home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.base64_encode($follow);

		$wbs='weibo/';
		$wbsurl='home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.base64_encode($wbs);
    }




//取真实姓名和积分
$query = DB::query("SELECT b.uid, b.realname,b.bio,b.field3,a.credits,b.field1 FROM ".DB::table('common_member_profile')." as b INNER JOIN ".DB::table('common_member')." as a ON a.uid=b.uid where a.uid=$uid");
	 while($value = DB::fetch($query)) {
		$realname=$value['realname'];		//真实姓名
		$bio=$value['bio'];       //简介
		$field3=$value['field3'];     //企业logo  如果没有，是不是给一个默认的
		$credits=$value['credits'];     //积分
        $qiyename=$compy=$value["field1"];     //公司名称
		//var_dump($field3);
        //var_dump($compy);
		}

//取博客

$blogsql = DB::query("SELECT blogid,uid,username,subject FROM ".DB::table('home_blog')."  where uid=$uid ORDER BY dateline DESC limit 6");
while($rowblog = DB::fetch($blogsql)) {
    $rowblog["subject"]=utf8Substr($rowblog["subject"],0,14);
	$leftblog[] = $rowblog;
}
//var_dump($leftblog);
//取相册
$album=DB::query("SELECT albumid,albumname,username, picnum, pic FROM ".DB::table('home_album')." WHERE uid ={$uid} ORDER BY updatetime DESC LIMIT 0,6 ");
while($rowalbum = DB::fetch($album)) {
    $rowalbum["albumname"]=utf8Substr($rowalbum["albumname"],0,14);
	$albumre[] = $rowalbum;
}
//var_dump($albumre);

//参加的话题
	$toptalk = DB::query("select content from jishigou_topic where uid='$uid' order by dateline desc limit 6");
	$str = '';
	while($row = mysql_fetch_assoc($toptalk)) {
		$str .= $row['content'];
	}
	if($str) {
		$num = substr_count($str, '#')/2;
		for($i = 0; $i < $num; $i++) {
			$start = strpos($str, '#');
			$end = strpos($str, '#', $start+1);
			$len = $end - $start;
			$tag .= substr($str, $start, $len+1).',';
			$str = substr($str, $end+1);
		}

		$tag=str_replace("#","", $tag);
		$topic = array_unique(explode(',', $tag));
	} else {
		$topic = '';
	}
	//var_dump($topic);


//标签
$labela = DB::query("select tag_name from jishigou_user_tag_fields where uid='$uid' order by id desc limit 8");
while($row = mysql_fetch_assoc($labela)) {
	$tags[] = $row;
}
//var_dump($tags);
//*********截取中文字符
function utf8Substr($str, $from, $len)
{
   return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
   '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
   '$1',$str);
}

//场地信息
$ssinfo=DB::query("select realname,field1,field2 from pre_common_member_profile where uid=".$uid);
$ssre=DB::fetch($ssinfo);
//var_dump($ssre);



//成绩卡排行

$query = DB::query("select cs.id, cs.uid, cs.fuid, cs.total_score, cmp.realname from ".DB::table('common_score')." cs left join ".DB::table('common_member_profile')." cmp on cmp.uid=cs.uid where cs.fuid='".$_GET['uid']."' and cs.status='2' and cs.total_score>50 order by cs.total_score asc limit 10");
$i = 0;
while($row = DB::fetch($query)) {
	$i++;
	$row['rank'] = $i;
	$row['realname'] = (strlen($row['realname']) < 12) ? $row['realname'] : mb_substr($row['realname'], '0', '5', 'utf-8');
    $rankscore[] = $row;
}


$query = DB::query("select cs.id, cs.uid, cs.tee, cs.dateline, cmp.realname from ".DB::table('common_score')." as cs left join ".DB::table('common_member_profile')." as cmp on cmp.uid=cs.uid where cs.fuid='".$_GET['uid']."' and cs.status='2'  and cs.total_score>50 group by cs.uid order by cs.total_score asc limit 10");
while($row = DB::fetch($query)) {
	$row['dateline'] = date('Y-m-d H:i:s', $row['dateline']);
	$row['realname'] = (strlen($row['realname']) < 5) ? $row['realname'] : mb_substr($row['realname'], 0, 5, 'utf-8');
	$rank[] = $row;
}
foreach($rank as $k=>$v) {
	$query = DB::query("select total_score from ".DB::table('common_score')." where uid='".$v['uid']."' and status='2' order by total_score asc limit 3");
	$total = 0;
	$i = 0;
	while($row = DB::fetch($query)) {
		$i++;
		$total += $row['total_score'];
	}
	if($i < 3) {
		$total_first = DB::fetch_first("select total_score from ".DB::table('common_score')." where uid='".$v['uid']."' and status='2' order by total_score asc");
		$total_score = $total_first['total_score'];
	} else {
		$total_score = round($total/3, 2);
	}
	$rank[$k]['rank'] = $k+1;
	$rank[$k]['total_score'] = $total_score;
}
$rankscore = scorerank($rank);

function scorerank($arr) {
	$count = count($arr);
	$t = 0;
	for($i = 0; $i < $count; $i++) {
		for($j = $i; $j < $count; $j++) {
			if($arr[$i]['total_score'] > $arr[$j]['total_score']) {
				$t = $arr[$i];
				$arr[$i] = $arr[$j];
				$arr[$j] = $t;
			}
		}
	}
	return $arr;
}

 
//var_dump($karow);
//视频
	$qvdio = DB::query("select hv.vid, hv.videoid, hv.videotypeid, hv.uid, hv.username, hv.title, hvp.* from ".DB::table('home_video')." as hv left join ".DB::table('home_videopath')." as hvp on hvp.vpid=hv.vpid where hv.uid='$uid' AND hvp.images !='' group by hv.videoid order by hv.dateline desc limit 6");
	while($row = mysql_fetch_assoc($qvdio)) {
		$row['dateline'] = date('Y-m-d', ($row['dateline'] ? $row['dateline'] : '0'));
		$row['title'] = strlen($row['title']) <= 9 ? $row['title'] : mb_substr($row['title'], '0', '5', 'utf-8');
		$spacevideo[] = $row;
	}
//取球童
//applytype=0  球童
//fieldid 球场id
//isverify=1  通过
$qtjl=DB::query("SELECT a.uid,a.username,b.realname FROM pre_home_apply a LEFT JOIN pre_common_member_profile b ON a.uid=b.uid WHERE a.fuid={$uid} AND a.applytype=0 AND a.isverify=1 ORDER BY a.lasttime DESC");
while($qtrow=DB::fetch($qtjl)){
    $qtrow["realname"]=utf8Substr($qtrow["realname"],0,4);
    $qtarr[]=$qtrow;
}




$jl_rows = get_event_experts($_G['gp_uid'],'2');  //获取教练
$zj_rows = get_event_experts($_G['gp_uid'],'1');  //获取专家
$qx_rows = get_event_experts($_G['gp_uid'],'0');  //获取参赛球星



//取球场信息
$infosql=DB::query("SELECT a.uid,a.fieldname,a.standardpar,LENGTH,b.name,a.fieldimg,a.address,a.par,a.cup FROM pre_common_field a LEFT JOIN pre_common_district b ON a.province=b.id WHERE a.uid={$uid} AND b.level=1");
$infoarr=DB::fetch($infosql);
//var_dump($infoarr);


//读取logo
$list=DB::query("select logo from ".DB::table("qiye_logo")." where uid=".$uid);
$listre=DB::fetch($list);
$listpath="static/space/qiye_logo/".$listre["logo"];
//echo $listpath;
if(empty($listre)){
    $listpath="";
}

//我的会员
$hy = DB::query("SELECT a.userid,a.iscomp,a.isuser,b.username,c.realname FROM ".DB::table("guanxi")." a left join ".DB::table("common_member")." b on a.userid=b.uid LEFT JOIN ".DB::table("common_member_profile")." c ON b.uid=c.uid WHERE (a.iscomp=1 or a.isuser=1) and a.compid=".$uid);
while ($hyrow = DB::fetch($hy)){
    if(empty($hyrow["realname"])){
        $hyrow["realname"]=$hyrow["username"];
    }
    $myhy[]=$hyrow;
}
//var_dump($myhy)
//页面中间取球场的信息
/*
$qctype=array();
$qctype[0]="没有选择类型";
$qctype[1]="山地球场";
$qctype[2]="丘陵球场";
$qctype[3]="平原球场";
$qctype[4]="河川球场";
$qctype[5]="森林球场";
$qctype[6]="滨海球场";
$qctype[7]="林克斯球场";
$qctype[8]="地中海球场";
$qinfo=DB::query("SELECT uid,fieldid,fieldname,herb,area,length,fieldherb,fieldclass,standardpar,cup,designer,ADDTIME,email,fax,fieldphone,address FROM pre_common_field WHERE uid=".$uid);
$qre=DB::fetch($qinfo);
if(!empty($qre["ADDTIME"])){
    $qre["ADDTIME"]=date("Y-m-d",$qre["ADDTIME"]);
}
$qre["fieldclass"]=$qctype[$qre["fieldclass"]];
//var_dump($qre);
*/
//左侧广告
$leftad=DB::query("SELECT id,advertname,adverturl,imglogo FROM pre_home_advert WHERE adverttype=5");
while($leftrow=DB::fetch($leftad)){
    $leftre[$leftrow["id"]]=$leftrow;
}
//var_dump($leftre);

//头部推荐的博客
$reblog=DB::query("SELECT a.blogid,b.`subject`,c.`message` FROM `pre_blog_recommend` a LEFT JOIN pre_home_blog b ON a.`blogid`=b.`blogid` LEFT JOIN `pre_home_blogfield` c ON a.`blogid`=c.`blogid` WHERE a.userid={$uid} ORDER BY a.dateline DESC LIMIT 1");
$blogarr=DB::fetch($reblog);
$blogarr["subject"]=utf8Substr($blogarr["subject"],0,18);
$blogarr["message"]=str_replace("&nbsp;","",utf8Substr(strip_tags($blogarr["message"]),0,100))."...";
//如果没有推荐，取一条最新的博客
if(empty($blogarr["subject"])){
    $resql=DB::query("SELECT a.blogid,a.uid,a.subject,b.message FROM pre_home_blog AS a LEFT JOIN pre_home_blogfield b ON a.uid=b.uid WHERE a.uid={$uid} ORDER BY a.dateline DESC LIMIT 1");
    $blogarr=DB::fetch($resql);
    $blogarr["subject"]=utf8Substr($blogarr["subject"],0,22);
    $blogarr["message"]=str_replace("&nbsp;","",utf8Substr(strip_tags($blogarr["message"]),0,100))."...";
}
//读取员工
$yg=DB::query("SELECT a.yid,b.`realname` FROM pre_jigou_yuangong a LEFT JOIN pre_common_member_profile b ON a.`yid`=b.`uid` WHERE a.`jid`=".$uid);
while($ac=DB::fetch($yg)){
    $ygarr[]=$ac;
}

	$array=array();
	$array["1083"]="tj57";    //长沙
	$array["1290"]="bj513";    //广州
	$array["1218"]="gz527";    //杭州
	$array["1204"]="cs611";    //天津
	$array["971"]="qd625";    //北京
	$array["1026"]="hz72";    //深圳
	$array["1889284"]="sh721";    //青岛
	$array["1186"]="dl729";    //上海
	$array["1302"]="sz84";    //大连
	$array["1341"]="cd812";    //成都
	$array["1113"]="km827";    //苏州
	$array["994"]="fz93";    //福州

//参赛明星
function csqyf($limit,$uid){
	global $array;
	if(empty($limit)){
		$limit="";
	}else{
		$limit="limit 6";
	}

	$sql="SELECT uid,realname FROM pre_home_dazbm WHERE  hot_district='".$array[$uid]."' AND pay_status=1 ORDER BY `addtime` DESC ".$limit;
	$csqy=DB::query($sql);
	while($rowa=DB::fetch($csqy)){
		$csqylist[]=$rowa;
	}
	return $csqylist;
}
$csqylist=csqyf("6",$uid);
//是否关注
$row = DB::fetch_first("select id from jishigou_buddys where uid='".$_G['uid']."' and buddyid='$uid' limit 1");
if($row) {
	$guanzhu['buddy'] = '1';
} else {
	$guanzhu['buddy'] = '0';
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