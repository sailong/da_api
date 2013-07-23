<?php
	class users_badges_getter{
		
		public function get_record_amount_by_uid_and_badge_type($uid,$badge_type){
			$ub = DB::table('users_badges');
			$sql = "SELECT COUNT(*) FROM ".$ub." WHERE uid=".$uid." AND badge_type='".$badge_type."'";
			$tmp = DB::query($sql);
			$row =  DB::fetch($tmp);
			return $row['COUNT(*)'];
		}
		
		public function get_record_amount(){
			$ub = DB::table('users_badges');
			$sql = "SELECT COUNT(*) FROM ".$ub;
			$tmp = DB::query($sql);
			$row =  DB::fetch($tmp);
			return $row['COUNT(*)'];
		}
		
		public function get_record($start,$len_per_page){
			$ub = DB::table('users_badges');
			$cmp = DB::table('common_member_profile');
			$sql = "SELECT * FROM ".$ub.",".$cmp." WHERE ".$ub.".uid=".$cmp.".uid AND ".$ub.".automatic_verifying=0 LIMIT ".$start.",".$len_per_page;
			$tmp = DB::query($sql);
			while($r = DB::fetch($tmp)){
				$rows[] = $r;
			}
			return $rows;		
			
		}

		public function get_record_amount_by_uid_and_badge_type_and_getting_badge_or_not($uid,$badge_type,$getting_badge_or_not){
			$ub = DB::table('users_badges');
			$sql = "SELECT COUNT(*) FROM ".$ub." WHERE uid=".$uid." AND badge_type='".$badge_type."' AND getting_badge_or_not=".$getting_badge_or_not;
			$tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['COUNT(*)'];
		}
	
		public function get_record_by_uid_and_getting_badge_or_not($uid,$getting_badge_or_not){
			
			$ub = DB::table('users_badges');
			$sql = "SELECT * FROM ".$ub." WHERE uid=".$uid." AND getting_badge_or_not=".$getting_badge_or_not;
			$tmp = DB::query($sql);
                        while($r = DB::fetch($tmp)){
                                $rows[] = $r;
                        }
                        return $rows;

		}
	
		public function get_record_by_uid_and_badge_type($uid,$badge_type){
			
			$ub = DB::table('users_badges');
			$sql = "SELECT * FROM  ".$ub." WHERE uid=".$uid." AND badge_type='".$badge_type."'";
			$tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
			return $row;

		}

		public function get_automatic_verifying_record_amount(){
	
			$ub = DB::table('users_badges');
			$sql = "SELECT COUNT(*) FROM ".$ub." WHERE automatic_verifying=0";
			$tmp = DB::query($sql);
			$row =  DB::fetch($tmp);
			return $row['COUNT(*)'];
		
		}	

		

	}
?>
