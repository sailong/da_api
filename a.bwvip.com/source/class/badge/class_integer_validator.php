<?php
	class integer_validator{
		public function validate($input){
			$pattern = "^[1-9][0-9]*$";
			if(ereg($pattern,$input) == false){
				return false;
			}else{
				return true;
			}
		}
	}
?>
