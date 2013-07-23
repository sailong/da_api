<?php
	class user_applying_badge_infos_getter{
		
		public function get_record_amount_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name){
			$uabi = DB::table('user_applying_badge_infos');
			$sql = "SELECT COUNT(*) FROM ".$uabi." WHERE uid=".$uid." AND badge_id=".$badge_id." AND tag_name='".$tag_name."'";
			$tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['COUNT(*)'];
		}

		public function get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name){
			$uabi = DB::table('user_applying_badge_infos');
                        $sql = "SELECT * FROM ".$uabi." WHERE uid=".$uid." AND badge_id=".$badge_id." AND tag_name='".$tag_name."'";
                        $tmp = DB::query($sql);
                        $row = DB::fetch($tmp);
                        return $row;
		}

	}
?>
