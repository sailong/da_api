<?php
	class select_component_getter{
		
		public function get($name,$selected_value,$all_value){
			
			for($i=0;$i<count($all_value);$i++){
				if($selected_value==$all_value[$i]){
					break;
				}
			}
			$selected_index = $i;
			$select_component = '<select name="'.$name.'">';
			$select_component = $select_component.'<option value="'.$all_value[$selected_index].'">'.$all_value[$selected_index].'</option>';
			
			for($i=0;$i<count($all_value);$i++){
				if($all_value[$i]!=$all_value[$selected_index]){
					$select_component = $select_component.'<option value="'.$all_value[$i].'">'.$all_value[$i].'</option>';
				}
			}
			$select_component = $select_component.'</select>';
			return $select_component;
		}
		
	}
?>