<?php
/**
 * jishigou api 示例
 * 
 * @author 狐狸<foxis@qq.com>
 * @version $Id: index.php 926 2012-05-15 01:41:04Z wuliyong $
 */

error_reporting(E_ALL ^ E_NOTICE);

header('Content-type: text/html;charset=utf-8');

include('../../t2/setting/settings.php');
include('./config.php');
include('./jishigouapi.class.php');
include('./sql_v2.class.php');
include('./file.class.php');

$ac = $_GET['ac'];
if($ac=="")
{
	echo " ac 参数为空，请修改 ";
}


//token口令

function yanzheng_token($token)
{
	
	$code=substr($token,0,32);
	$uid=substr($token,32,(strlen($token)-32));
	/*
	echo "<hr>";
	echo $time;
	echo "<hr>";
	echo $code;
	echo "<hr>";
	*/
	if($code<>md5(date("Ymd",time())."bwvip.com"))
	{
		return false;
	}
	else
	{
		if($uid)
		{
			define("TOKEN_UID",$uid);
			//echo $uid;
		}
		return true;
	}

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


//删除微博
if($ac=="delete_topic")
{
	$tid=$_GET['tid'];
	if($tid)
	{
		$all_topic = $JishiGouAPI->DeleteTopic($tid);
		print_r($all_topic);
		if($all_topic['error'])
		{
			//jishigouapi_message($all_topic['result']);
			echo "删除失败，请重试";
		}
		else
		{
			echo "删除成功";
		}
	}

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
		/*
		for($i=0; $i<count($pic); $i++)
		{
			$pic[$i]['data'] = file_get_contents($pic[$i]['tmp_name']);
		}
		*/
		
	}
    $return_data = array();
	$add_result = $JishiGouAPI->AddTopic($_REQUEST['text'],$_REQUEST['totid'],$_REQUEST['type'],$_REQUEST['pic_url'],$pic);
    if($add_result['error'])
    {
        api_json_result(1,"1","发布失败",$data);
    }
    else
    {
        
		$new_tid=$add_result['result']['tid'];
		$return_data['tid'] = $new_tid;
		$uid=$_POST['uid'];
		$fuid=$_POST['fuid'];
		$roottid=$_POST['roottid'];
		$user_info=$dsql->GetOne("select nickname from jishigou_members where uid='".$uid."' ");
		$return_data['username'] = $user_info['nickname'];
		
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
		if($_FILES["voice"]["error"]<=0 && $_FILES["voice"]["name"])
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

			$ext_name=end(explode(".",$_FILES["voice"]["name"]));
			if($ext_name!="mp3")
			{
				//转换
				$res=change2mp3("/home/www/dzbwvip/weibo/".$file_path);
				$sql_file_path=$file_path.".mp3";
			}
			else
			{
				$sql_file_path=$file_path;
			}

			$voice_timelong=$_POST['voice_timelong'];
			$sql = "update `jishigou_topic` set `voice`='".$sql_file_path."',`voice_timelong`='{$voice_timelong}' where `tid`='{$new_tid}'";
			$res=$dsql->ExecuteNoneQuery($sql);
			//echo $sql;
		}
		$data['title'] = 'return_data';
		$data['data'] = $return_data; 
		//print_r($add_result);
        api_json_result(1,"0","发布成功",$data);
    }

	
	
}

//微博上传图片接口
if($ac == 'upload_pic') {
	
    $tid = $_POST['tid'];
    $uid = $_POST['uid'];
    $user_name = $_POST['username'];
	if(empty($tid)) 
    {
        api_json_result(1,1,"缺少参数tid",null);exit;
    }
	
	if(empty($uid)) {
		$uid_info=$dsql->GetOne("select uid from jishigou_topic where tid='".$tid."' ");
		if(empty($uid_info)) {
			api_json_result(1,2,"此微博不存在",null);exit;
		}
		$uid = $uid_info['uid'];
	}
	if(empty($uid)) {
		api_json_result(1,1,"缺少参数uid",null);exit;
	}
	if(empty($user_name)) {
		$user_info=$dsql->GetOne("select nickname from jishigou_members where uid='".$uid."' ");
		$user_name = $user_info['nickname'];
	}

    if(!empty($_FILES['pic'])) {
    	$now_data = date("Ymd",time());
        $pre_path = '.';
        $save_path=$pre_path."./upload/topic_image/";
    	$full_save_path=$save_path.$now_data."/";
    	if(!file_exists($save_path))
    	{
    		mkdir($save_path);
    	}
    	if(!file_exists($full_save_path))
    	{
    		mkdir($full_save_path);
    	}
    	$file_val = $_FILES['pic'];
		
    	
	    $now_time = time();
	    $t_name = mt_rand(0,100);
	    $pic_name = $file_val['name'];
    	$extname=end(explode(".",$file_val['name']));
    	$pic_name_o = $now_time.$t_name.'_o.'.$extname;
    	$pic_name_p = $now_time.$t_name.'_p.'.$extname;
    	$pic_name_s = $now_time.$t_name.'_s.'.$extname;
    	$pic_name_t = $now_time.$t_name.'_t.'.$extname;
    	$pic_name_o_path="./upload/topic_image/".$now_data."/".$pic_name_o;
    	$pic_name_p_path="./upload/topic_image/".$now_data."/".$pic_name_p;
    	$pic_name_s_path="./upload/topic_image/".$now_data."/".$pic_name_s;
	    $pic_name_t_path= "./upload/topic_image/".$now_data."/".$pic_name_t;
    	move_uploaded_file($file_val['tmp_name'], $pre_path.$pic_name_o_path);
    	$image_file = $pre_path.$pic_name_o_path;
    	$image_file_small = $pre_path.$pic_name_s_path;
    	$image_file_photo = $pre_path.$pic_name_p_path;
    	$image_file_temp = $pre_path.$pic_name_t_path;
    	
    	@copy($image_file, $image_file_temp);
    	if(file_exists($image_file_temp)) {
    	    list($image_width,$image_height,$image_type,$image_attr) = getimagesize($image_file);
      	    //生成小图
			$iw = $image_width;
	        $ih = $image_height;
			$image_width_s = $config['thumbwidth'];
    		if($iw > $image_width_s) {
    			$s_width = $image_width_s;
    			$s_height = round(($ih*$image_width_s)/$iw);
    		}else{
				$s_width=$iw;
				$s_height=$ih;
			}
			$result = makethumb($image_file, $image_file_small, $s_width, $s_height, 0, 0, 0, 0, 0, 0, 0, 100);
	        /* $maxw = $config['maxthumbwidth'];
	        $maxh = $config['maxthumbheight'];
			if($image_width != $image_height) {
			if($maxw > 300 && $maxh > 300 && ($iw > $maxw || $ih > $maxh)) {
				list($iw, $ih) = array($image_width,$image_height);
			}

			$src_x = $src_y = 0;
			$src_w = $src_h = min($iw, $ih);
			if($iw > $ih) {
				$src_x = round(($iw - $ih) / 2);
			} else {
				$src_y = round(($ih - $iw) / 2);
			}
			$result = makethumb($image_file, $image_file_small, $config['thumbwidth'], $config['thumbheight'], 0, 0, $src_x, $src_y, $src_w, $src_h, 0, 100);
			} */
			clearstatcache();
			if (!$result && !is_file($image_file_small)) {
				@copy($image_file_temp, $image_file_small);
			}
			//生成中图
        	$image_width_p = 300;
    		if($iw > $image_width_p) {
    			$p_width = $image_width_p;
    			$p_height = round(($ih*$image_width_p)/$iw);
    			$result = makethumb($image_file, $image_file_photo, $p_width, $p_height, 0, 0, 0, 0, 0, 0, 0, 100);
    		}
    		clearstatcache();
    		if($iw <= $image_width_p || (!$result && !is_file($image_file_photo))) {
    			@copy($image_file_temp, $image_file_photo);
		    }
        	
        	unlink($image_file_temp);
        	
        	$image_info = getimagesize($pre_path.$pic_name_o_path);
        	$pic_path = $pic_name_o_path;
        	$pic_size = $file_val['size'];
        	$image_width = $image_info[0];
        	$image_height = $image_info[1];
        	$sql = "INSERT INTO `jishigou_topic_image`(tid,photo,name,filesize,width,height,uid,username,dateline) 
        			VALUES ('{$tid}', '{$pic_path}', '{$pic_name}', '{$pic_size}', '{$image_width}', '{$image_height}', '{$uid}', '{$user_name}', '{$now_time}')";
            $res = $dsql->ExecuteNoneQuery($sql);
            $data['title']='data';
            if(!empty($res)) {
                $image_id = $dsql->GetLastID();
                $dsql->ExecuteNoneQuery("update jishigou_topic set imageid=CONCAT_WS(',',`imageid`,'{$image_id}') where tid={$tid}");
            }else{
                $data['data'] = null;
                api_json_result(1,1,"上传失败",$data);exit;
            }
        	$data['data']['image_id'] = $image_id;
        	api_json_result(1,0,"上传成功",$data);exit;
    	}
    }
    api_json_result(1,1,"上传失败",$data);	
}
//微博上传图片接口---测试接口
if($ac == 'upload_pic_test') {
    if(!empty($_FILES['pic'])) {
    	$now_data = 'test';//date("Ymd",time());
        $pre_path = '.';
        $save_path=$pre_path."./upload/topic_image/";
    	$full_save_path=$save_path.$now_data."/";
    	if(!file_exists($save_path))
    	{
    		mkdir($save_path);
    	}
    	if(!file_exists($full_save_path))
    	{
    		mkdir($full_save_path);
    	}
    	$file_val = $_FILES['pic'];
		
    	//foreach($_FILES as $file_name=>$file_val) {
	    $now_time = time();
	    $t_name = mt_rand(0,100);
	    $pic_name = $file_val['name'];
    	$extname=end(explode(".",$file_val['name']));
    	$pic_name_o = $now_time.$t_name.'_o.'.$extname;
    	$pic_name_p = $now_time.$t_name.'_p.'.$extname;
    	$pic_name_s = $now_time.$t_name.'_s.'.$extname;
    	$pic_name_t = $now_time.$t_name.'_t.'.$extname;
    	$pic_name_o_path="./upload/topic_image/".$now_data."/".$pic_name_o;
    	$pic_name_p_path="./upload/topic_image/".$now_data."/".$pic_name_p;
    	$pic_name_s_path="./upload/topic_image/".$now_data."/".$pic_name_s;
	    $pic_name_t_path= "./upload/topic_image/".$now_data."/".$pic_name_t;
    	move_uploaded_file($file_val['tmp_name'], $pre_path.$pic_name_o_path);
    	$image_file = $pre_path.$pic_name_o_path;
    	$image_file_small = $pre_path.$pic_name_s_path;
    	$image_file_photo = $pre_path.$pic_name_p_path;
    	$image_file_temp = $pre_path.$pic_name_t_path;
    	
    	@copy($image_file, $image_file_temp);
    	if(file_exists($image_file_temp)) {
    	    list($image_width,$image_height,$image_type,$image_attr) = getimagesize($image_file);
      	    //生成小图
			$iw = $image_width;
	        $ih = $image_height;
			$image_width_s = $config['thumbwidth'];
    		if($iw > $image_width_s) {
    			$s_width = $image_width_s;
    			$s_height = round(($ih*$image_width_s)/$iw);
    		}else{
				$s_width=$iw;
				$s_height=$ih;
			}
			$result = makethumb($image_file, $image_file_small, $s_width, $s_height, 0, 0, 0, 0, 0, 0, 0, 100);
	        /* $maxw = $config['maxthumbwidth'];
	        $maxh = $config['maxthumbheight'];
			if($image_width != $image_height) {
			if($maxw > 300 && $maxh > 300 && ($iw > $maxw || $ih > $maxh)) {
				list($iw, $ih) = array($image_width,$image_height);
			}

			$src_x = $src_y = 0;
			$src_w = $src_h = min($iw, $ih);
			if($iw > $ih) {
				$src_x = round(($iw - $ih) / 2);
			} else {
				$src_y = round(($ih - $iw) / 2);
			}
			$result = makethumb($image_file, $image_file_small, $config['thumbwidth'], $config['thumbheight'], 0, 0, $src_x, $src_y, $src_w, $src_h, 0, 100);
			} */
			clearstatcache();
			if (!$result && !is_file($image_file_small)) {
				@copy($image_file_temp, $image_file_small);
			}
			//生成中图
			$image_width_p = 300;
    		if($iw > $image_width_p) {
    			$p_width = $image_width_p;
    			$p_height = round(($ih*$image_width_p)/$iw);
    			$result = makethumb($image_file, $image_file_photo, $p_width, $p_height, 0, 0, 0, 0, 0, 0, 0, 100);
    		}
    		clearstatcache();
    		if($iw <= $image_width_p || (!$result && !is_file($image_file_photo))) {
    			@copy($image_file_temp, $image_file_photo);
		    }
        	
        	unlink($image_file_temp);
        	
    	}
    	//}
    	
    }
   // api_json_result(1,1,"上传失败",$data);	
}

//添加微博----------可以添加多张图片
if($ac=="add_topic_v2")
{
    $uid=$_POST['uid'];
	$fuid=$_POST['fuid'];
	$roottid=$_POST['roottid'];
    
    if(count($_FILES)>9) {
        api_json_result(1,"1","本次发布图片数量超额",$data);exit;
    }
	
	$add_result = $JishiGouAPI->AddTopic($_REQUEST['text'],$_REQUEST['totid'],$_REQUEST['type'],$_REQUEST['pic_url'],'');
	
    if($add_result['error'])
    {
        api_json_result(1,"1","发布失败",$data);
    }
    else
    {
        $tid = $add_result['result']['tid'];
        if(!$roottid)
		{
			$roottid=0;
		}
		$client_from=$_POST['from'];
		//更新UID
		if($uid && $tid)
		{
			$sql = "select realname from `pre_common_member_profile` where `uid`='{$uid}'";
        	$realname = $dsql->GetOne($sql);
        	$realname = $realname['realname'];
			if(empty($realname)) {
				$sql = "select username from `pre_common_member` where `uid`='{$uid}'";
				$realname = $dsql->GetOne($sql);
				$realname = $realname['username'];
			}
        	
			$sql3 = "update `jishigou_topic` set `uid`='{$uid}',`username`='{$realname}',`fuid`='{$fuid}',`roottid`='{$roottid}',`from`='{$client_from}',`item`='{$client_from}' where tid='".$tid."' ";
			echo $sql3;
			$dsql->ExecuteNoneQuery($sql3);
		}
        
        if(!empty($_FILES)) {
        	$now_data = date("Ymd",time());
            $pre_path = '.';
            $save_path=$pre_path."./upload/topic_image/";
        	$full_save_path=$save_path.$now_data."/";
        	if(!file_exists($save_path))
        	{
        		mkdir($save_path);
        	}
        	if(!file_exists($full_save_path))
        	{
        		mkdir($full_save_path);
        	}
        	
        	foreach($_FILES as $file_name=>$file_val) {
        	    $now_time = time();
        	    $t_name = mt_rand(0,100);
        	    $pic_name = $file_val['name'];
            	$extname=end(explode(".",$file_val['name']));
            	$pic_name_o = $now_time.$t_name.'_o.'.$extname;
            	$pic_name_p = $now_time.$t_name.'_p.'.$extname;
            	$pic_name_s = $now_time.$t_name.'_s.'.$extname;
            	$pic_name_t = $now_time.$t_name.'_t.'.$extname;
            	$pic_name_o_path="./upload/topic_image/".$now_data."/".$pic_name_o;
            	$pic_name_p_path="./upload/topic_image/".$now_data."/".$pic_name_p;
            	$pic_name_s_path="./upload/topic_image/".$now_data."/".$pic_name_s;
        	    $pic_name_t_path= "./upload/topic_image/".$now_data."/".$pic_name_t;
            	move_uploaded_file($file_val['tmp_name'], $pre_path.$pic_name_o_path);
            	$image_file = $pre_path.$pic_name_o_path;
            	$image_file_small = $pre_path.$pic_name_s_path;
            	$image_file_photo = $pre_path.$pic_name_p_path;
            	$image_file_temp = $pre_path.$pic_name_t_path;
            	
            	@copy($image_file, $image_file_temp);
            	if(file_exists($image_file_temp)) {
            	    list($image_width,$image_height,$image_type,$image_attr) = getimagesize($image_file);
              	    //生成小图
        			$iw = $image_width;
    		        $ih = $image_height;
    		        $maxw = $config['maxthumbwidth'];
    		        $maxh = $config['maxthumbheight'];
    				if($image_width != $image_height) {
    				if($maxw > 300 && $maxh > 300 && ($iw > $maxw || $ih > $maxh)) {
    					list($iw, $ih) = array($image_width,$image_height);
    				}
    
    				$src_x = $src_y = 0;
    				$src_w = $src_h = min($iw, $ih);
    				if($iw > $ih) {
    					$src_x = round(($iw - $ih) / 2);
    				} else {
    					$src_y = round(($ih - $iw) / 2);
    				}
    				$result = makethumb($image_file, $image_file_small, $config['thumbwidth'], $config['thumbheight'], 0, 0, $src_x, $src_y, $src_w, $src_h, 0, 100);
        			}
        			clearstatcache();
        			if (!$result && !is_file($image_file_small)) {
        				@copy($image_file_temp, $image_file_small);
        			}
        			//生成中图
                	$image_width_p = 0;
                	if($image_width_p < 1) {
                		$image_width_p = 280;
                	}
            		if($iw > $image_width_p) {
            			$p_width = $image_width_p;
            			$p_height = round(($ih*$image_width_p)/$iw);
            			$result = makethumb($image_file, $image_file_photo, $p_width, $p_height, 0, 0, 0, 0, 0, 0, 0, 100);
            		}
            		clearstatcache();
            		if($iw <= $image_width_p || (!$result && !is_file($image_file_photo))) {
            			@copy($image_file_temp, $image_file_photo);
        		    }
                	
                	unlink($image_file_temp);
                	
                	$image_info = getimagesize($pre_path.$pic_name_o_path);
                	$pic_path = $pic_name_o_path;
                	$pic_size = $file_val['size'];
                	$image_width = $image_info[0];
                	$image_height = $image_info[1];
                	$user_name = $add_result['result']['username'];
                	$sql = "INSERT INTO `jishigou_topic_image`(tid,photo,name,filesize,width,height,uid,username,dateline) 
                			VALUES ('{$tid}', '{$pic_path}', '{$pic_name}', '{$pic_size}', '{$image_width}', '{$image_height}', '{$uid}', '{$user_name}', '{$now_time}')";
                    $res = $dsql->ExecuteNoneQuery($sql);
                    $image_ids[] = $dsql->GetLastID();
            	}
    			
        	}
        	$sql = "update `jishigou_topic` set `imageid`='".implode(",",$image_ids)."' where `tid`='{$tid}'";
    		$dsql->ExecuteNoneQuery($sql);
        }
        
		//更新 update
		//if ($_FILES["voice"]["error"]<=0)
		if($_FILES["voice"]["error"]<=0 && $_FILES["voice"]["name"])
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

			$ext_name=end(explode(".",$_FILES["voice"]["name"]));
			if($ext_name!="mp3")
			{
				//转换
				$res=change2mp3("/home/www/dzbwvip/weibo/".$file_path);
				$sql_file_path=$file_path.".mp3";
			}
			else
			{
				$sql_file_path=$file_path;
			}

			$voice_timelong=$_POST['voice_timelong'];
			$sql = "update `jishigou_topic` set `voice`='".$sql_file_path."',`voice_timelong`='{$voice_timelong}' where `tid`='{$new_tid}'";
			$res=$dsql->ExecuteNoneQuery($sql);
			//echo $sql;
		}
		//print_r($add_result);
        api_json_result(1,"0","发布成功",$data);
    }
	
}

function makethumb($srcfile,$dstfile,$thumbwidth,$thumbheight,$maxthumbwidth=0,$maxthumbheight=0,$src_x=0,$src_y=0,$src_w=0,$src_h=0, $thumb_cut_type=0, $thumb_quality = 100) {
		if (!is_file($srcfile)) {
		return '';
	}

	$tow = (int) $thumbwidth;
	$toh = (int) $thumbheight;
	if($tow < 30) {
		$tow = 30;
	}
	if($toh < 30) {
		$toh = 30;
	}

	$make_max = 0;
	$maxtow = (int) $maxthumbwidth;
	$maxtoh = (int) $maxthumbheight;
	if($maxtow >= 300 && $maxtoh >= 300)
	{
		$make_max = 1;
	}

	$im = '';
	if(false != ($data = getimagesize($srcfile))) {
		if($data[2] == 1) {
			$make_max = 0;			if(function_exists("imagecreatefromgif")) {
				$im = imagecreatefromgif($srcfile);
			}
		} elseif($data[2] == 2) {
			if(function_exists("imagecreatefromjpeg")) {
				$im = imagecreatefromjpeg($srcfile);
			}
		} elseif($data[2] == 3) {
			if(function_exists("imagecreatefrompng")) {
				$im = imagecreatefrompng($srcfile);
			}
		}
	}
	if(!$im) return '';

	$srcw = ($src_w ? $src_w : imagesx($im));
	$srch = ($src_h ? $src_h : imagesy($im));

	$towh = $tow/$toh;
	$srcwh = $srcw/$srch;
	if($towh <= $srcwh) {
		$ftow = $tow;
		$ftoh = round($ftow*($srch/$srcw),2);
	} else {
		$ftoh = $toh;
		$ftow = round($ftoh*($srcw/$srch),2);
	}


	if($make_max) {
		$maxtowh = $maxtow/$maxtoh;
		if($maxtowh <= $srcwh) {
			$fmaxtow = $maxtow;
			$fmaxtoh = round($fmaxtow*($srch/$srcw),2);
		} else {
			$fmaxtoh = $maxtoh;
			$fmaxtow = round($fmaxtoh*($srcw/$srch),2);
		}

		if($srcw <= $maxtow && $srch <= $maxtoh) {
			$make_max = 0;		
		}
	}


	$maxni = '';
	$thumb_quality = (int) $thumb_quality;
	if($thumb_quality < 1 || $thumb_quality > 100) {
		$thumb_quality = 100;
	}
	if($srcw >= $tow || $srch >= $toh) {
		if(function_exists("imagecreatetruecolor") && function_exists("imagecopyresampled") && ($ni = imagecreatetruecolor($ftow, $ftoh))) {
			imagecopyresampled($ni, $im, 0, 0, $src_x, $src_y, $ftow, $ftoh, $srcw, $srch);
						if($make_max && ($maxni = imagecreatetruecolor($fmaxtow, $fmaxtoh))) {
				imagecopyresampled($maxni, $im, 0, 0, $src_x, $src_y, $fmaxtow, $fmaxtoh, $srcw, $srch);
			}
		} elseif(function_exists("imagecreate") && function_exists("imagecopyresized") && ($ni = imagecreate($ftow, $ftoh))) {
			imagecopyresized($ni, $im, 0, 0, $src_x, $src_y, $ftow, $ftoh, $srcw, $srch);
						if($make_max && ($maxni = imagecreate($fmaxtow, $fmaxtoh))) {
				imagecopyresized($maxni, $im, 0, 0, $src_x, $src_y, $fmaxtow, $fmaxtoh, $srcw, $srch);
			}
		} else {
			return '';
		}
		if(function_exists('imagejpeg')) {
			imagejpeg($ni, $dstfile, $thumb_quality);
						if($make_max && $maxni) {
				imagejpeg($maxni, $srcfile, $thumb_quality);
			}
		} elseif(function_exists('imagepng')) {
			imagepng($ni, $dstfile);
						if($make_max && $maxni) {
				imagepng($maxni, $srcfile);
			}
		}
		imagedestroy($ni);
		if($make_max && $maxni) {
			imagedestroy($maxni);
		}
	}
	imagedestroy($im);

	if(!is_file($dstfile)) {
		return '';
	} else {
		return $dstfile;
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

function change2mp3($srcfile)
{
	if(is_file($srcfile))
	{
		//echo "文件不存在";
		//$cmd = '/usr/bin/ffmpeg -i '.$srcfile.' -o '.$srcfile.'.mp3 ';  
		$cmd = 'ffmpeg -i '.$srcfile.'  '.$srcfile.'.mp3 ';  
		//echo $cmd;
		$res = shell_exec($cmd); 
	}

}

//$res=change2mp3("/home/www/dzbwvip/weibo/upload/voice/20130408/1365423421RecordedFile.aac");






?>

