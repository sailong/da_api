<?php
	class users_activities_types_adder{
		public function add($uid,$activity_id,$type_id){
			$uat = DB::table('users_activities_types');
			$sql = "INSERT INTO ".$uat."(uid,activity_id,type_id) VALUES(".$uid.",".$activity_id.",".$type_id.")";
			DB::query($sql);
			return DB::insert_id();
		}
	}
?>