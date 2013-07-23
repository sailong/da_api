<?php
	class guanxi_updater{
		public function update_iscomp($compid,$userid,$iscomp){
			$data = array(
				'iscomp'=>$iscomp
			);
			$condition = array(
                                'compid'=>$compid,
				'userid'=>$userid
                        );

			DB::update('guanxi',$data,$condition);

		}
	}
?>
