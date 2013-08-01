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
        $arr['sais_id'] =$_G['gp_sid'];
        $baofen_id =$_G['gp_id'];
        $id =$_G['gp_id'];
		unset($arr['scoreimg']);
		unset($arr['profilesubmitbtn']);

$caves = explode('|',$arr['score'] );
 
		//$row = DB::update('common_score', $arr, array('baofen_id'=>$arr['id'], 'uid'=>$arr['uid'])); 
			$cave_1=$caves[0];
			$cave_2=$caves[1];
			$cave_3=$caves[2];
			$cave_4=$caves[3];
			$cave_5=$caves[4];
			$cave_6=$caves[5];
			$cave_7=$caves[6];
			$cave_8=$caves[7];
			$cave_9=$caves[8];
			
			$cave_10=$caves[10];
			$cave_11=$caves[11];
			$cave_12=$caves[12];
			$cave_13=$caves[13];
			$cave_14=$caves[14];
			$cave_15=$caves[15];
			$cave_16=$caves[16];
			$cave_17=$caves[17];
			$cave_18=$caves[18];
			$uploadimg=$arr['uploadimg'];
            $total_score=$arr['total_score'];
            $score=$arr['score'];
            $pars=$arr['pars'];
            $status=$arr['status'];
            $flag=$arr['flag']; 
            $sid=$arr['sid']; 
            $total_eagle=$arr['total_eagle']; 
            $total_birdie=$arr['total_birdie']; 
            $total_bogi=$arr['total_bogi']; 
            $total_doubles=$arr['total_doubles'];     
 	$sql = "update tbl_baofen set  cave_1=$cave_1,  cave_2=$cave_2,  cave_3=$cave_3,  cave_4=$cave_4,  cave_5=$cave_5,  cave_6=$cave_6,  cave_7=$cave_7,  cave_8=$cave_8,  cave_9=$cave_9,  cave_10=$cave_10,  cave_11=$cave_11,  cave_12=$cave_12,  cave_13=$cave_13,  cave_14=$cave_14,  cave_15=$cave_15,  cave_16=$cave_16,  cave_17=$cave_17,  cave_18=$cave_18,uploadimg='$uploadimg',total_score='$total_score',score='$score',pars='$pars',
	status='$status',flag='$flag',sid='$sid',total_eagle='$total_eagle',total_birdie='$total_birdie',total_bogi='$total_bogi',total_doubles='$total_doubles' where uid=$uid and baofen_id=$baofen_id ";
	$row = DB::query ( $sql );




        /*新成绩卡 生成新的微博 功能 angf Do it 2012/8/16*/
        if(!$result = DB::result_first(" select tid from ultrax.jishigou_topic where uid=".$_G['gp_uid']." and score_id=".$baofen_id))
        {
              $score_info = DB::fetch_first(" select cs.sid,cs.uid,cs.field_id,cs.baofen_id,cs.addtime,cs.dateline,cmp.realname as sai_name ,cmp2.realname as qc_name from tbl_baofen as cs LEFT JOIN ".DB::table("common_member_profile")." as cmp ON cmp.uid = cs.sid LEFT JOIN ".DB::table("common_member_profile")." as cmp2 ON cmp2.uid=cs.field_id where cs.baofen_id=".$baofen_id);

              $weibdata['uid']     = $score_info['uid'];
              $weibdata['field_id']    = $score_info['field_id'];
              $sais_username = DB::result_first(" select username from ".DB::table('common_member')." where uid=".$score_info['sid']);
              $qc_username   = DB::result_first(" select username from ".DB::table('common_member')." where uid=".$score_info['field_id']);
              $username      = DB::result_first(" select username from ".DB::table('common_member')." where uid=".$score_info['uid']);

              $weibdata['content'] ='我在'.date('Y-m-d',$score_info['dateline']).' 参加的 <M '.$qc_username.'>@'.$score_info['qc_name'].'<\/M> <M '.$sais_username.'>@'.$score_info['sai_name'].'<\/M> 比赛 成绩卡已经上传大正微博 <iframe src="/home.php?mod=space&do=common&op=score&uid='.$score_info['uid'].'&id='.$score_info['baofen_id'].'&weibo_tmp=1" width="353"  scrolling="no" frameborder="0" ><\/iframe>';

              DB::query(" insert into ultrax.jishigou_topic (uid,username,fuid,content,score_id,dateline,type) values ('".$weibdata['uid']."','".$username ."','".$weibdata['field_id']."','".$weibdata['content']."','".$score_info['baofen_id']."','".time()."' ,'first')  ");
        }





		if($row) {
			showmessage('操作成功', 'home.php?mod=space&do=common&op=score&uid='.$arr['uid']."&angf=".$is_exist_weib);
		} else {
			showmessage('操作失败', 'home.php?mod=space&do=common&op=score&uid='.$arr['uid'].'&id='.$arr['baofen_id'].'&c=edit');
		}
	} else {
		if($_GET['c'] == 'del') { 
	$uid1=$_GET['uid'];
	$tid=$_GET['id'];
	   DB::query(" delete from  tbl_baofen where uid='$uid1' and  baofen_id='$tid' ");
	   showmessage('操作成功');
		}
		if($id) {

			$child = DB::fetch_first("select uid from ".DB::table('home_apply')." where uid='".$_G['uid']."' and applytype='0'");
			$list = DB::fetch_first("select cs.*, cf.fieldname, cd.name from tbl_baofen as cs left join ".DB::table('common_field')." as cf on cf.uid=cs.field_id left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.baofen_id=$id and cs.uid=$uid");
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
				$groupfield = DB::fetch_first("select baofen_id from tbl_baofen where uid='".$_G['uid']."' and field_id='".$list['field_id']."'");

			}
		} else {

		 

			$mod = $_GET['mod'];
			$do = $_GET['do'];
			$op = $_GET['op'];
			$uid = $_GET['uid'];

			if($game_type=='individual'){
				$theurl = 'home.php?mod=space&do=common&op=score&uid='.$uid.'&gtt=individual';
				//$sql = "SELECT COUNT(*) FROM tbl_baofen WHERE ismine = '1' AND uid = '".$uid."'";
				$sql = "SELECT COUNT(*) FROM tbl_baofen WHERE  uid = '".$uid."'";
				$tmp = DB::query($sql);
				$result = DB::fetch($tmp);
				$record_amount = $result["COUNT(*)"];

				$start = ($page-1)*$pagesize;



				$sql ="SELECT
					cs.baofen_id,
					cs.uid,
					cs.sid,
					cs.tee,
					cs.rtype, 
					cs.realname,
					cs.province,
					cs.field_id,
					cs.par,
					cs.score,
					cs.pars,
					cs.total_score,   
					cs.total_eagle,
					cs.total_birdie, 
					cs.total_bogi,
					cs.total_doubles, 
					cs.total_evenpar, 
					cs.dateline, 
					cs.content,
					cs.uploadimg,
					cs.status,
					cs.flag,
					cs.group_id,
					cs.addtime,
					cf.fieldname,
					cd.name
					FROM tbl_baofen AS cs
					LEFT JOIN ".DB::table('common_field')." AS cf ON cf.uid = cs.field_id
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
				$sql = "SELECT COUNT(*) FROM tbl_baofen WHERE status = '2'  AND uid = '".$uid."'";
				$tmp = DB::query($sql);
				$result = DB::fetch($tmp);
				$record_amount = $result["COUNT(*)"];

				$start = ($page-1)*$pagesize;

				$sql ="SELECT
					cs.baofen_id,
					cs.uid,
					cs.sid,
					cs.tee,
					cs.rtype,
					cs.realname,
					cs.province,
					cs.field_id,
					cs.par,
					cs.score,
					cs.pars,
					cs.total_score,  
					cs.total_eagle,
					cs.total_birdie, 
					cs.total_bogi,
					cs.total_doubles, 
					cs.total_evenpar, 
					cs.dateline, 
					cs.content,
					cs.uploadimg,
					cs.status,
					cs.flag,
					cs.group_id,
					cs.addtime,
					cf.fieldname,
					cd.name
					FROM tbl_baofen AS cs
					LEFT JOIN ".DB::table('common_field')." AS cf ON cf.uid = cs.field_id
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
