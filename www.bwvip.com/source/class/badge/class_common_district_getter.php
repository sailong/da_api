<?php
	class common_district_getter{
		
		public function get_name_by_id($id){
			$cd = DB::table('common_district');
			$sql = "SELECT name FROM ".$cd." WHERE id=".$id;
			$tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['name'];
		}

	}
?>
