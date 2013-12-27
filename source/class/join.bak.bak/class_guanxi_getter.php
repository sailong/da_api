<?php
	class guanxi_getter{
		public function get_record_amount_by_trainer_uid($uid){
			$g = DB::table('guanxi');
			$sql = "SELECT COUNT(*) FROM ".$g." WHERE compid=1899467 AND userid=".$uid." AND iscomp=1";
			$tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['COUNT(*)'];
		}

		public function get_record_amount($compid,$userid){
			$g = DB::table('guanxi');
			$sql = "SELECT COUNT(*) FROM ".$g." WHERE compid=".$compid." AND userid=".$userid;
			$tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['COUNT(*)'];
		}

	}
?>
