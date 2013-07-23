<?php
	class checkbox_component_getter{
		public function get($name,$is_checked){
			if($is_checked == true){
				$checkbox_component = '<input name="'.$name.'" type="checkbox" checked="checked"/>';
			}elseif($is_checked == false){
				$checkbox_component = '<input name="'.$name.'" type="checkbox"/>';
			}
			return $checkbox_component;
		}
	}
?>