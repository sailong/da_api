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

/*
$userAgent = $_SERVER['HTTP_USER_AGENT'];
if(strpos($userAgent,"iPhone") || strpos($userAgent,"iPad") || strpos($userAgent,"iPod") || strpos($userAgent,"iOS"))
{
	//iPhone
	//echo "<script>location='https://itunes.apple.com/us/app/da-zheng-gao-er-fu-golf/id642016024?ls=1&mt=8';</script>";
	//echo "http://www.bwvip.com/nd/fenzhan.php";
	//echo "iphone客户端暂不能下载，请使用彩信二维码签到";
}

else if(strpos($userAgent,"Android"))
{
	//Android
	echo "<h1>Android访问</h1>";
}

else
{
	$android_url=DB::result_first("select app_version_file from tbl_app_version where app_version_type='android' order by app_version_addtime desc limit 1 ");
	//echo $version;
	//echo "<script>location='".$version."';</script>";
}

*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>大正传媒</title>
<style type="text/css">
body{margin:0;padding:0;width:100%;}
p{margin:0;padding:0;text-indent:2em;width:80%;line-height:150%; text-align:left;font-size:1.1em;font-family:"微软雅黑";color:#5c5c5c;margin-bottom:20px;}
img { max-width: 100%; margin:0;padding:0;border:0;}
</style>
</head>

<body>



<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td >
    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
  <tr align="center"><td><img src="images/top.png" width="<?php echo $width;?>" /></td></tr>
  <tr>
    <td align="center" ><p></p><a href="https://itunes.apple.com/us/app/da-zheng-gao-er-fu-golf/id642016024?ls=1&mt=8" target="_blank"><img src="images/iphone.gif" alt="" width="<?php echo ($width/2)+90;?>"  /></a><p></p></td>
 </tr> <tr>
    <td align="center"><p></p><a href="<? echo $android_url;?>" target="_blank"><img src="images/android.gif" width="<?php echo ($width/2)+90;?>" /></a><p></p></td>
  </tr>
    </table>

    </td>
  </tr>
  
  <tr>
    <td align="center"><a href="#" target="_blank"><img src="images/pic1.jpg" width="<?php echo $width;?>" /></a></td>
  </tr>
  
  <tr>
    <td align="center">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><a href="#" target="_blank"><img src="images/text.jpg" /></a></td>
  </tr>
  <tr>
    <td align="center" width="80%"><p>北京大正承平文化传播有限公司系&quot;大正传媒&quot;品牌持有者和国内专业的高尔夫整合营销服务机构。</p></td>
  </tr>
  <tr>
    <td align="center" width="80%"><p>公司专注服务于集团企业客户，为企业提供高尔夫产品定制、赛事活动、俱乐部服务和品牌营销策划；同时公司整合众多行业和媒体优势资源，结合自身的高端数据库资源，为企业提供个性化、差异化的精准营销、联动营销、赛事推广和媒体传播服务。</p></td>
  </tr>
  <tr>
    <td align="center" width="90%"><p>经过多年的业务发展和服务创新，大正网不但积累了75万会员和众多高端企业客户资源，并逐步形成了"媒体传播"、"赛事运营"和"俱乐部服务"三位一体的专业服务平台，为客户提供全方位、多样化、持续性的高尔夫整合营销服务。凭借其丰富的行业资源和专业服务，大正高尔夫已成为联通和电信114高尔夫服务独家合作伙伴。并与中国电信、中国联通联合打造国内首份面向公众用户的《高尔夫手机报》，目前已近1600万订阅量。</p></td>
  </tr>
  <tr>
    <td align="center" width="80%"><p>2011年，公司全力打造的实名纯净的高尔夫主题社区——大正社区正式上线，提供微博、视频、教学、活动、球队、竞猜、社交等一站式综合服务。2012年大正网全新改版，大正社区全面升级，着力打造。多样的互动方式、便捷的参与形式、丰富的线上活动，惊喜的互动奖品。都市精英的高尔夫生活圈，一个真正的高尔夫主题社区。</p>
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

    </td>
  </tr>
</table>


</body>
</html>
