<?php
	
	class guess_activity_adder{
		
		public function add($name,$group_id,$public_or_not,$start_time,$end_time,$pic_name,$sorting_num,$desc1,$desc2){
			
			$dga = DB::table('daz_guess_activities');
			$sql = "INSERT INTO ".$dga."(name,group_id,publish_or_not,start_time,end_time,pic_name,sorting_num,desc1,desc2) VALUES('".$name."',".$group_id.",'".$public_or_not."','".$start_time."','".$end_time."','".$pic_name."',".$sorting_num.",'".$desc1."','".$desc2."')";
			
			DB::query($sql);
			$guess_activity_id = DB::insert_id();
			
			return $guess_activity_id;
		}
		
	}
	
?>