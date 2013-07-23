<?php
	class guess_type_max_player_counter{
		public function get_guess_type_max_player_amount($id_str){
			$dgt = DB::table('daz_guessing_types');
			$sql = "SELECT MAX(end_pos) FROM ".$dgt." WHERE id IN (".$id_str.")";
			
			
			$tmp = DB::query($sql);
			$r = DB::fetch($tmp);
			return $r['MAX(end_pos)'];
			
		}
	}
?>