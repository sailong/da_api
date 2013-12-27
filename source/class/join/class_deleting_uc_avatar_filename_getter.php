<?php
	class deleting_uc_avatar_filename_getter{
		public function get($big_img_tar_path_prefix,$middle_img_tar_path_prefix,$small_img_tar_path_prefix){
			$suffixs = array('.jpg','.png');
			$tmp = array();
			for($i=0;$i<count($suffixs);$i++){
				if(file_exists($big_img_tar_path_prefix.$suffixs[$i])){
					$tmp[] = $big_img_tar_path_prefix.$suffixs[$i];
				}
				if(file_exists($middle_img_tar_path_prefix.$suffixs[$i])){
                                        $tmp[] = $middle_img_tar_path_prefix.$suffixs[$i];
                                }
				if(file_exists($small_img_tar_path_prefix.$suffixs[$i])){
                                        $tmp[] = $small_img_tar_path_prefix.$suffixs[$i];
                                }		
			}
			return $tmp;
		}
	}
?>
