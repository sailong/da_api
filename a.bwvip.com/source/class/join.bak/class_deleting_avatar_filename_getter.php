<?php
	class deleting_avatar_filename_getter{
		public function get($uid,$uploaddir,$last_avatar_filename){
			$suffixs = array('.jpg','.png');

			$prefix = substr($last_avatar_filename,0,strlen($last_avatar_filename)-4);
			$tmp = array();
			for($i=0;$i<count($suffixs);$i++){
				if($prefix.$suffixs[$i]!=$last_avatar_filename && file_exists($prefix.$suffixs[$i])){
					$tmp[] = $prefix.$suffixs[$i];
				}
			}
			return $tmp;

		}
	}
?>
