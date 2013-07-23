<?php
	class daz_guess_activities_guessing_types_result_adder{
		public function add($activity_type_id,$uid){
			$dgagtr = DB::table('daz_guess_activities_guessing_types_result');
			$sql = "INSERT INTO ".$dgagtr."(activity_type_id,uid) VALUES(".$activity_type_id.",".$uid.")";
			DB::query($sql);
		}
	}
?>