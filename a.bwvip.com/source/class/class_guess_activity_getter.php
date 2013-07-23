<?php
	class guess_activity_getter{
		
		public function name_exist_or_not($name){
			
			
			$dga = DB::table('daz_guess_activities');
			$sql = "SELECT COUNT(name) FROM ".$dga." WHERE name='".$name."'";
			$tmp = DB::query($sql);
			$r = DB::fetch($tmp);
			return $r['COUNT(name)'];
			
		}
		
		public function get_guess_activity_amount(){
			
			$query_num = DB::query("SELECT `id` FROM ". DB::table('daz_guess_activities')." " .$where. " " );
			return DB::num_rows($query_num);
			
		}
		
		public function get_published_guess_activity_amount(){
			$dga = DB::table('daz_guess_activities');
			$sql = "SELECT COUNT(id) FROM ".$dga." WHERE publish_or_not='是'";
			
			$tmp = DB::query($sql);
			$r = DB::fetch($tmp);
			return $r['COUNT(id)'];
		}
		
		
		
		public function get_info_perpage($where,$start_pos,$len){
			
			$dga = DB::table('daz_guess_activities');
			$sql = "SELECT * FROM ".$dga." ".$where." ORDER BY id DESC LIMIT ".$start_pos.",".$len;
			$tmp = DB::query($sql);
		
			$rows = array();
			
			while($r = DB::fetch($tmp)) {
				$rows[] = $r;
				
			}
			
			return $rows;
			
		}
		
		public function get_info_by_id($id){
			$dga = DB::table('daz_guess_activities');
			$sql = "SELECT * FROM ".$dga." WHERE id=".$id;
			$tmp = DB::query($sql);
			return DB::fetch($tmp);
		}
		
	}
?>