<?php
	class volunteers_getter{
		public function get_record_amount_by_uid($uid){
			$v = DB::table('volunteers_and_players');
			$sql = "SELECT COUNT(*) FROM ".$v." WHERE uid=".$uid." AND volunteer_or_player='志愿者'";
			$tmp = DB::query($sql);
			$row =  DB::fetch($tmp);
			return $row['COUNT(*)'];		
		}

		public function get_record_by_uid($uid){
			$v = DB::table('volunteers_and_players');
                        $sql = "SELECT * FROM ".$v." WHERE uid=".$uid." AND volunteer_or_player='志愿者'";
			//var_dump($sql);
                        $tmp = DB::query($sql);
			while($r = DB::fetch($tmp)){
                                $rows[] = $r;
                        }
                        return $rows[0];		
		}

	}
?>
