<?php
$sql=" insert into tbl_zimeiti_yaoqing (`uid`,'to_uid',`field_uid`,`mobile`,`guanxi`,`zimeiti_apply_status`,`zimeiti_apply_addtime`) values ('".$uid."','".$to_uid."','".$_G['gp_field_uid']."','".$_G['gp_mobile']."','".$_G['gp_guanxi']."','0','".time()."') ";
return $sql;
?>