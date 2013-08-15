<?php

header('Content-type: text/html;charset=utf-8');


include('./config.php');

include('./jishigouapi.class.php');


$JishiGouAPI = new JishiGouAPI(JISHIGOU_API_SITE_URL,JISHIGOU_API_APP_KEY,JISHIGOU_API_APP_SECRET,JISHIGOU_API_USERNAME,JISHIGOU_API_PASSWORD);


$act = $_GET['act'];


if('do_add'==$act && $_REQUEST['text'])
{
	$add_result = $JishiGouAPI->AddTopic($_REQUEST['text']);
    
    if($add_result['error'])
    {
        jishigouapi_message($add_result['result']);
    }
    else
    {
        jishigouapi_message('发布成功','index.php?');
    }
}


//$all_topic = $JishiGouAPI->GetAllTopic();
$all_topic = $JishiGouAPI->GetMyTopic();



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
<form method="post" action="indexapi.php?act=do_add">
<input type="text" id="text" name="text" style="width:400px;" />
<input type="submit" value="发布新微博" />
</form>
<h1>2、最新微博列表</h1>
<?php foreach($all_topic['result']['topics'] as $v) { ?>
<div><img src="<?php echo $v['face']; ?>" /><?php echo $v['nickname']; ?> : <?php echo $v['content']; if($v['image_small']) { ?><a href="<?php echo $v['image_original']; ?>"><img src="<?php echo $v['image_small']; ?>" /></a><?php } ?> - <?php echo $v['from_string']; ?></div><hr />
<?php } ?>
