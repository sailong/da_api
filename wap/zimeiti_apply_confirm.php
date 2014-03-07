<? 
function get_device_type(){
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$type = 'other';
	if(strpos($agent, 'iphone') || strpos($agent, 'ipad')){
		$type = 'ios';
	}
	if(strpos($agent, 'android')){
		$type = 'android';
	}
	return $type;
}
 	 
$width = $_GET ['width'];
if(!$width)
{
	$width=460;
}
 if(get_device_type()=='ios'){
	$width=320;
} 

$zimeiti_apply_id = $_GET ['zimeiti_apply_id'];
$uid = $_GET ['uid'];
$mobile = $_GET ['mobile'];
 
 //字体缩放
$ziti=14/460;
$fonts=$ziti*$width;
$ziti=16/460;
$fonts1=$ziti*$width;

$input=450/460;
$inputc=$input*$width;

?>

<html xmlns="http://www.w3.org/1999/xhtml"><style type="text/css">
.centertl {
	font-size: <?php echo $fonts;?>px;
	font-weight: 600;
}
.tptitle {
	font-size: <?php echo $fonts;?>px;
	font-weight: 600;
	color: #999;
}
.tptitle1 {
	font-size: <?php echo $fonts1;?>px;
	font-weight: 600;
	color: #999;
	padding-left:20px;
}
p {
	font-size: <?php echo $fonts;?>px; 
	color: #000;
}
h3 {
	font-size: <?php echo $fonts;?>px; 
	font-weight: 600;
	color: #000;
}
.inputc{width:<?php echo $inputc;?>px;}
</style> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  

<SCRIPT type=text/javascript src="images/jquery.js"></SCRIPT>

<title></title> 
  
</head> 
<body >
<?php 
if($_GET['step']==1){
	
	define('APPTYPEID', 0);
	define('CURSCRIPT', 'member');
	require '../source/class/class_core.php';
	$discuz = & discuz_core::instance();
	$discuz->init();
	
	$zimeiti_apply_id = $_POST ['zimeiti_apply_id'];
	//$uid = $_POST ['uid'];
	//$mobile = $_POST ['mobile'];
	$zimeiti_recommend_status = $_POST ['zimeiti_recommend_status'];
	$sql="update tbl_zimeiti_apply set zimeiti_recommend_status='".$zimeiti_recommend_status."',zimeiti_apply_role='1' where zimeiti_apply_id='".$zimeiti_apply_id."'";
	DB::query($sql);
 
?>
<div align="center" class="inputc">
	操作成功！
</div>
<?php 
}else{
?>
<form method="post" id="act_form" name="act_form" action="http://wap.bwvip.com/zimeiti_apply_confirm.php?step=1" enctype="multipart/form-data">
<div align="center" class="inputc">
	<input type="hidden" name="zimeiti_apply_id" value="<?php echo $zimeiti_apply_id; ?>"/>
	<input type="hidden" name="uid" value="<?php echo $uid; ?>">
	<input type="hidden" name="mobile" value="<?php echo $mobile; ?>">
	<input type="hidden" name="zimeiti_recommend_status" id="zimeiti_recommend_status" value="1">
	<div><input type="button" value="确定" onclick="act_form_submit1();"/> <input type="button" value="拒绝" onclick="act_form_submit2();"/></div>
</div>
</form>
<script>
function act_form_submit1(){
document.getElementById("act_form").submit();
}
function act_form_submit2(){
document.getElementById("zimeiti_recommend_status").value="2"
document.getElementById("act_form").submit();

}
</script>
<?php 
}
?>
</body>