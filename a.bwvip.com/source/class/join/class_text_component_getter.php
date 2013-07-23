<?php
	class text_component_getter{
		public function get($name,$value,$onclick=''){
			if($onclick == ''){
				return '<input name="'.$name.'" value="'.$value.'"/>';
			}else{
				return '<input name="'.$name.'" value="'.$value.'" onclick="'.$onclick.'"/>';
			}
		}
	}
?>