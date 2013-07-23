<?php
	class volunteers_and_players_getter{
		public function get_record_by_uid($uid){
			$vap = DB::table('volunteers_and_players');
			$sql = "SELECT * FROM ".$vap." WHERE uid=".$uid;
			$tmp = DB::query($sql);
                        while($r = DB::fetch($tmp)){
                                $rows[] = $r;
                        }
                        return $rows[0];
		}
	}
?>
