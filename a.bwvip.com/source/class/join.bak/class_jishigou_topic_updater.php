<?php
	class jishigou_topic_updater{
		
		public function update_by_uid($uid,$from,$item){
			
			$sql = "UPDATE jishigou_topic SET `from`='web',`item`='' WHERE uid=".$uid." AND `from`='api' AND `item`='api'";
			$tmp = DB::query($sql);
			var_dump($tmp);			

		}

	}
?>
