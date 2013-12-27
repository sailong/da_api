<?php
	class dz_order_info_getter{
		
		public function get_record_amount_by_user_id($user_id){
			$ecname = getecprefix();
			$sql = "SELECT COUNT(*) FROM ".$ecname."order_info WHERE user_id=".$user_id." AND order_status=1";
			$tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['COUNT(*)'];
		}

	}
?>
