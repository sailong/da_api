<?php
	class judges_and_trainers_getter{
		
		public function get_record_amount_by_judge_uid_and_trainer_uid($judge_uid,$trainer_uid){
			$jat = DB::table('judges_and_trainers');
			$sql = "SELECT COUNT(*) FROM ".$jat." WHERE judge_uid=".$judge_uid." AND trainer_uid=".$trainer_uid;
			$tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['COUNT(*)'];
		}

		public function get_record_amount_by_trainer_uid($trainer_uid){
			$jat = DB::table('judges_and_trainers');
			$sql = "SELECT COUNT(*) FROM ".$jat." WHERE trainer_uid=".$trainer_uid;
			$tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['COUNT(*)'];
		}

		public function get_avg_score_by_trainer_uid($trainer_uid){
			$jat = DB::table('judges_and_trainers');
                        $sql = "SELECT AVG(score) FROM ".$jat." WHERE trainer_uid=".$trainer_uid;
                        $tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
			return $row['AVG(score)'];
		}

	}
?>
