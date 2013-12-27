<?php
	class deleting_type_id_set_getter{
		
		public function get($original_type_id_set,$current_type_id_set){
			$result = array();
			for($i=0;$i<count($original_type_id_set);$i++){
				if(!in_array($original_type_id_set[$i],$current_type_id_set)){
					$result[] = $original_type_id_set[$i];
				}
			}
			return $result;
		}
		
	}
?>