<?php
	class guess_type_getter{
		
		public function get(){
			
			$guess_type_list = array();
			$dgt = DB::table('daz_guessing_types');
			$sql = "SELECT id,name FROM ".$dgt;
			$tmp = DB::query($sql);
			while($r = DB::fetch($tmp)){
				$guess_type_list[] = array($r['id'],$r['name']);
			}
			
			return $guess_type_list;
			
		}
		
		public function get_info_by_id_str($id_str){
			$dgt = DB::table('daz_guessing_types');
			$sql = "SELECT * FROM ".$dgt." WHERE id IN(".$id_str.")";
			$tmp = DB::query($sql);
			$rows = array();
			while($r = DB::fetch($tmp)){
				$rows[] = $r;
			}
			return $rows;
		}
		
	}
?>