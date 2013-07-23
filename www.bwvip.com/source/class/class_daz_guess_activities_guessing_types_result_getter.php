<?php
	class daz_guess_activities_guessing_types_result_getter{
	
		public function get_by_activity_type_id($activity_type_id){
			$dgagtr = DB::table('daz_guess_activities_guessing_types_result');
			$sql = "SELECT id,uid FROM ".$dgagtr." WHERE activity_type_id=".$activity_type_id." ORDER BY id ASC";
			$tmp = DB::query($sql);
			while($r = DB::fetch($tmp)){
				$rows[] = $r;
			}
			return $rows;
		}
		
	}
?>