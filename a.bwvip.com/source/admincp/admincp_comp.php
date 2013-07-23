<?php
 if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
} 
cpheader();
echo "<div style='text-align:center;'>
<a href='?action=comp&operation=list'>分类管理</a>
<a href='?action=comp&operation=add'>添加分类</a> 
<a href='?action=comp&operation=list'>企业用户审核管理</a>
</div>
";
$operation = $operation ? $operation : 'list';
//默认列表页
if($operation == 'list') {
?>
<table width=100% border="0" cellpadding="1" cellspacing="1" style="background:#cdcdcd">
<tr>
<td bgcolor="#FFFFFF">机构名称</td>
<td bgcolor="#FFFFFF">分类描述</td>
<td bgcolor="#FFFFFF">模板文件夹</td>
<td bgcolor="#FFFFFF"> 用户组</td>
<td bgcolor="#FFFFFF">管理</td>
</tr>
<?php  
$query = DB::query('SELECT id, sortname, content,tbname FROM '.DB::table('Team_sort'));
			while($value=DB::fetch($query)) {
				?>
                <tr>
<td bgcolor="#FFFFFF"><?php echo $value['sortname'];?></td>
<td bgcolor="#FFFFFF"><?php echo $value['content'];?></td>
<td bgcolor="#FFFFFF">static/space/<?php echo $value['tbname'];?></td>
<td bgcolor="#FFFFFF"><?php echo $value['groupid'];?></td>
<td bgcolor="#FFFFFF"><a href='?action=comp&operation=edit&gid=<?php echo $value['id'];?>'>修改</a></td>
  </tr> 
		<?php	}
?>
</table>


<?php	
}elseif($operation == 'add') {

?>
<form name="form1" action="?action=comp&operation=addtb" method="post">
      <p> 
      </p>
      <p align="center">添加机构名称</p>
      <p align="center"> 
          机构名称:<input type="text" name=sortname id=sortname>  
      </p>
      <p align="center"> 
          分类简介:
            <textarea name="content" id="content"></textarea>  
      </p>
      <p align="center"> 
       <input type="hidden" name="act" id="act" value="hot"> 
      </p>
      <p align="center"><input type="submit" value="提交" name="submit"><input type="reset" value="重置" name="reset"> 
      </p>

</form>
       
<?php
//修改页面
}elseif($operation == 'edit') {
	$gid = empty($_GET['gid']) ? 0 : $_GET['gid'];
	if($gid>0){
  
	$query = DB::query('SELECT id, sortname, content,tbname FROM '.DB::table('Team_sort').' where id='.$gid);
 
			while($value=DB::fetch($query)) {
?>	 
<form name="form1" action="?action=comp&operation=eidttb" method="post">
    <p> 
      </p>
      <p align="center">修改机构  </p>
      <p align="center"> 
          机构名称:<input type="text" name=sortname id=sortname value="<?php echo $value['sortname'];?>">  
      </p>
      <!--<p align="center"> 
          模板名称:
            <input type="text" name=sortname id=sortname value="<?php echo $value['sortname'];?>">  
      </p>-->
      
      <p align="center"> 
          分类简介:
            <textarea name="content" id="content"  ><?php echo $value['content'];?></textarea>  
      </p>
      <p align="center"> 
       <input type="hidden" name="act" id="act" value="hot"> 
       
       <input type="hidden" name="gid" id="gid" value="<?php echo $value['id'];?>"> 
      </p>
      <p align="center"><input type="submit" value="提交" name="submit"><input type="reset" value="重置" name="reset"> 
      </p>

</form>
	 
<?php 
 }
}
//修改页面
}elseif($operation=='eidttb'){
		$gid = empty($_POST['gid']) ? 0 : $_POST['gid'];
if($gid>0){
			 $act=$_POST['act'];
			if($act=='hot')
			 { 
			 		DB::query("UPDATE ".DB::table('Team_sort')." SET sortname='$_POST[sortname]',content='$_POST[content]' WHERE id='$gid'"); 
		   header("Location:admin.php?action=comp&operation=list"); 

			 }
	     }
		 
//添加页面
 }elseif($operation=='addtb'){

$sortname=$_POST['sortname'];
$content=$_POST['content'];
$act=$_POST['act'];
if($act=='hot')
 { 
 
	$getid = DB::result_first("SELECT id FROM ".DB::table('Team_sort')." order by id desc")+1;
	$foldername='theme'.$getid; 
	/*生成数据记录DB::table('common_usergroup')  DB::table('common_usergroup_field')*/  
	$insert = "INSERT INTO ".DB::table('common_usergroup')." (type, system,grouptitle,allowvisit) VALUES ('special', 'private', '$sortname',1)";
	DB::query($insert); 	
	$id = DB::insert_id();
	$insert="INSERT INTO ".DB::table('common_usergroup_field')." (groupid,readaccess, allowpost,allowreply) VALUES ('$id','1', '0', '0')";
	DB::query($insert); 	 
	
	 
	//插入数据表pre_Team_sort 
	$insert = "INSERT INTO ".DB::table('Team_sort')." (sortname, content,tbname,groupid,theme) VALUES ('$sortname','$content','$foldername','$id','$foldername');";
	 DB::query($insert); 
	 
	/*增加文件夹static/$sortid/*/
	$choices = DB::result_first("SELECT choices FROM ".DB::table('common_member_profile_setting')." WHERE fieldid='jigouleibie'");
	$choices=$choices.chr(10).$id.'-'.$sortname;
	DB::query("UPDATE ".DB::table('common_member_profile_setting')." SET choices='$choices' WHERE fieldid='jigouleibie'"); 	 
		//mkdir('static/space/'.$foldername,0755);
	//xCopy('static/space/t1','static/space/'.$foldername,1);
	 
	header("Location:admin.php?action=comp&operation=list"); 
 }	
}

 
function xCopy($source, $destination, $child){   
//用法：   
// xCopy("feiy","feiy2",1):拷贝feiy下的文件到 feiy2,包括子目录   
// xCopy("feiy","feiy2",0):拷贝feiy下的文件到 feiy2,不包括子目录   
//参数说明：   
// $source:源目录名   
// $destination:目的目录名   
// $child:复制时，是不是包含的子目录   
if(!is_dir($source)){   
echo("注意: $source 不是文件夹!");   
return 0;   
}   
if(!is_dir($destination)){   
mkdir($destination,0777);   
}   
 
$handle=dir($source);   
while($entry=$handle->read()) {   
if(($entry!=".")&&($entry!="..")){   
if(is_dir($source."/".$entry)){   
if($child)   
xCopy($source."/".$entry,$destination."/".$entry,$child);   
}   
else{   
copy($source."/".$entry,$destination."/".$entry);   
}   
 
}   
}   
 
return 1;   
} 

$getfield = array();
$field = DB::result_first("SELECT field FROM ".DB::table('common_member_verify_info')." WHERE uid='2'");	
$getfield = unserialize($field);
$text=$getfield['jigouleibie'];  
$getgroupid = explode("-", $text); 
$getgroupid[0];
$choices = DB::result_first("SELECT choices FROM ".DB::table('common_member_profile_setting')." where fieldid='jigouleibie'");
 
print_r($choices);


?>