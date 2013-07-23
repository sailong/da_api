<?php
	class common_score_getter{
		
		public function get_record_amount_by_uid_and_sais_id($uid,$sais_id){
			$cs = DB::table('common_score');
                        $sql = "SELECT COUNT(*) FROM ".$cs." WHERE uid=".$uid." AND sais_id=".$sais_id;
                        $tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['COUNT(*)'];
		}

	}
?>
