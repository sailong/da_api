<?php
	class guess_result_getter{
		public function get_uid_by_users_activities_types_id($users_activities_types_id){
			
			$gs = DB::table('guess_result');
			$sql = "SELECT user_id FROM ".$gs." WHERE users_activities_types_id=".$users_activities_types_id." ORDER BY id ASC";
			$tmp = DB::query($sql);
			$rows = array();
			while($r = DB::fetch($tmp)) {
				$rows[] = $r;
			}
			return $rows;
			
		}
	}
?>