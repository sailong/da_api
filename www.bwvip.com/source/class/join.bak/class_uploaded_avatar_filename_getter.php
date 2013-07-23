<?php
	require_once($_SERVER["DOCUMENT_ROOT"].'/uc_server/lib/upload.class.php');
	class uploaded_avatar_filename_getter{
		
		public function get($uid,$uploaddir,$uploaded_file_desc='_avatar'){
			
			$u = new upload();
			$img_path_fragment = $u->mkdir_by_uid($uid);
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

			$suffixs = array('.jpg','.png');
			for($i=0;$i<count($suffixs);$i++){
				if(file_exists($uploaddir.$img_path_fragment.'/'.$uid_suffix.$uploaded_file_desc.$suffixs[$i])){
					return $uploaddir.$img_path_fragment.'/'.$uid_suffix.$uploaded_file_desc.$suffixs[$i];
				}
			}
			return '';

		}

	}
?>
