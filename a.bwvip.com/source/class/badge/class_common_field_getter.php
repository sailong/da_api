<?php
	class common_field_getter{
		
		public function get_fieldname_by_uid($uid){
			$cfg = DB::table('common_field');
			$sql = "SELECT fieldname FROM ".$cfg." WHERE uid=".$uid;
			$tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['fieldname'];
		}

		public function get_fieldname_by_id($id){
			$cfg = DB::table('common_field');
			$sql = "SELECT fieldname FROM ".$cfg." WHERE id=".$id;
			$tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['fieldname'];
		}

	}
?>
