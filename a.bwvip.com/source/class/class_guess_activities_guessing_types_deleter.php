<?php
	class guess_activities_guessing_types_deleter{
		public function delete_by_activity_id($activity_id){
			/*
			$dgagt = DB::table('daz_guess_activities_guessing_types');
			$sql = "DELETE FROM ".$dgagt." WHERE guess_activity_id=".$activity_id;
			DB::query($sql);
			*/
			$dgagt = DB::table('daz_guess_activities_guessing_types');
			$sql = "UPDATE ".$dgagt." SET delete_or_not=1 WHERE guess_activity_id=".$activity_id;
			DB::query($sql);
		}
		
		public function physically_delete_by_activity_id($activity_id){
			$dgagt = DB::table('daz_guess_activities_guessing_types');
			$sql = "DELETE FROM ".$dgagt." WHERE guess_activity_id=".$activity_id;
			DB::query($sql);
		}
		
	}
?>