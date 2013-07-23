<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: space_doing.php 19158 2010-12-20 08:21:50Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

space_merge($space, 'count');
$act = $_GET['act'];
$operation = $_GET['op'];



if($act=='iphone4s') {
	if($operation == 'subscribe') {
		$compid = $_POST['compid'];
		$uid = $_G['uid'];    //当前用户id
		//$username=$_POST['username'];
		$mobile = $_POST['mobile'];    //手机号
		$license = $_POST['license'];    //驾照编号
		$chejiahao = $_POST['chejiahao'];    //车驾号
		$chexing = $_POST["chexing"];     //车型
		$chepai = $_POST["chepai"];   //车牌号
		$fadongji = $_POST["fadongji"];   //发动机
		$telcolor = $_POST["telcolor"];    //手机颜色
		$keyongkj = $_POST["kongjian"];     //可用空间
		$qixian = $_POST["qixian"];      //合约期限
		$plan = $_POST["plan"];      //合约计划
		$zjtype = $_POST["zjtype"];    //证件类型
		$zhengjian = $_POST["zhengjian"];   //证件号码
		$address = $_POST["address"];   //地址
		$youbian = $_POST["youbian"];   //邮编
		$addtime = time();
		$price = "5780";
		$fctbox = $_POST['fctbox'];
		$brbox = $_POST['brbox'];
		$spbox = $_POST['spbox'];

		if($spbox > 0) {
			$autoid = $spbox;
		} else {
			if($brbox > 0) {
				$autoid = $brbox;
			} else {
				if($fctbox > 0) {
					$autoid = $fctbox;
				} else {
					$autoid = '0';
				}
			}
		}

		if($_POST["sub"]) {
			if($autoid == '0') {
				showmessage('请填写车型', 'home.php?mod=space&uid='.$compid.'&do=action&act=iphone4s');
			} else {
				//先查找有没有这个用户的数据
				$ishave = DB::fetch_first("select uid from zdy_getauto where uid=".$_G["uid"]);
				if($ishave) {
					showmessage('已经预定过了', 'home.php?mod=space&uid='.$compid.'&do=action&act=iphone4s');
				} else {
					//插入数据表zdy_getauto 
					$insert = "insert into zdy_getauto (uid, mobile, autoid, chejiahao, autoplate, license, telcolor, address, youbian, zjtype, zhengjian, fadongji, autoname, addtime, price, keyongkj, plan, qixian) values ('$uid', '$mobile', '$autoid', '$chejiahao', '$chepai', '$license', '$telcolor', '$address', '$youbian', '$zjtype', '$zhengjian', '$fadongji', '$chexing', '$addtime', '$price', '$keyongkj', '$plan', '$qixian')";
					$row = DB::query($insert);
					if($row) {
						showmessage('预订成功', 'home.php?mod=space&uid='.$compid.'&do=action&act=iphone4s');
					} else {
						showmessage('预订失败', 'home.php?mod=space&uid='.$compid.'&do=action&act=iphone4s');
					}
				}
			}
		}
	} else {
		$templates='home/20_iphone4s';
	}
}

if($act=='qpg'){
    //青苹果预定页面
    $ry=DB::query("SELECT tel FROM pre_lianghao WHERE flag=0 limit 12");
    while($relist=DB::fetch($ry)){
        $arr[]=$relist;
    }
    $countnum=count($arr);
    //var_dump($arr);
    $templates='home/20_qpg_iphone4s';
}
if($act=='qpgtc'){
    //青苹果套餐页面
    $templates='home/20_4s_taocan';
}
if($act=='qpgld'){
    //青苹果比较页面
    $templates='home/20_4s_bijiao';
}
if($act=='qpgindex'){
    //青苹果首页页面
    $templates='home/20_4s_index';
}
if($act=='weibo'){
$wburl=$_GET['wburl'];
$wburl=base64_decode($wburl);
$templates='home/'.$gropid.'_weibo';
}


$act=$_POST['act'];
if(!$act) {
include_once(template($templates));
}

?>