<?php
	class guess_activity_type_deleter{
		public function delete($guess_activity_id_str){
			/*
			$dgagt = DB::table('daz_guess_activities_guessing_types');
			$sql = "DELETE FROM ".$dgagt." WHERE guess_activity_id IN (".$guess_activity_id_str.")";
			DB::query($sql);
			*/
			
			$dgagt = DB::table('daz_guess_activities_guessing_types');
			$sql = "UPDATE ".$dgagt." SET delete_or_not=1 WHERE guess_activity_id IN (".$guess_activity_id_str.")";
			DB::query($sql);
			
		}
	}
?>