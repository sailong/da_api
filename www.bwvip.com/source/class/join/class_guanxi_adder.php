<?php
	class guanxi_adder{
		public function add($compid,$userid,$iscomp){
			
			$data = array(
				'compid'=>$compid,
				'userid'=>$userid,
				'iscomp'=>$iscomp
			);

			DB::insert('guanxi',$data);

		}
	}
?>
