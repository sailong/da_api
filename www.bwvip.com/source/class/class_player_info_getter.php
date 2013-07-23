<?php
	class player_info_getter{
		public function get_player_info_by_group_id($group_id){
			$phsc = DB::table('home_saishi_csqy');
			$sql = "SELECT userid FROM ".$phsc." WHERE groupid=".$group_id." ORDER BY seq";
			$tmp = DB::query($sql);
			$user_ids = array();
			while($r = DB::fetch($tmp)){
				$user_ids[] = $r['userid'];
			}
			
			$rows = array();
			for($i=0;$i<count($user_ids);$i++){
				$cmp = DB::table('common_member_profile');
				$sql = "SELECT * FROM ".$cmp." WHERE uid=".$user_ids[$i];
				$tmp = DB::query($sql);
				$r = DB::fetch($tmp);
				$rows[] = array($r['uid'],$r['realname'],'uc_server/avatar.php?uid='.$r['uid'].'&size=middle');
			}
			return $rows;
		}
	}
?>