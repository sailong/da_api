<?php
	#require_once libfile('class/uploaded_avatar_filename_getter','join');
	require_once('class_uploaded_avatar_filename_getter.php');
	class avatar_reader{
		public function read($uid,$uploaddir,$uploaded_file_desc='_avatar'){

			$uafg = new uploaded_avatar_filename_getter();
			$uploadfile = $uafg->get($uid,$uploaddir,$uploaded_file_desc);
			$avatarInfo = getimagesize($uploadfile);
			header('Content-type: '.$avatarInfo['mime']);
			readfile($uploadfile);
			exit;
			
		}
	}
?>
