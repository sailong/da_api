<?php
	class jishigou_topic_getter{
		
		public function get_record_by_uid($uid){

                        $sql = "SELECT COUNT(*) FROM jishigou_topic WHERE uid=".$uid;
                        $tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['COUNT(*)'];

                }

	}
?>
