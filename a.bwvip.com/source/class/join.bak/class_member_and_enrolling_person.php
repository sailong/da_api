<?php
	class member_and_enrolling_person{
		
		public function get_record_amount_by_condition(
			$volunteer_or_player,
			$trainer_joining_game,
			$referee_joining_game,
			$keyword,
			$start_time,
			$end_time,
			$area,
			$trainer_certificate,
			$recommendation_letter,
			$referee_certificate,
			$passing_or_not,
			$game_beginning_or_not,
			$paying_or_not,
			$comment_type
			){
				
				$cmp = DB::table('common_member_profile');
				$vap = DB::table('volunteers_and_players');
				
				if($volunteer_or_player != '全部'){
						$condition1 = $vap.".volunteer_or_player='".$volunteer_or_player."'"; 
				}else{
						$condition1 = "1=1";
				}
				
				if($trainer_joining_game==0){
					$condition2 = "1=1";
				}else{
					$condition2 = $vap.".trainer_joining_game=".$trainer_joining_game;
				}
				
				if($referee_joining_game==0){
					$condition3 = "1=1";
				}else{
					$condition3 = $vap.".referee_joining_game=".$referee_joining_game;
				}
				
				if($keyword==''){
					$condition4 = "1=1";
				}else{
					$condition4 = "(".$cmp.".realname='".$keyword."' OR ".$cmp.".telephone='".$keyword."' OR ".$cmp.".mobile='".$keyword."')";
				}
				
				if($start_time==''){
					$condition5 = "1=1";
				}else{
					$condition5 = $vap.".enrolling_time>='".$start_time."'";
				}
				
				if($end_time==''){
					$condition6 = "1=1";
				}else{
					$condition6 = $vap.".enrolling_time<='".$end_time."'";
				}
				
				if($area != '全部'){
				
					if($area=='华北地区'){
						$condition7 = "(".$vap.".game_area='".$area."' OR referee_judging_game_round1_in_north_china=1 OR referee_judging_game_round2_in_north_china=1)";
					}
				
					if($area=='华东地区'){
						$condition7 = "(".$vap.".game_area='".$area."' OR referee_judging_game_round1_in_east_china=1 OR referee_judging_game_round2_in_east_china=1)";
					}
					
					if($area=='华南地区'){
						$condition7 = "(".$vap.".game_area='".$area."' OR referee_judging_game_round1_in_south_china=1 OR referee_judging_game_round2_in_south_china=1)";
					}
					
					if($area=='中南地区'){
						$condition7 = "(".$vap.".game_area='".$area."' OR referee_judging_game_round1_in_south_central_china=1 OR referee_judging_game_round2_in_south_central_china=1)";
					}
					
				}else{
					$condition7 = "1=1";
				}
				
				if($trainer_certificate==0){
					$condition8 = "1=1";
				}else{
					$condition8 = $vap.".trainer_certificate_inexistence=".$trainer_certificate;
				}
				
				if($recommendation_letter==0){
					$condition9 = "1=1";
				}else{
					$condition9 = $vap.".recommendation_letter_inexistence=".$recommendation_letter;
				}
				
				if($referee_certificate==0){
					$condition10 = "1=1";
				}else{
					$condition10 = $vap.".referee_certificate_inexistence=".$referee_certificate;
				}
				
				
				if($passing_or_not != '全部'){
					$condition11 = "passing_or_not=".$passing_or_not;
				}else{
					$condition11 = "1=1";
				}
				
				if($game_beginning_or_not != '全部'){
					$condition12 = "game_beginning_or_not=".$game_beginning_or_not;
				}else{
					$condition12 = "1=1";
				}
				
				if($paying_or_not != '全部'){
					$condition13 = "paying_or_not=".$paying_or_not;
				}else{
					$condition13 = "1=1";
				}
				
				if($comment_type != '全部'){
					$c = DB::table('comments');
					$condition14 = $vap.".uid IN (SELECT uid FROM ".$c." WHERE type='".$comment_type."')";
				}else{
					$condition14 = "1=1";
				}
				
				$select = "SELECT COUNT(*)";
				$from = " FROM ".$cmp.",".$vap;
				$where = " WHERE ".$cmp.".uid=".$vap.".uid AND ".$vap.".accept_or_not=1";
				$where = $where." AND ".$condition1." AND ".$condition2." AND ".$condition3." AND ".$condition4." AND ".$condition5." AND ".$condition6." AND ".$condition7." AND ".$condition8." AND ".$condition9." AND ".$condition10." AND ".$condition11." AND ".$condition12." AND ".$condition13." AND ".$condition14;
				$sql = $select.$from.$where;
				
				//echo $sql;
				
				$tmp = DB::query($sql);
				$row =  DB::fetch($tmp);
				return $row['COUNT(*)'];
				
		}
		
		public function get_records(
			$start,
			$len,
			$volunteer_or_player,
			$trainer_joining_game,
			$referee_joining_game,
			$keyword,
			$start_time,
			$end_time,
			$area,
			$trainer_certificate,
			$recommendation_letter,
			$referee_certificate,
			$passing_or_not,
			$game_beginning_or_not,
			$paying_or_not,
			$comment_type
			){
				$cmp = DB::table('common_member_profile');
				$vap = DB::table('volunteers_and_players');
				
				if($volunteer_or_player != '全部'){
						$condition1 = $vap.".volunteer_or_player='".$volunteer_or_player."'"; 
				}else{
						$condition1 = "1=1";
				}
				
				if($trainer_joining_game==0){
					$condition2 = "1=1";
				}else{
					$condition2 = $vap.".trainer_joining_game=".$trainer_joining_game;
				}
				
				if($referee_joining_game==0){
					$condition3 = "1=1";
				}else{
					$condition3 = $vap.".referee_joining_game=".$referee_joining_game;
				}
				
				if($keyword==''){
					$condition4 = "1=1";
				}else{
					$condition4 = "(".$cmp.".realname='".$keyword."' OR ".$cmp.".telephone='".$keyword."' OR ".$cmp.".mobile='".$keyword."')";
				}
				
				if($start_time==''){
					$condition5 = "1=1";
				}else{
					$condition5 = $vap.".enrolling_time>='".$start_time."'";
				}
				
				if($end_time==''){
					$condition6 = "1=1";
				}else{
					$condition6 = $vap.".enrolling_time<='".$end_time."'";
				}
				
				if($area != '全部'){
				
					if($area=='华北地区'){
						$condition7 = "(".$vap.".game_area='".$area."' OR referee_judging_game_round1_in_north_china=1 OR referee_judging_game_round2_in_north_china=1)";
					}
				
					if($area=='华东地区'){
						$condition7 = "(".$vap.".game_area='".$area."' OR referee_judging_game_round1_in_east_china=1 OR referee_judging_game_round2_in_east_china=1)";
					}
					
					if($area=='华南地区'){
						$condition7 = "(".$vap.".game_area='".$area."' OR referee_judging_game_round1_in_south_china=1 OR referee_judging_game_round2_in_south_china=1)";
					}
					
					if($area=='中南地区'){
						$condition7 = "(".$vap.".game_area='".$area."' OR referee_judging_game_round1_in_south_central_china=1 OR referee_judging_game_round2_in_south_central_china=1)";
					}
					
				}else{
					$condition7 = "1=1";
				}
				
				if($trainer_certificate==0){
					$condition8 = "1=1";
				}else{
					$condition8 = $vap.".trainer_certificate_inexistence=".$trainer_certificate;
				}
				
				if($recommendation_letter==0){
					$condition9 = "1=1";
				}else{
					$condition9 = $vap.".recommendation_letter_inexistence=".$recommendation_letter;
				}
				
				if($referee_certificate==0){
					$condition10 = "1=1";
				}else{
					$condition10 = $vap.".referee_certificate_inexistence=".$referee_certificate;
				}
				
				if($passing_or_not != '全部'){
					$condition11 = "passing_or_not=".$passing_or_not;
				}else{
					$condition11 = "1=1";
				}
				
				if($game_beginning_or_not != '全部'){
					$condition12 = "game_beginning_or_not=".$game_beginning_or_not;
				}else{
					$condition12 = "1=1";
				}
				
				if($paying_or_not != '全部'){
					$condition13 = "paying_or_not=".$paying_or_not;
				}else{
					$condition13 = "1=1";
				}
				
				if($comment_type != '全部'){
					$c = DB::table('comments');
					$condition14 = $vap.".uid IN (SELECT uid FROM ".$c." WHERE type='".$comment_type."')";
				}else{
					$condition14 = "1=1";
				}
				
				$select = "SELECT ".$cmp.".uid,".$cmp.".realname,".$cmp.".telephone,".$cmp.".mobile,".$vap.".paying_or_not,".$vap.".passing_or_not,".$vap.".enrolling_time,".$vap.".volunteer_or_player,".$vap.".handicap,".$vap.".game_beginning_or_not";
				$from = " FROM ".$cmp.",".$vap;
				$where = " WHERE ".$cmp.".uid=".$vap.".uid AND ".$vap.".accept_or_not=1";
				
				$where = $where." AND ".$condition1." AND ".$condition2." AND ".$condition3." AND ".$condition4." AND ".$condition5." AND ".$condition6." AND ".$condition7." AND ".$condition8." AND ".$condition9." AND ".$condition10." AND ".$condition11." AND ".$condition12." AND ".$condition13." AND ".$condition14;
				$limit = " LIMIT ".$start.",".$len;
				$sql = $select.$from.$where.$limit;
				
				//echo $sql;
				
				$tmp = DB::query($sql);
				while($r = DB::fetch($tmp)){
					$rows[] = $r;
				}
				return $rows;
				
		}
		
	}
?>
