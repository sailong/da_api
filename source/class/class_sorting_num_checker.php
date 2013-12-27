<?php
	class sorting_num_checker{
		public function check($input){
			
			if($input==''){
				return "排序数字必须填写";
			}
			
			$p = "^[0-9]{1,3}$";
			
			if(!ereg($p,$input)){
				return "排序数字错误";
			}
			
			if(!($input>=0&&$input<=100)){
				return "排序数字错误";
			}
			
			return true;
		}
	}
?>