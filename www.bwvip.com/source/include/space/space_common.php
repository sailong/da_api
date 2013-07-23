<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: space_wall.php 16680 2010-09-13 03:01:08Z wangjinbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$option = $_GET['op'];

$uid = $_GET['uid'];
$id = $_GET['id'];


if(isset($_GET['gt'])){
	$game_type = $_GET['gt'];
}else{
	$game_type = 'individual';#'elite'
}

$pagesize = ($option == 'score') ? '4' : '20';
$pagesize = mob_perpage($pagesize);
$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
if($page < 1) {
	$page = 1;
}
$start = ($page-1)*$pagesize;
ckstart($start, $pagesize);

//球洞
for($i = 1; $i <= 21; $i++) {
	if($i == '10') {
		$data[$i] = 'OUT';
	} elseif($i == '20') {
		$data[$i] = 'IN';
	} elseif($i == '21') {
		$data[$i] = 'Total';
	} elseif($i > 9) {
		$data[$i] = $i - 1;
	} else {
		$data[$i] = $i;
	}
}

//在添加状态下循环的输入框数
for($i = 1; $i <= 21; $i++) {
	$num[] = $i;
}

if($option == 'score') {
	$array = array('par', 'score', 'pars');

	if($_GET['c'] == 'save') {
		$arr = $_POST;
		$arr['total_score'] = $arr['score']['20'];
		$arr['status'] = '2';
		$arr['flag'] = $_G['uid'];
		foreach($arr as $key=>$val) {
			if(in_array($key, $array)) {
				$arr[$key] = implode('|', $val);
			}
		}
		if($_FILES['uploadimg']['tmp_name']) {
			$tmp = $_FILES['uploadimg']['tmp_name'];
			$file = uploadimg();
			move_uploaded_file($tmp, $file);
			$arr['uploadimg'] = $file;
		} else {
			$arr['uploadimg'] = $_POST['scoreimg'];
		}
        $arr['sais_id'] =$_G['gp_sais_id'];
		unset($arr['scoreimg']);
		unset($arr['profilesubmitbtn']);

		$row = DB::update('common_score', $arr, array('id'=>$arr['id'], 'uid'=>$arr['uid']));






        /*新成绩卡 生成新的微博 功能 angf Do it 2012/8/16*/
        if(!$result = DB::result_first(" select tid from ultrax.jishigou_topic where uid=".$_G['gp_uid']." and score_id=".$_G['gp_id']))
        {
              $score_info = DB::fetch_first(" select cs.sais_id,cs.uid,cs.fuid,cs.id,cs.addtime,cmp.realname as sai_name ,cmp2.realname as qc_name from ".DB::table("common_score")." as cs LEFT JOIN ".DB::table("common_member_profile")." as cmp ON cmp.uid = cs.sais_id LEFT JOIN ".DB::table("common_member_profile")." as cmp2 ON cmp2.uid=cs.fuid where cs.id=".$_G['gp_id']);

              $weibdata['uid']     = $score_info['uid'];
              $weibdata['fuid']    = $score_info['fuid'];
              $sais_username = DB::result_first(" select username from ".DB::table('common_member')." where uid=".$score_info['sais_id']);
              $qc_username   = DB::result_first(" select username from ".DB::table('common_member')." where uid=".$score_info['fuid']);
              $username      = DB::result_first(" select username from ".DB::table('common_member')." where uid=".$score_info['uid']);

              $weibdata['content'] ='我在'.date('Y-m-d',$score_info['addtime']).' 参加的 <M '.$qc_username.'>@'.$score_info['qc_name'].'<\/M> <M '.$sais_username.'>@'.$score_info['sai_name'].'<\/M> 比赛 成绩卡已经上传大正微博 <iframe src="/home.php?mod=space&do=common&op=score&uid='.$score_info['uid'].'&id='.$score_info['id'].'&weibo_tmp=1" width="353"  scrolling="no" frameborder="0" ><\/iframe>';

              DB::query(" insert into ultrax.jishigou_topic (uid,username,fuid,content,score_id,dateline,type) values ('".$weibdata['uid']."','".$username ."','".$weibdata['fuid']."','".$weibdata['content']."','".$score_info['id']."','".time()."' ,'first')  ");
        }





		if($row) {
			showmessage('操作成功', 'home.php?mod=space&do=common&op=score&uid='.$arr['uid']."&angf=".$is_exist_weib);
		} else {
			showmessage('操作失败', 'home.php?mod=space&do=common&op=score&uid='.$arr['uid'].'&id='.$arr['id'].'&c=edit');
		}
	} else {
		if($_GET['c'] == 'del') { 
	$uid1=$_GET['uid'];
	$tid=$_GET['id'];
	   DB::query(" delete from  ".DB::table('common_score')."  where uid='$uid1' and  id='$tid' ");
	   showmessage('操作成功');
		}
		if($id) {

			$child = DB::fetch_first("select uid from ".DB::table('home_apply')." where uid='".$_G['uid']."' and applytype='0'");
			$list = DB::fetch_first("select cs.*, cf.fieldname, cd.name from ".DB::table('common_score')." as cs left join ".DB::table('common_field')." as cf on cf.uid=cs.fuid left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.id=$id and cs.uid=$uid");
			if(!empty($list)) {
				$list['dateline'] = date('Y-m-d H:i:s', $list['dateline']);
				foreach($list as $key=>$val) {
					if(in_array($key, $array) && !empty($val)) {
						$list[$key] = array();
						$list[$key] = explode('|', $val);
					}
				}
				$flag = ($_G['uid'] == 1) ? 0 : $list['flag'];
				$childuid = ($_G['uid'] == 1) ? 1 : $child['uid'];
				$groupfield = DB::fetch_first("select id from ".DB::table('common_score')." where uid='".$_G['uid']."' and fuid='".$list['fuid']."'");

			}
		} else {

			/*
			$theurl = 'home626.php?mod=space&do=common&op=score&uid='.$uid;

			#echo 'select count(*) from '.DB::table('common_score')." as cs left join ".DB::table('common_field')." as cf on cf.uid=cs.fuid left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.uid='$uid' and (cs.status='2' or cs.ismine='1')";

			$count = DB::result(DB::query('select count(*) from '.DB::table('common_score')." as cs left join ".DB::table('common_field')." as cf on cf.uid=cs.fuid left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.uid='$uid' and (cs.status='2' or cs.ismine='1')"));
			if($count) {
				#echo 'select cs.*, cf.fieldname, cd.name from '.DB::table('common_score')." as cs left join ".DB::table('common_field')." as cf on cf.uid=cs.fuid left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.uid='$uid' and (cs.status='2' or cs.ismine='1') order by cs.dateline desc limit $start, $pagesize";
				$query = DB::query('select cs.*, cf.fieldname, cd.name from '.DB::table('common_score')." as cs left join ".DB::table('common_field')." as cf on cf.uid=cs.fuid left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.uid='$uid' and (cs.status='2' or cs.ismine='1') order by cs.dateline desc limit $start, $pagesize");
				while($row = mysql_fetch_assoc($query)) {
					$row['dateline'] = date('Y-m-d H:i:s', $row['dateline']);
					$scorelist[] = $row;
				}
			}

			foreach($scorelist as $key=>$val) {
				foreach($val as $k=>$v) {
					if(!empty($val[$k])) {
						if(in_array($k, $array)) {
							$scorelist[$key][$k] = explode('|', $v);
						}
					}
				}
			}
			$multi = multi($count, $pagesize, $page, $theurl);
			*/

			#$pagesize = 1;

			$mod = $_GET['mod'];
			$do = $_GET['do'];
			$op = $_GET['op'];
			$uid = $_GET['uid'];

			if($game_type=='individual'){
				$theurl = 'home.php?mod=space&do=common&op=score&uid='.$uid.'&gtt=individual';
				//$sql = "SELECT COUNT(*) FROM ".DB::table('common_score')." WHERE ismine = '1' AND uid = '".$uid."'";
				$sql = "SELECT COUNT(*) FROM ".DB::table('common_score')." WHERE  uid = '".$uid."'";
				$tmp = DB::query($sql);
				$result = DB::fetch($tmp);
				$record_amount = $result["COUNT(*)"];

				$start = ($page-1)*$pagesize;



				$sql ="SELECT
					cs.id,
					cs.uid,
					cs.sais_id,
					cs.tee,
					cs.rtype,
					cs.name,
					cs.province,
					cs.fuid,
					cs.par,
					cs.score,
					cs.pars,
					cs.total_score,
					cs.total_avglength,
					cs.total_shangdao,
					cs.total_aveshangdao,
					cs.total_pushs,
					cs.total_avepushs,
					cs.total_eagle,
					cs.total_birdie,
					cs.total_furthest,
					cs.total_bogi,
					cs.total_doubles,
					cs.total_penalty,
					cs.total_evenpar,
					cs.total_other,
					cs.dateline,
					cs.member,
					cs.content,
					cs.uploadimg,
					cs.status,
					cs.flag,
					cs.group,
					cs.ismine,
					cs.addtime,
					cf.fieldname,
					cd.name
					FROM ".DB::table('common_score')." AS cs
					LEFT JOIN ".DB::table('common_field')." AS cf ON cf.uid = cs.fuid
					LEFT JOIN ".DB::table('common_district')." AS cd ON cd.id = cs.province
					WHERE    cs.uid = '".$uid."'
					ORDER BY cs.dateline DESC
					LIMIT ".$start.",".$pagesize."
					";


				$tmp = DB::query($sql);
				$scorelist = array();
				while($r = DB::fetch($tmp)) {
					$r['dateline'] = date('Y-m-d H:i:s', $r['dateline']);
					$scorelist[] = $r;
				}

				foreach($scorelist as $key=>$val) {
					foreach($val as $k=>$v) {
						if(!empty($val[$k])) {
							if(in_array($k, $array)) {
								$scorelist[$key][$k] = explode('|', $v);
							}
						}
					}
				}

				$multi = multi($record_amount, $pagesize, $page, $theurl);
				//$list = $scorelist;
				//var_dump($scorelist);
			}

			if($game_type=='elite'){

				$theurl = 'home.php?mod=space&do=common&op=score&uid='.$uid.'&gt=elite';
				$sql = "SELECT COUNT(*) FROM ".DB::table('common_score')." WHERE status = '2' AND ismine = '0' AND uid = '".$uid."'";
				$tmp = DB::query($sql);
				$result = DB::fetch($tmp);
				$record_amount = $result["COUNT(*)"];

				$start = ($page-1)*$pagesize;

				$sql ="SELECT
					cs.id,
					cs.uid,
					cs.sais_id,
					cs.tee,
					cs.rtype,
					cs.name,
					cs.province,
					cs.fuid,
					cs.par,
					cs.score,
					cs.pars,
					cs.total_score,
					cs.total_avglength,
					cs.total_shangdao,
					cs.total_aveshangdao,
					cs.total_pushs,
					cs.total_avepushs,
					cs.total_eagle,
					cs.total_birdie,
					cs.total_furthest,
					cs.total_bogi,
					cs.total_doubles,
					cs.total_penalty,
					cs.total_evenpar,
					cs.total_other,
					cs.dateline,
					cs.member,
					cs.content,
					cs.uploadimg,
					cs.status,
					cs.flag,
					cs.group,
					cs.ismine,
					cs.addtime,
					cf.fieldname,
					cd.name
					FROM ".DB::table('common_score')." AS cs
					LEFT JOIN ".DB::table('common_field')." AS cf ON cf.uid = cs.fuid
					LEFT JOIN ".DB::table('common_district')." AS cd ON cd.id = cs.province
					WHERE status = '2' AND ismine = '0' AND cs.uid = '".$uid."'
					ORDER BY cs.dateline DESC
					LIMIT ".$start.",".$pagesize."
					";

				$tmp = DB::query($sql);
				$scorelist = array();
				while($r = DB::fetch($tmp)) {
					$r['dateline'] = date('Y-m-d H:i:s', $r['dateline']);
					$scorelist[] = $r;
				}

				foreach($scorelist as $key=>$val) {
					foreach($val as $k=>$v) {
						if(!empty($val[$k])) {
							if(in_array($k, $array)) {
								$scorelist[$key][$k] = explode('|', $v);
							}
						}
					}
				}

				$multi = multi($record_amount, $pagesize, $page, $theurl);

			}

		}
	}
} elseif($option == 'visiter') {
	$theurl = 'home.php?mod=space&do=common&op=visiter&uid='.$uid;
	$count = DB::result(DB::query("select count(uid) from ".DB::table('home_visitor')." where uid='$uid' order by dateline desc"));
	if($count) {
		$query = DB::query("select hv.uid, hv.vuid, hv.vusername,cmp.realname from ".DB::table('home_visitor')." as hv LEFT JOIN ".DB::table('common_member_profile')." as cmp ON cmp.uid=hv.vuid  where hv.uid='$uid' order by dateline desc limit $start, $pagesize");
		while($row = DB::fetch($query)) {
			$visitermore[] = $row;
		}
	}
	$multi = multi($count, $pagesize, $page, $theurl);
} elseif($option == 'medalmore') {
	$medals = DB::fetch_first("select medals from ".DB::table('common_member_field_forum')." where uid='$uid' limit 1");
	$arr = explode('	', $medals['medals']);
	foreach($arr as $val) {
		$query = DB::query("select image, name from ".DB::table('forum_medal')." where medalid='$val' limit 1");
		while($row = mysql_fetch_assoc($query)) {
			$medalmore[] = $row;
		}
	}
}
//标签
if($option == 'tag') {
	$tag = $_GET['tag'];
	if($tag) {
		$url = 'weibo/index.php?mod=search&code=usertag&usertag='.$tag.'&fuid='.$uid;
	} else {
		$url = 'weibo/index.php?mod=user_tag&uid='.$uid.'&fuid='.$uid;
	}
}
//群
if($option == 'qun') {
	$qid = $_GET['qid'];
	if($qid) {
		$url = 'weibo/index.php?mod=qun&qid='.$qid.'&fuid='.$uid;
	} else {
		//$url = 'weibo/index.php?mod=qun';
		$url = 'weibo/index.php?mod=qun&code=profile'.'&fuid='.$uid;
	}
}
//活动
if($option == 'event') {
	$eid = $_GET['eid'];
	if($eid) {
		$url = 'weibo/index.php?mod=event&code=detail&id='.$eid.'&fuid='.$uid;
	} else {
		$url = 'weibo/index.php?mod=event&code=myevent&type=part&uid='.$uid.'&fuid='.$uid;
	}
}
//话题
if($option == 'topic') {
	//$tcode =diconv($_G['gp_tcode'],"gb2312","UTF-8");
	//$code = diconv($_GET['code'],"gb2312","UTF-8");
	$tcode =$_G['gp_tcode'];
	$code =$_G['code'];
   if(is_gb2312($tcode))
   {$tcode =diconv($_G['gp_tcode'],"gb2312","UTF-8");}
   if(is_gb2312($code))
   {$code =diconv($_G['code'],"gb2312","UTF-8");}


	if($tcode) {
		$url = 'weibo/index.php?mod=tag&code='.$tcode.'&fuid='.$uid;
	} else {
		if($code) {
		$url = 'weibo/index.php?mod=topic&code='.$code.'&fuid='.$uid;
	    }else {
		$url = 'weibo/index.php?mod='.$userinfo['username'].'&fuid='.$uid;
		}
	}
}
//@我
if($option == 'refer') {
	$url = 'weibo/index.php?mod=topic&code=myat'.'&fuid='.$uid;
}
//关注
if($option == 'follow') {
	$url = 'weibo/index.php?mod='.$userinfo['username'].'&code=follow'.'&fuid='.$uid;
}
//粉丝
if($option == 'fans') {
	$url = 'weibo/index.php?mod='.$userinfo['username'].'&code=fans'.'&fuid='.$uid;
}
//微博
if($option == 'weibo') {
	//$url = 'weibo/index.php?mod='.$userinfo['username'];
    $url = 'weibo/index.php?mod='.$userinfo['username'].'&fuid='.$uid.'&fuid='.$uid;
}
space_merge($space, 'count');

function uploadimg() {
	$dir = 'uploadfile/score/'.date('Ym');
	if(!is_dir($dir)) {
		mkdir($dir, 0777);
	}
	chmod($dir, 0777);
	$str = 'abcdefghijklmnopqrstuvwxyz0123456789';
	for($i = 1; $i <= 20; $i++) {
		$max = strlen($str);
		$start = rand(0, $max);
		$rand .= substr($str, $start, '1');
	}
	$file = $dir.'/'.$rand.'.jpg';
	return $file;
}

$usergroup = !empty($getstat['groupid']) ? $getstat['groupid'] : $_G['groupid'];
$usergroup = ($gropid < 20) ? 10 : $usergroup;
$templates = 'home/'.$usergroup.'_common';
if($_G['gp_weibo_tmp']){
    $templates =  'home/web_score_tmp';
}


include_once(template($templates));#/home/www/dzbwvip/./data/template/1_1_home_10_common.tpl.php
//echo '/home/www/dzbwvip/data/template/1_1_home_10_common.tpl626.php';
#include_once('/home/www/dzbwvip/data/template/1_1_home_10_common.tpl626.php');
//判断是否为utf8
function is_utf8($string) {

return preg_match('%^(?:
[x09x0Ax0Dx20-x7E] # ASCII
| [xC2-xDF][x80-xBF] # non-overlong 2-byte
| xE0[xA0-xBF][x80-xBF] # excluding overlongs
| [xE1-xECxEExEF][x80-xBF]{2} # straight 3-byte
| xED[x80-x9F][x80-xBF] # excluding surrogates
| xF0[x90-xBF][x80-xBF]{2} # planes 1-3
| [xF1-xF3][x80-xBF]{3} # planes 4-15
| xF4[x80-x8F][x80-xBF]{2} # plane 16
)*$%xs', $string);

}

// 经常遇到这种情况，需要对URL中的字符串进行解码，例如Google中搜索"编码"，"编码"会转换为

// 综合了网上搜集的资料和GB/UTF-8编码方法，判断一个中英文混杂的字符串是用GB2312/GBK编码还是UTF-8编码
// 返回: true - 含GB编码 false - 为UTF-8编码

function is_gb2312($str)
{
        for($i=0; $i<strlen($str); $i++) {
                $v = ord( $str[$i] );
                if( $v > 127) {
                        if( ($v >= 228) && ($v <= 233) )
                        {
                                if( ($i+2) >= (strlen($str) - 1)) return true;  // not enough characters
                                $v1 = ord( $str[$i+1] );
                                $v2 = ord( $str[$i+2] );
                                if( ($v1 >= 128) && ($v1 <=191) && ($v2 >=128) && ($v2 <= 191) ) // utf编码
                                        return false;
                                else
                                        return true;
                        }
                }
        }
        return true;
}

?>
