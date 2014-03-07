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
include('./file.class.php');

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
	
	//print_r($_POST);

	$pic = null;
	if($_FILES['pic'])
	{
		$pic = $_FILES['pic'];
		$pic['data'] = file_get_contents($pic['tmp_name']);
	}

	$add_result = $JishiGouAPI->AddTopic($_REQUEST['text'],$_REQUEST['totid'],$_REQUEST['type'],$_REQUEST['pic_url'],$pic);
    if($add_result['error'])
    {
        api_json_result(1,"1","发布失败",$data);
    }
    else
    {
		$new_tid=$add_result['result']['tid'];
		$uid=$_POST['uid'];
		$fuid=$_POST['fuid'];
		$roottid=$_POST['roottid'];
		if(!$roottid)
		{
			$roottid=0;
		}
		$client_from=$_POST['from'];
		//更新UID
		if($uid && $new_tid)
		{
			$sql2 = "update `jishigou_topic_image` set `uid`='{$uid}' where tid='".$new_tid."' ";
			$dsql->ExecuteNoneQuery($sql2);
			$sql3 = "update `jishigou_topic` set `uid`='{$uid}',`fuid`='{$fuid}',`roottid`='{$roottid}',`from`='{$client_from}',`item`='{$client_from}' where tid='".$new_tid."' ";
			$dsql->ExecuteNoneQuery($sql3);
		}
	

		//处理图片
		$pic_info=$dsql->GetOne("select id,photo from jishigou_topic_image where tid='".$new_tid."' ");
		if($pic_info['photo'])
		{
			$pic_o=$pic_info['photo'];
			$pic_name_arr=explode("/",$pic_info['photo']);
			$pic_name_o=$pic_name_arr[5];

			$pic_p=str_replace("_o","_p",$pic_info['photo']);
			$pic_s=str_replace("_o","_s",$pic_info['photo']);

			$save_path2="../upload/topic_image/";
			$full_save_path2=$save_path2.date("Ymd",time())."/";
			if(!file_exists($save_path2))
			{
				mkdir($save_path2);
			}
			if(!file_exists($full_save_path2))
			{
				mkdir($full_save_path2);
			}

			//移动文件
			$new_pic="./upload/topic_image/".date("Ymd",time())."/".$pic_name_o;
			FileUtil::copyFile("../../t2/".$pic_o, $full_save_path2.$pic_name_o);
			FileUtil::copyFile("../../t2/".$pic_p, $full_save_path2.str_replace("_o","_p",$pic_name_o));
			FileUtil::copyFile("../../t2/".$pic_s, $full_save_path2.str_replace("_o","_s",$pic_name_o));

			$sql = "update `jishigou_topic_image` set `photo`='{$new_pic}' where `id`='{$pic_info['id']}'";
			$dsql->ExecuteNoneQuery($sql);
		}

	
		//更新 update
		//if ($_FILES["voice"]["error"]<=0)
		if ($_FILES["voice"]["error"]<=0 && $_FILES["voice"]["name"])
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

			move_uploaded_file($_FILES["voice"]["tmp_name"], $full_save_path. time().$_FILES["voice"]["name"]);//将上传的文件存储到服务器
			$file_path="./upload/voice/".date("Ymd",time())."/".time().$_FILES["voice"]["name"];
			$voice_timelong=$_POST['voice_timelong'];
			$sql = "update `jishigou_topic` set `voice`='".$file_path."',`voice_timelong`='{$voice_timelong}' where `tid`='{$new_tid}'";
			$res=$dsql->ExecuteNoneQuery($sql);
			//echo $sql;
		}
		//print_r($add_result);
        api_json_result(1,"0","发布成功",$data);
    }

	
	
}


if($ac=="add_topic2")
{
	$new_tid=27971;
	
	echo $sql;

	echo $new_pic;



	print_r($pic_info);


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

