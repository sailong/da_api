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
include('./sql_v2.class.php');

$ac = $_GET['ac'];
if($ac=="")
{
	echo " ac 参数为空，请修改 ";
}

$JishiGouAPI = new JishiGouAPI(JISHIGOU_API_SITE_URL,JISHIGOU_API_APP_KEY,JISHIGOU_API_APP_SECRET,JISHIGOU_API_USERNAME,JISHIGOU_API_PASSWORD);
//$JishiGouAPI->Test();exit;


if($ac=="topic_detail")
{
	$tid=$_GET['tid'];
	$data = $JishiGouAPI->GetTopicById($tid);
	echo $tid;
}

if($ac=="get_all_topic")
{
	$all_topic = $JishiGouAPI->GetAllTopic();
	if($all_topic['error']) {
		jishigouapi_message($all_topic['result']);
	}
	print_r($all_topic);
}



//添加微博
if($ac=="add_topic")
{

	$pic = null;
	if($_FILES['pic'])
	{
		$pic = $_FILES['pic'];
		$pic['data'] = file_get_contents($pic['tmp_name']);
	}

	echo "sldjflsdkfjsdlfkj";

	print_r($pic);

	$add_result = $JishiGouAPI->AddTopic($_REQUEST['text'],$_REQUEST['totid'],$_REQUEST['type'],$_REQUEST['pic_url'],$pic);

	print_r($add_result);

    if($add_result['error'])
    {
        api_json_result(1,1,"发布失败",$data);
    }
    else
    {
		$new_tid=$add_result['result']['tid'];
		//更新 update
		if ($_FILES["voice"]["error"]<=0)
		{
			$save_path="../upload/voice/";
			$full_save_path=$save_path.date("Ymd",time())."/";
			if(!file_exists($save_path))
			{
				mkdir($save_path);
			}
			if(!file_exists($full_save_path))
			{
				mkdir($full_save_path);
			}

			move_uploaded_file($_FILES["voice"]["tmp_name"], $full_save_path . time().$_FILES["voice"]["name"]);//将上传的文件存储到服务器
			$file_path=$full_save_path. time().$_FILES["voice"]["name"];
			$voice_timelong=$_POST['voice_timelong'];
			$sql = "update `jishigou_topic` set `voice`='{$file_path}',`voice_timelong`='{$voice_timelong}' where `tid`='{$new_tid}'";
			$res=$dsql->ExecuteNoneQuery($sql);
		}
		//print_r($add_result);
        api_json_result(1,0,"发布成功",$data);
    }

	
	
}



function api_json_result($response=0,$error='',$message='',$data=''){
    $result = array(
                  'response'      => $response,
                  'error'         => $error,
                  'message'       => $message,
                  $data['title']  => $data['data']
                );
    exit(json_encode($result));
}


?>

