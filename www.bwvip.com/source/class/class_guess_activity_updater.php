<?php
	class guess_activity_updater{
		public function update($id,$name,$public_or_not,$group_id,$start_time,$end_time,$pic_name,$sorting_num,$desc1,$desc2){
		
			$gag = new guess_activity_getter();
			$r = $gag->get_info_by_id($id);
			//$r['pic_name']
			
			$dga = DB::table('daz_guess_activities');
			
			if($r['pic_name']!=''&&$pic_name==''){
				$sql = "UPDATE ".$dga." SET name='".$name."',publish_or_not='".$public_or_not."',group_id=".$group_id.",start_time='".$start_time."',end_time='".$end_time."',sorting_num=".$sorting_num.",desc1='".$desc1."',desc2='".$desc2."' WHERE id=".$id;
			}else{
				$sql = "UPDATE ".$dga." SET name='".$name."',publish_or_not='".$public_or_not."',group_id=".$group_id.",start_time='".$start_time."',end_time='".$end_time."',pic_name='".$pic_name."',sorting_num=".$sorting_num.",desc1='".$desc1."',desc2='".$desc2."' WHERE id=".$id;
			}
			
			DB::query($sql);
		
		}
		
		public function update_result_address($id,$result_address){
			
			$dga = DB::table('daz_guess_activities');
			$sql = "UPDATE ".$dga." SET result_address='".$result_address."' WHERE id=".$id;
			DB::query($sql);
		}
		
	}
?>