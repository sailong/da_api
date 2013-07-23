<?php
	class str_len_checker{
		
		public function check($input,$min_len,$max_len,$extra_str){
			
			if($min_len==0){
				
				if(mb_strlen($input,"UTF-8")>$max_len){
					//return $extra_str."最多输入".$max_len."个字符";
					return $extra_str."的长度太长了";
				}
				
			}elseif( $min_len > 0 ){
				
				if(mb_strlen($input,"UTF-8")<$min_len){
					//return $extra_str."最少输入".$min_len."个字符";
					return $extra_str."的长度太短了";
				}
				
				if(mb_strlen($input,"UTF-8")>$max_len){
					//return $extra_str."最多输入".$max_len."个字符";
					return $extra_str."的长度太长了";
				}
				
			}
			
			return true;
			
		}
		
	}
?>