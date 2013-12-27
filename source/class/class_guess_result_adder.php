<?php
	class guess_result_adder{
		public function add($users_activities_types_id,$user_id){
			$gr = DB::table('guess_result');
			$sql = "INSERT INTO ".$gr."(users_activities_types_id,user_id) VALUES(".$users_activities_types_id.",".$user_id.")";
			DB::query($sql);
		}
	}
?>