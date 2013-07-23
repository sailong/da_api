<?php
	class types_activities_getter{
		
		
		public function get_amount($where){
			$dgagt = DB::table('daz_guess_activities_guessing_types');
			$sql = "SELECT * FROM ".DB::table('daz_guess_activities_guessing_types')." ".$where;
			$tmp = DB::query($sql);
			return DB::num_rows($tmp);
		}
		
		public function get_info_perpage($start_pos,$len){
			$dgagt = DB::table('daz_guess_activities_guessing_types');
			$dga = DB::table('daz_guess_activities');
			$dgt = DB::table('daz_guessing_types');
			$sql = "SELECT ".$dga.".name AS activity_name,".$dgt.".name AS type_name,start_time,end_time,".$dga.".id AS activity_id,".$dgt.".id AS type_id FROM ".$dgagt.",".$dga.",".$dgt." WHERE ".$dga.".id=".$dgagt.".guess_activity_id AND ".$dgt.".id=".$dgagt.".guessing_type_id AND ".$dgagt.".delete_or_not=0 LIMIT ".$start_pos.",".$len;
			
			$tmp = DB::query($sql);
			$rows = array();
			while($r = DB::fetch($tmp)) {
				$rows[] = $r;
			}
			return $rows;
		}
		
	}
?>