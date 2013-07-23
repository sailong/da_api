<?php
	class players_getter{

		public function get_record_amount_by_uid($uid){
			$p = DB::table('volunteers_and_players');
			$sql = "SELECT COUNT(*) FROM ".$p." WHERE uid=".$uid." AND volunteer_or_player='球员'";
			$tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['COUNT(*)'];
		}

		public function get_record_by_uid($uid){
			$p = DB::table('volunteers_and_players');
			$sql = "SELECT * FROM ".$p." WHERE uid=".$uid." AND volunteer_or_player='球员'";
                        $tmp = DB::query($sql);
			while($r = DB::fetch($tmp)){
                                $rows[] = $r;
                        }
                        return $rows[0];
		}
	}
?>
