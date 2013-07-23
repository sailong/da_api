<?php
	class uc_avatar_adder{
		public function add($uid,$img_src_path,$tar_path_prefix='/home/www/dzbwvip/uc_server/data/avatar/'){

			require_once($_SERVER["DOCUMENT_ROOT"].'/uc_server/lib/upload.class.php');
			
			$u = new upload();
			$img_tar_path_fragment = $u->mkdir_by_uid($uid);

			$tmp = explode("/",$img_tar_path_fragment);
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
	
			
			

			$big_img_tar_path_prefix = $tar_path_prefix.$img_tar_path_fragment.'/'.$uid_suffix.'_avatar_big';
			$middle_img_tar_path_prefix = $tar_path_prefix.$img_tar_path_fragment.'/'.$uid_suffix.'_avatar_middle';
			$small_img_tar_path_prefix = $tar_path_prefix.$img_tar_path_fragment.'/'.$uid_suffix.'_avatar_small';			

			#require_once libfile('class/deleting_uc_avatar_filename_getter','join');
			require_once('class_deleting_uc_avatar_filename_getter.php');
                        $duafg = new deleting_uc_avatar_filename_getter();
                        $deleting_uc_avatar_filename = $duafg->get($big_img_tar_path_prefix,$middle_img_tar_path_prefix,$small_img_tar_path_prefix);

			for($i=0;$i<count($deleting_uc_avatar_filename);$i++){
                                unlink($deleting_uc_avatar_filename[$i]);
                        }

			$img_type_suffix = substr($img_src_path,strlen($img_src_path)-4);		

		
			#copy($img_src_path,$big_img_tar_path_prefix.$img_type_suffix);
			copy($img_src_path,$big_img_tar_path_prefix.'.jpg');	

			//middle

			$src_avatar_info = getimagesize($img_src_path);
			$src_avatar_width = $src_avatar_info[0];
			$src_avatar_height = $src_avatar_info[1];

			if($src_avatar_width>$src_avatar_height){

				if($src_avatar_width>119){

					$tmp = $src_avatar_width;
					$src_avatar_width = 119;
					$rate = $tmp/$src_avatar_width;
					$src_avatar_height = round($src_avatar_height/$rate);

				}


			}

			if($src_avatar_height>$src_avatar_width){

				if($src_avatar_height>119){
					$tmp = $src_avatar_height;
					$src_avatar_height = 119;
					$rate = $tmp/$src_avatar_height;
					$src_avatar_width = round($src_avatar_width/$rate);
				}

			}

			if($src_avatar_width==$src_avatar_height){

				if($src_avatar_height>119){
					$src_avatar_width = 119;
					$src_avatar_height = 119;
				}

			}

			if($img_type_suffix=='.jpg'){
				$new = imagecreatetruecolor($src_avatar_width, $src_avatar_height);
				$source = imagecreatefromjpeg($img_src_path);
				imagecopyresized($new, $source, 0, 0, 0, 0, $src_avatar_width, $src_avatar_height, $src_avatar_info[0], $src_avatar_info[1]);
				imagejpeg($new,$middle_img_tar_path_prefix.$img_type_suffix);
			}

			if($img_type_suffix=='.png'){
				$new = imagecreatetruecolor($src_avatar_width, $src_avatar_height);
				$source = imagecreatefrompng($img_src_path);
				imagecopyresized($new, $source, 0, 0, 0, 0, $src_avatar_width, $src_avatar_height, $src_avatar_info[0], $src_avatar_info[1]);
				
				#imagepng($new,$middle_img_tar_path_prefix.$img_type_suffix);
				imagepng($new,$middle_img_tar_path_prefix.'.jpg');
                        }
                        
			//small

			$src_avatar_info = getimagesize($img_src_path);
			$src_avatar_width = $src_avatar_info[0];
			$src_avatar_height = $src_avatar_info[1];

			if($src_avatar_width>$src_avatar_height){

				if($src_avatar_width>48){

					$tmp = $src_avatar_width;
					$src_avatar_width = 48;
					$rate = $tmp/$src_avatar_width;
					$src_avatar_height = round($src_avatar_height/$rate);

				}


			}

			if($src_avatar_height>$src_avatar_width){

				if($src_avatar_height>48){
					$tmp = $src_avatar_height;
					$src_avatar_height = 48;
					$rate = $tmp/$src_avatar_height;
					$src_avatar_width = round($src_avatar_width/$rate);
				}

			}

			if($src_avatar_width==$src_avatar_height){

				if($src_avatar_height>48){
					$src_avatar_width = 48;
					$src_avatar_height = 48;
				}

			}

			if($img_type_suffix=='.jpg'){
				$new = imagecreatetruecolor($src_avatar_width, $src_avatar_height);
				$source = imagecreatefromjpeg($img_src_path);
				imagecopyresized($new, $source, 0, 0, 0, 0, $src_avatar_width, $src_avatar_height, $src_avatar_info[0], $src_avatar_info[1]);
				imagejpeg($new,$small_img_tar_path_prefix.$img_type_suffix);
			}

			if($img_type_suffix=='.png'){
				$new = imagecreatetruecolor($src_avatar_width, $src_avatar_height);
				$source = imagecreatefrompng($img_src_path);
				imagecopyresized($new, $source, 0, 0, 0, 0, $src_avatar_width, $src_avatar_height, $src_avatar_info[0], $src_avatar_info[1]);
				
				#imagepng($new,$small_img_tar_path_prefix.$img_type_suffix);
				imagepng($new,$small_img_tar_path_prefix.'.jpg');
                        }	

		}
	}
?>
