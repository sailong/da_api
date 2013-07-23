<?php
	class guess_activities_guessing_types_getter{
	
		public function get_by_activity_id($activity_id){
			$dgagt = DB::table('daz_guess_activities_guessing_types');
			
			$sql = "SELECT * FROM ".$dgagt." WHERE guess_activity_id=".$activity_id." AND delete_or_not=0";
			
			$tmp = DB::query($sql);
			
			while($r = DB::fetch($tmp)){
				$rows[] = $r;
			}
			return $rows;
			
		}
		
		public function get_id_by_activity_id_type_id($activity_id,$type_id){
			$dgagt = DB::table('daz_guess_activities_guessing_types');
			$sql = "SELECT id FROM ".$dgagt." WHERE guess_activity_id=".$activity_id." AND guessing_type_id=".$type_id." AND delete_or_not=0";
			$tmp = DB::query($sql);
			
			while($r = DB::fetch($tmp)){
				$rows[] = $r;
			}
			return $rows[0]['id'];
			
		}
		
		
		
		public function get_amount_by_type_id($type_id){
			
			$dgagt = DB::table('daz_guess_activities_guessing_types');
			
			$sql = "SELECT count(*) FROM ".$dgagt." WHERE guessing_type_id=".$type_id." AND delete_or_not=0";
			
			$tmp = DB::query($sql);
			$r = DB::fetch($tmp);
			return $r["count(*)"];
			
		}
		
	}
?>