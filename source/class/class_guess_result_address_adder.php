<?php
	class guess_result_address_adder{
		public function add($activity_id,$type_id,$address){
			$dgagt = DB::table('daz_guess_activities_guessing_types');
			$sql = "UPDATE ".$dgagt." SET address='".$address."' WHERE guess_activity_id=".$activity_id." AND guessing_type_id=".$type_id." AND delete_or_not=0";
			DB::query($sql);
		}
	}
?>