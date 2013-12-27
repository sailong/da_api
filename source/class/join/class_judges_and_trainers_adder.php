<?php
	class judges_and_trainers_adder{
		public function add($judge_uid,$trainer_uid,$score,$comment){
			
			$data = array(
				'judge_uid'=>$judge_uid,
				'trainer_uid'=>$trainer_uid,
				'score'=>$score,
				'comment'=>$comment
			);

			DB::insert('judges_and_trainers',$data);

		}
	}
?>
