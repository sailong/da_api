<?php
	class game_getter{
		
		
		
		public function get(){
			
			$game_list = array();
			$cmp = DB::table('common_member_profile');
			$cm = DB::table('common_member');
			$sql = "SELECT ".$cmp.".uid,".$cmp.".field1 FROM ".$cm.",".$cmp." WHERE ".$cm.".groupid = '25' AND ".$cm.".uid=".$cmp.".uid";
			$tmp = DB::query($sql);
			while($r = DB::fetch($tmp)){
				$game_list[] = array($r['uid'],$r['field1']);
			}
			
			return $game_list;
			
		}
		
	}
?>