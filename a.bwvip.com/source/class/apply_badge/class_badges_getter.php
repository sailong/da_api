<?php
	class badges_getter{
		public function get_record_amount_by_id($id){
			$b = DB::table('badges');
			$sql = "SELECT COUNT(*) FROM ".$b." WHERE id=".$id;
                        $tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['COUNT(*)'];
		}
	}
?>
