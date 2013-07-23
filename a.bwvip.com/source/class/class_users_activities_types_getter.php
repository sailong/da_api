<?php
	class users_activities_types_getter{
		public function get_amount_by_user_id_activity_id_type_id($user_id,$activity_id,$type_id){
			$uat = DB::table('users_activities_types');
			$sql = "SELECT count(*) FROM ".$uat." WHERE uid=".$user_id." AND activity_id=".$activity_id." AND type_id=".$type_id;
			$tmp = DB::query($sql);
			$r = DB::fetch($tmp);
			return $r["count(*)"];
		}
		
		public function get_id_by_user_id_activity_id_type_id($user_id,$activity_id,$type_id){
			$uat = DB::table('users_activities_types');
			$sql = "SELECT id FROM ".$uat." WHERE uid=".$user_id." AND activity_id=".$activity_id." AND type_id=".$type_id;
			$tmp = DB::query($sql);
			$r = DB::fetch($tmp);
			return $r["id"];
		}
		
		/*
		public function get_info_perpage($activity_id,$start_pos,$len){
			$uat = DB::table('users_activities_types');
			$sql = "SELECT DISTINCT type_id FROM ".$uat." WHERE activity_id=".$activity_id." LIMIT ".$start_pos.",".$len;
			
			$tmp = DB::query($sql);
			$rows = array();
			while($r = DB::fetch($tmp)) {
				$rows[] = $r;
			}
			return $rows;
		}
		
		public function get_type_id_by_activity_id($activity_id){
			$uat = DB::table('users_activities_types');
			$sql = "SELECT DISTINCT type_id FROM ".$uat." WHERE activity_id=".$activity_id;
			$tmp = DB::query($sql);
			$rows = array();
			while($r = DB::fetch($tmp)) {
				$rows[] = $r;
			}
			return $rows;
		}
		*/
		
		public function get_amount_by_activity_id($activity_id){
			$uat = DB::table('users_activities_types');
			$sql = "SELECT count(*) FROM ".$uat." WHERE activity_id=".$activity_id;
			$tmp = DB::query($sql);
			$r = DB::fetch($tmp);
			return $r["count(*)"];
		}
		
		public function get_info_perpage($activity_id,$start_pos,$len){
			$uat = DB::table('users_activities_types');
			$sql = "SELECT id,uid,type_id FROM ".$uat." WHERE activity_id=".$activity_id." LIMIT ".$start_pos.",".$len;
			$tmp = DB::query($sql);
			$rows = array();
			while($r = DB::fetch($tmp)) {
				$rows[] = $r;
			}
			return $rows;
		}
		
		public function get_amount_by_type_id($type_id){
			
			$uat = DB::table('users_activities_types');
			$sql = "SELECT count(*) FROM ".$uat." WHERE type_id=".$type_id;
			//var_dump($sql);
			$tmp = DB::query($sql);
			$r = DB::fetch($tmp);
			return $r["count(*)"];
			
		}
		
	}
?>