<?php
	require_once($_SERVER["DOCUMENT_ROOT"].'/uc_server/lib/upload.class.php');
	class avatar_uploader{
		public function upload($uid,$uploaddir,$uploaded_file_desc='_avatar'){
			
			if($_FILES['avatar']['error']!=0){
				echo '上传错误';
				exit;
			}

			//var_dump($_FILES['avatar']["type"]);
			
			if($_FILES['avatar']["type"] != 'image/jpeg' && $_FILES['avatar']["type"] != 'image/png' && $_FILES['avatar']["type"] != 'image/pjpeg' && $_FILES['avatar']["type"] != 'image/x-png'){
                                echo '只能上传jpg或png类型的图片';
                                exit;
                        }

			if($_FILES['avatar']["size"] > 2048000){
                                echo '图片大小不得超过2M';
                                exit;
                        }

			if($_FILES['avatar']["type"] == 'image/jpeg'){
                                $suffix = '.jpg';
                        }

			if($_FILES['avatar']["type"] == 'image/png'){
                                $suffix = '.png';
                        }

			if($_FILES['avatar']["type"] == 'image/pjpeg'){
                                $suffix = '.jpg';
                        }

			if($_FILES['avatar']["type"] == 'image/x-png'){
                                $suffix = '.png';
                        }
			//
			#$uploadfile = $uploaddir.'avatar_'.$uid.$suffix;
			$u = new upload();
                        $img_path_fragment = $u->mkdir_by_uid($uid,$uploaddir);
			$tmp = explode("/",$img_path_fragment);
                        $buf = '';
                        for($i=0;$i<count($tmp);$i++){
                                $buf = $buf.$tmp[$i];
                        }

			

                        $tmp = '';
                        for($i=0;$i<strlen($buf);$i++){
                                if(substr($buf,$i,1)=='0'){
                                        $tmp = $tmp.'0';
                                }else{
                                        break;
                                }

                        }
                        $uid_suffix = substr($tmp.$uid,7);

			

			$uploadfile = $uploaddir.$img_path_fragment.'/'.$uid_suffix.$uploaded_file_desc.$suffix;
			
			
                        if (move_uploaded_file($_FILES['avatar']['tmp_name'],$uploadfile)) {
						//if (copy($_FILES['avatar']['tmp_name'],$uploadfile)) {
				
				if($uploaded_file_desc!='_avatar'){
					$original_size_uploadfile = $uploaddir.$img_path_fragment.'/'.$uid_suffix.$uploaded_file_desc.'_original_size'.$suffix;
					copy($uploadfile,$original_size_uploadfile);		
				}

				$avatar_info = getimagesize($uploadfile);
				$avatar_width = $avatar_info[0];
				$avatar_height = $avatar_info[1];

				if($avatar_width>$avatar_height){

					if($avatar_width>200){
					
						$tmp = $avatar_width;
						$avatar_width = 200;
						$rate = $tmp/$avatar_width;
						$avatar_height = round($avatar_height/$rate);

					}

						
				}

				if($avatar_height>$avatar_width){

					if($avatar_height>200){
						$tmp = $avatar_height;
						$avatar_height = 200;
						$rate = $tmp/$avatar_height;
						$avatar_width = round($avatar_width/$rate);	
					}
					
				}

				if($avatar_width==$avatar_height){

					if($avatar_height>200){
						$avatar_width = 200;
						$avatar_height = 200;					
					}

                                }

				if($_FILES['avatar']["type"] == 'image/jpeg' || $_FILES['avatar']["type"] == 'image/pjpeg'){
					$new = imagecreatetruecolor($avatar_width, $avatar_height);
					$source = imagecreatefromjpeg($uploadfile);
					imagecopyresized($new, $source, 0, 0, 0, 0, $avatar_width, $avatar_height, $avatar_info[0], $avatar_info[1]);
					imagejpeg($new,$uploadfile);
				}

				

				if($_FILES['avatar']["type"] == 'image/png' || $_FILES['avatar']["type"] == 'image/x-png'){
					$new = imagecreatetruecolor($avatar_width, $avatar_height);
					$source = imagecreatefrompng($uploadfile);
					imagecopyresized($new, $source, 0, 0, 0, 0, $avatar_width, $avatar_height, $avatar_info[0], $avatar_info[1]);
					
					imagepng($new,$uploadfile);
						
				}
				
				

				#require_once libfile('class/deleting_avatar_filename_getter','join');
				require_once('class_deleting_avatar_filename_getter.php');

				$dafg = new deleting_avatar_filename_getter();
				$tmp = $dafg->get($uid,$uploaddir,$uploadfile);
				
				for($i=0;$i<count($tmp);$i++){
					unlink($tmp[$i]);
				}

				if($uploaded_file_desc!='_avatar'){
					$dafg = new deleting_avatar_filename_getter();
					$tmp = $dafg->get($uid,$uploaddir,$original_size_uploadfile);
				
					for($i=0;$i<count($tmp);$i++){
						unlink($tmp[$i]);
					}	
				}

				

                                echo 'ok';
                        }else{
                                echo '拷贝上传文件错误';
                        }

		}
	}
?>
