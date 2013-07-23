<?php
	class daz_guess_activities_guessing_types_result_deleter{
		
		public function delete($activity_type_id){
			/*
			$dgagtr = DB::table('daz_guess_activities_guessing_types_result');
			$sql = "DELETE FROM ".$dgagtr." WHERE id=".$id;
			DB::query($sql);
			*/
			
			$dgagtr = DB::table('daz_guess_activities_guessing_types_result');
			$sql = "DELETE FROM ".$dgagtr." WHERE activity_type_id=".$activity_type_id;
			DB::query($sql);
			
		}
		
	}
?>