<?php
	class jishigou_members_getter{
		public function get_record_by_uid($uid){
			
			$sql = "SELECT * FROM jishigou_members WHERE uid=".$uid;
			$tmp = DB::query($sql);
                        while($r = DB::fetch($tmp)){
                                $rows[] = $r;
                        }
                        return $rows[0];	
		}
		
	}
?>
