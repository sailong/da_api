<?php
	class date_time_checker{
		public function check($date_time){
			
			$p = "(^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}$)|(^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$)";
			if(!ereg($p,$date_time)){
				return '时间格式错误';
			}
			
			$tmp = explode(" ",$date_time);
			$date = $tmp[0];
			$time = $tmp[1];
			
			$tmp = explode("-",$date);
			$year = $tmp[0];
			$month = $tmp[1];
			$day = $tmp[2];
			
			if(!checkdate($month,$day,$year)){
				return '日期错误';
			}
			
			$tmp = explode(":",$time);
			$hour = $tmp[0];
			$min = $tmp[1];
			
			if( !($hour>=0 && $hour<=23 && $min>=0 && $min<=59) ){
				return '时间错误';
			}
			
			return true;
			
		}
	}
?>