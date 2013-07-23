<?php
	class guess_result_address_getter{
		public function get($activity_id,$type_id){
			$dgagt = DB::table('daz_guess_activities_guessing_types');
			$sql = "SELECT * FROM ".$dgagt." WHERE guess_activity_id=".$activity_id." AND guessing_type_id=".$type_id;
			$tmp = DB::query($sql);
			$rows = array();
			while($r = DB::fetch($tmp)) {
				$rows[] = $r;
			}
			return $rows;
		}
	}
?>