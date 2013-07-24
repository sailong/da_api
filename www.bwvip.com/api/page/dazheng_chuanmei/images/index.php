<?php
define('APPTYPEID', 0);
define('CURSCRIPT', 'member');
require '../../../source/class/class_core.php';
$discuz = & discuz_core::instance();

$discuz->init();
$width=$_G['gp_pic_width'];
if(!$width)
{
	$width=720;
}
$android_url=DB::result_first("select app_version_file from tbl_app_version where app_version_type='android' order by app_version_addtime desc limit 1 ");


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0"/>
<meta name="MobileOptimized" content="236"/>
<meta http-equiv="Cache-Control" content="no-cache"/>
<title>大正传媒</title>
<style type="text/css">
body{margin:0;padding:0;width:320px;}
p{margin:0;padding:0;text-indent:2em;width:80%;line-height:150%; text-align:left;font-size:1.1em;font-family:"微软雅黑";color:#5c5c5c;margin-bottom:20px;}
img{width:100%; margin:0;padding:0;border:0;}
</style>
</head>

<body>



<table width="320px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td >
    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
  <tr><td><img src="images/top.png" width="<?php echo $width;?>" /></td></tr>
  <tr>
    <td><p></p><a href="https://itunes.apple.com/us/app/da-zheng-gao-er-fu-golf/id642016024?ls=1&mt=8" target="_blank"><img src="images/iphone.gif" alt="" width="<?php echo ($width/2)+90;?>"  /></a><p></p></td>
 </tr> <tr>
    <td><p></p><a href="<? echo $android_url;?>" target="_blank"><img src="images/android.gif" width="<?php echo ($width/2)+90;?>" /></a><p></p></td>
  </tr>
    </table>

    </td>
  </tr>
  
  <tr>
    <td><a href="#" target="_blank"><img src="images/pic1.jpg" width="<?php echo $width;?>" /></a></td>
  </tr>
  
  <tr>
    <td>
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><a href="#" target="_blank"><img src="images/text.jpg" /></a></td>
  </tr>
  <tr>
    <td align="center" width="80%"><p>"大正传媒"成立于2006年，是高尔夫行业：以移动新媒体拓展及专业赛事运营为主项的整合营销服务机构。</p></td>
  </tr>
  <tr>
    <td align="center" width="80%"><p>2006年创建之初，即携手中国电信、中国联通所属，全国最大规模语音服务提供商："114查号台"，建立了统一的高尔夫专业语音客服平台。八年来，为约76万个人用户提供高尔夫专业咨询服务。</p></td>
  </tr>
  <tr>
    <td align="center" width="80%"><p>2007年受一汽丰田汽车"皇冠"品牌委托，创建"皇冠杯"业余高尔夫赛事体系，目前已形成每年全国12个赛区预选、加年终总决赛的固定赛制模式，并于2010年取得了中高协（CGA）的认证，列入其全年赛事管理计划，2012年达到了近两万人的赛事报名规模。</p></td>
  </tr>
  <tr>
    <td align="center" width="80%"><p>2009年伴随电信运营商开通了3G数据业务，大正传媒新媒体业务开始逐步完善，同年与电信、联通共同开通了针对商旅移动用户为主要受众的《高尔夫手机报》，2013年每周发送量达3000万条。</p></td>
  </tr>
  <tr>
    <td align="center" width="80%"><p>2010年受中国电信委托组建"天翼高尔夫俱乐部"，截止到2013年初，已举办了百余场各类高尔夫活动。</p></td>
  </tr>
  <tr>
    <td align="center" width="80%"><p>2011年借助智能移动终端的普及，大正传媒开始了移动新媒体领域的拓展，当年开通了以高尔夫爱好者为基础的实名网络社区；2012年开始进行安卓、ios系统移动客户端的研究与开发，2013年5月正式上线；同期，大正传媒还开发出高尔夫球场专属移动客户端服务系统，为广大球友与球场间建立了一个更快、更方便的互动空间。</p></td>
  </tr>
  <tr>
    <td align="center" width="80%" ><p>专注于数据精确是我们一贯的标准，为高尔夫球友提供最及时有效的资讯是我们的责任，为机构客户搭建联动营销、异业合作的平台是我们的目标。</p>
	<p></p>
	<p></p>
	<p></p>
	<p></p>
	<p></p>
	<p></p>
	<p></p>
	</td>
  </tr>
</table>
<p></p>
	<p></p>
	<p></p>
	<p></p>
	<p></p>
	<p></p>
	<p></p>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
	

    </td>
  </tr>
</table>


</body>
</html>
