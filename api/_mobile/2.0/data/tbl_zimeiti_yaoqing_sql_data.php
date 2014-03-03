<?php
$sql=" insert into tbl_zimeiti_yaoqing (`uid`,`to_uid`,`field_uid`,`mobile`,`guanxi`,`zimeiti_yaoqing_status`,`zimeiti_yaoqing_addtime`) values ('".$uid."','".$to_uid."','".$_G['gp_field_uid']."','".$mobile."','".urldecode($_G['gp_guanxi'])."','0','".time()."') ";
return $sql;
?>