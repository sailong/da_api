<?php
	class guess_activity_deleter{
		public function delete($id_str){
			$dga = DB::table('daz_guess_activities');
			$sql = "DELETE FROM ".$dga." WHERE id IN (".$id_str.")";
			DB::query($sql);
		}
	}
?>