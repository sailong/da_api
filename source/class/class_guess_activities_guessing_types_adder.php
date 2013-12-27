<?php
	class guess_activities_guessing_types_adder{
		public function add($guess_activity_id,$guessing_type_id){
		
			$dgagt = DB::table('daz_guess_activities_guessing_types');
			$sql = "SELECT COUNT(*) FROM ".$dgagt." WHERE guess_activity_id=".$guess_activity_id." AND guessing_type_id=".$guessing_type_id;
			$tmp = DB::query($sql);
			$r = DB::fetch($tmp);
			if($r["COUNT(*)"]==0){
				$dgagt = DB::table('daz_guess_activities_guessing_types');
				$sql = "INSERT INTO ".$dgagt."(guess_activity_id,guessing_type_id,delete_or_not) VALUES(".$guess_activity_id.",".$guessing_type_id.",0)";
				DB::query($sql);
			}else{
				$dgagt = DB::table('daz_guess_activities_guessing_types');
				$sql = "UPDATE ".$dgagt." SET delete_or_not=0 WHERE guess_activity_id=".$guess_activity_id." AND guessing_type_id=".$guessing_type_id." AND delete_or_not=1";
				DB::query($sql);
			}
			
		}
	}
?>