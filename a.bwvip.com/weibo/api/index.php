<?php
/**
 * jishigou api 示例
 * 
 * @author 狐狸<foxis@qq.com>
 * @version $Id: index.php 926 2012-05-15 01:41:04Z wuliyong $
 */

error_reporting(E_ALL ^ E_NOTICE);

header('Content-type: text/html;charset=utf-8');

include('./config.php');
include('./jishigouapi.class.php');

$act = $_GET['act'];

//echo JISHIGOU_API_PASSWORD;

$JishiGouAPI = new JishiGouAPI(JISHIGOU_API_SITE_URL,JISHIGOU_API_APP_KEY,JISHIGOU_API_APP_SECRET,JISHIGOU_API_USERNAME,JISHIGOU_API_PASSWORD);

//$JishiGouAPI->Test();exit;

if('do_add'==$act && $_REQUEST['text'])
{
	$pic = null;
	if($_FILES['pic']) {
		$pic = $_FILES['pic'];
		$pic['data'] = file_get_contents($pic['tmp_name']);
	}
	//print_r($_REQUEST);
	print_r($_FILES);
	echo "<hr>";
	print_r($pic);
	echo "<hr>";
	

	$add_result = $JishiGouAPI->AddTopic($_REQUEST['text'], 0, 'first', $_REQUEST['pic_url'], $pic);
	//print_r($add_result);exit;
    if($add_result['error'])
    {
        jishigouapi_message($add_result['result']);
    }
    else
    {
        jishigouapi_message('发布成功','index.php?');
    }

	
}


$all_topic = $JishiGouAPI->GetAllTopic();
//$all_topic = $JishiGouAPI->GetMyTopic();
if($all_topic['error']) {
	jishigouapi_message($all_topic['result']);
}

/*
$all_follows = $JishiGouAPI->GetMyFollow();
print_r($all_follows);exit;
//*/

/*
$my_pm = $JishiGouAPI->GetMyPm();
print_r($my_pm);exit;
//*/

function jishigouapi_message($message='',$url_forward='',$stop_time=3) 
{
	if(!$message) 
    {
		@header("Location: {$url_forward}");
	} 
    else 
    {		
		if($url_forward) 
        {
			$message .= "<br /><br /><br /><a href=\"$url_forward\">如果您的浏览器没有自动跳转，请点击这里</a><meta http-equiv=\"refresh\" content=\"{$stop_time}; URL=$url_forward\">";
		}
		echo "<br /><br /><br /><br /><br /><br />
		<table width=\"600\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\" class=\"tableborder\">
		<tr class=\"header\"><td>消息提示</td></tr><tr><td class=\"altbg2\"><br /><div align=\"center\">
			{$message}</div><br /><br />
		</td></tr></table>
		<br /><br /><br />";
	}
	exit ;
}


?>
<h1>1、发布新微博</h1>
<form method="post" action="index.php?act=do_add" enctype="multipart/form-data">
微博内容：<textarea id="text" name="text" style="width:400px; height:100px;" /></textarea><br />
远程图片：<input type="text" id="pic_url" name="pic_url" style="width:400px" /><br />
本地图片：<input type="file" id="pic" name="pic" /><br />
<input type="submit" value="发布新微博" />
</form>

<h1>2、发布新微博2</h1>
<form method="post" action="api_m.php?ac=add_topic" enctype="multipart/form-data">
微博内容：<textarea id="text" name="text" style="width:400px; height:100px;" /></textarea><br />

本地图片：<input type="file" id="pic" name="pic" /><br />

本地声音：<input type="file" id="voice" name="voice" /><br />
发布人UID：<input type="text" id="uid" name="uid" style="width:400px" /><br />
totid：<input type="text" id="totid" name="totid" style="width:400px" /><br />
类型：<select id="type" name="type"><option value="first">直接</option><option value="reply">评论</option><option value="forward">转发</option></select><br />
<input type="submit" value="发布新微博" />
</form>
<h1>3、发布新微博3--添加多张图片</h1>
<form method="post" action="api_m.php?ac=add_topic_v2" enctype="multipart/form-data">
微博内容：<textarea id="text" name="text" style="width:400px; height:100px;" /></textarea><br />

本地图片：<input type="file" id="pic" name="pic1" /><br />
本地图片：<input type="file" id="pic" name="pic2" /><br />
本地图片：<input type="file" id="pic" name="pic3" /><br />

本地声音：<input type="file" id="voice" name="voice" /><br />
发布人UID：<input type="text" id="uid" name="uid" style="width:400px" /><br />
totid：<input type="text" id="totid" name="totid" style="width:400px" /><br />
类型：<select id="type" name="type"><option value="first">直接</option><option value="reply">评论</option><option value="forward">转发</option></select><br />
<input type="submit" value="发布新微博" />
</form>
上传图片
<form method="post" action="api_m.php?ac=upload_pic" enctype="multipart/form-data">

本地图片：<input type="file" id="pic" name="pic" /><br />
发布人uid：<input type="text" id="uid" name="uid" style="width:400px" /><br />
tid：<input type="text" id="tid" name="tid" style="width:400px" /><br />
发布人username：<input type="text" id="username" name="username" style="width:400px" /><br />
<input type="submit" value="上传图片" />
</form>


<h1>2、最新微博列表</h1>
<?php if(is_array($all_topic) && count($all_topic)) { foreach($all_topic['result']['topics'] as $v) { ?>
<div><img src="<?php echo $v['face']; ?>" /><?php echo $v['nickname']; ?> : <?php echo $v['content']; if($v['image_list']) { foreach($v['image_list'] as $iv) { ?><a href="<?php echo str_replace("t2","weibo", $iv['image_original']) ?>"><img src="<?php echo str_replace("t2","weibo", $iv['image_small']); ?>" /></a><?php } ?> - <?php echo $v['from_string']; ?></div><hr />
<?php } } } else { echo '暂时还没有数据，请先发一条新微博吧！'; } ?>
