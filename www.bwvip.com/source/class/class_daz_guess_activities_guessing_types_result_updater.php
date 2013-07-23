<?php
	class daz_guess_activities_guessing_types_result_updater{
	
		public function update($id,$activity_type_id,$uid){
			$dgagtr = DB::table('daz_guess_activities_guessing_types_result');
			$sql = "UPDATE ".$dgagtr." SET uid=".$uid." WHERE id=".$id." AND activity_type_id=".$activity_type_id;
			//var_dump($sql);
			DB::query($sql);
		}
	
		
	}
?>