<?php
	class game_player_counter{
		public function get_game_player_amount($group_id){
			$hsc = DB::table('home_saishi_csqy');
			$sql = "SELECT COUNT(id) FROM ".$hsc." WHERE groupid=".$group_id;
			$tmp = DB::query($sql);
			$r = DB::fetch($tmp);
			return $r['COUNT(id)'];
		} 
	}
?>