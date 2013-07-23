<?php
	class badges_related_to_page_getter{
		
		public function get_record_amount_by_uid($uid){
			
			$brtp = DB::table('badges_related_to_page');
			$sql = "SELECT COUNT(*) FROM ".$brtp." WHERE uid=".$uid;
			$tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['COUNT(*)'];

		}

		public function get_record_by_uid($uid){
			$brtp = DB::table('badges_related_to_page');
                        $sql = "SELECT * FROM ".$brtp." WHERE uid=".$uid;
			$tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
			return $row;
		}

	}
?>
