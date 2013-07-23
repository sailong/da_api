<?php

   $daz_weibo_mod= !empty($_GET['daz_weibo_mod']) ?  $_GET['daz_weibo_mod'] : 'admin';
   $daz_weibo_uid= !empty($_GET['daz_weibo_uid']) ?  $_GET['daz_weibo_uid'] : '1';

   include("./index.php");

   foreach($weibo_new_list['list'] as $value){
		echo $value['content'].'<br>';
        foreach($value['image_list'] as $img){
			echo "<img src=\"".$img['image_small']."\"/>";
		}
		 exit;
	}


?>