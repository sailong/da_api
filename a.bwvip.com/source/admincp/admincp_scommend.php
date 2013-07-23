<?php
 if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
} 
cpheader();

shownav('dazheng', '分站信息管理');
{  

//添加顶部菜单xyx
$doo=empty($do)?'adlist':$do;

$navmenu[] = array('分站信息列表', 'scommend&operation=scommend&do=adlist', $doo == 'adlist');
$navmenu[] = array('添加分站信息', 'scommend&operation=scommend&do=addscommend', $doo == 'addscommend');
$navmenu[] = array('分类管理', 'scommend&operation=scommend&do=tylist', $doo == 'tylist');
$navmenu[] = array('添加分类', 'scommend&operation=scommend&do=addtype', $doo == 'addtype');
showsubmenu("分站信息管理",$navmenu);
 
	if($_GET['do'] == 'del') {
		$id = $_GET['id'];
		
		$imglogo = DB::result_first("select imglogo from ".DB::table('home_scommend')."  where id='".$id."' ");
		if(file_exists($imglogo))unlink($imglogo);
		
		$row = DB::query("delete from ".DB::table('home_scommend')." where id=$id");
			if($row) {
				cpmsg('删除成功', 'action=scommend&operation=scommend&do=adlist');
			} else {
				cpmsg('删除失败', 'action=scommend&operation=scommend&do=adlist');
			}	

	} elseif($_GET['do'] == 'addscommend') {
		$query = DB::query("select * from ".DB::table('home_scommendtype'));
		$option = "<option value='0'>选择</option>";
		while($row = DB::fetch($query)) {
			$option .= "<option value='".$row['tid']."'>".$row['tname']."</option>";
		}
		echo "<form action='".ADMINSCRIPT."?action=scommend&operation=scommend&do=save' method='post' enctype='multipart/form-data'><table width='96%; style='border:1px solid gray'><tr style='height:30px'><td style='width:10%'>分站信息名称</td><td><input type='text' name='scommendname' style='width:300px' maxlength='20' /></td></tr><tr><td>链接地址</td><td><input type='text' name='scommendurl' style='width:300px' maxlength='100' /></td></tr><tr><td>分站信息分类</td><td><select name='scommendtype' style='widht:160px'>".$option."</select></td></tr><tr><td>分站信息描述</td><td><input type='text' name='content' style='width:300px; height:80px' maxlength='300' /></td></tr><tr><td>分站信息地址</td><td><input type='file' name='imglogo' style='width:300px; border:1px solid gray' maxlength='100' /></td></tr><tr><td colspan='2' style='text-align:left; padding-left:200px; padding-top:20px'><input type='submit' name='submit' value='提交' style='border:1px solid gray' /></td></tr></table></form>";
	} elseif($_GET['do'] == 'save') {
		$arr = $_POST;
		$arr['dateline'] = time();
		unset($arr['submit']);
		$file = $_FILES;
		$time = date('Ym');
		$dir = 'uploadfile/scommend/'.$time;
		if(!is_dir($dir)) {
			mkdir($dir, 0777);
		}
		chmod($dir, 0777);
		$str = '0123456789abcdefghijklmnopqrstuvwxyz';
		for($i = 1; $i <= 20; $i++) {
			$max = strlen($str);
			$rand = rand(0, $max);
			$num .= substr($str, $rand, '1');
		}
		$tmp_name = $file['imglogo']['tmp_name'];
		$dir = $dir.'/'.$num.'.jpg';
		$move = move_uploaded_file($tmp_name, $dir);
		if(empty($_GET['c'])) {
			$arr['imglogo'] = $dir;
			$row = DB::insert('home_scommend', $arr);
			if($row) {
				cpmsg('添加成功', 'action=scommend&operation=scommend&do=adlist');
			} else {
				unlink($dir);
				cpmsg('添加失败', 'action=scommend&operation=scommend&do=adlist');
			}
		} else {
			unset($arr['scommendid']);
			$arr['imglogo'] = $move ? $dir : ($_POST['imgpath'] ? $_POST['imgpath'] : '');
			unset($arr['imgpath']);
			$row = DB::update('home_scommend', $arr, array('id'=>$_POST['scommendid']));
			if($row) {
				cpmsg('修改成功', 'action=scommend&operation=scommend&do=addscommend');
			} else {
				cpmsg('修改失败', 'action=scommend&operation=scommend&do=addscommend');
			}
		}
	} elseif($_GET['do'] == 'addtype') {
		echo "<form action='".ADMINSCRIPT."?action=scommend&operation=scommend&do=type' method='post' enctype='multipart/form-data'><table width='96%; style='border:1px solid gray'><tr style='height:30px'><td style='width:10%'>分类名称</td><td><input type='text' name='tname' style='width:300px' maxlength='20' /></td></tr><tr><td colspan='2' style='text-align:left; padding-left:200px; padding-top:20px'><input type='submit' name='submit' value='提交' style='border:1px solid gray' /></td></tr></table></form>";
	} elseif($_GET['do'] == 'type') {
		$arr['tname'] = $_POST['tname'];
		$arr['dateline'] = time();
		$row = DB::insert('home_scommendtype', $arr);
		if($row) {
			cpmsg('添加成功', 'action=scommend&operation=scommend&do=tylist');
		} else {
			cpmsg('添加失败', 'action=scommend&operation=scommend&do=tylist');
		}
	}elseif($_GET['do'] == 'editype') {
		 $tname = $_POST['tname'];
		 $gid = $_POST['gid'];
		 $turl='action=scommend&operation=scommend&do=tylist';
		 $flag=DB::update("home_scommendtype",array("tname"=>$tname),array("tid"=>$gid));
        if($flag){
            cpmsg("分类修改成功",$turl);
        }else{
            cpmsg("分类修改失败",$turl);
        } 
	}	 elseif($_GET['do'] == 'del') {
		$id = $_GET['id'];
		$arr = DB::fetch_first("select * from ".DB::table('home_scommend')." where id=".$id);
		$row = DB::query("delete from ".DB::table('home_scommend')." where id=".$id);
		if($row) {
			unlink($arr['imglogo']);
			cpmsg('删除成功', 'action=scommend&operation=scommend');
		} else {
			cpmsg('删除失败', 'action=scommend&operation=scommend');
		}
	} elseif($_GET['do'] == 'edit') {
		$id = $_GET['id'];
		$scommend = DB::fetch_first("select * from ".DB::table('home_scommend')." where id=$id order by id desc");
		$query = DB::query("select * from ".DB::table('home_scommendtype')." order by tid desc");
		$option = "<option value='0'>选择</option>";
		while($row = DB::fetch($query)) {
			if($row['tid'] == $scommend['scommendtype']) {
				$option .= "<option value='".$row['tid']."' selected>".$row['tname']."</option>";
			} else {
				$option .= "<option value='".$row['tid']."'>".$row['tname']."</option>";
			}
		} 
		
		if(file_exists($scommend['imglogo'])){
					$strimg="<img src=".$scommend['imglogo']." width='300' height='200' />";
				}else{
					$strimg="无图";
				}
		echo "<form action='".ADMINSCRIPT."?action=scommend&operation=scommend&do=save&c=1' method='post' enctype='multipart/form-data'><table width='96%; style='border:1px solid gray'><input type='hidden' name='scommendid' value='".$scommend['id']."' /><tr style='height:30px'><td style='width:10%'>分站信息名称</td><td><input type='text' name='scommendname' value='".$scommend['scommendname']."' style='width:300px' maxlength='20' /></td></tr><tr><td>链接地址</td><td><input type='text' name='scommendurl' value='".$scommend['scommendurl']."' style='width:300px' maxlength='100' /></td></tr><tr><td>分站信息分类</td><td><select name='scommendtype' style='widht:160px'>".$option."</select></td></tr><tr><td>分站信息描述</td><td><input type='text' name='content' value='".$scommend['content']."' style='width:300px; height:80px' maxlength='300' /></td></tr><tr><td>分站信息地址</td><td><input type='file' name='imglogo' style='width:300px; border:1px solid gray' maxlength='100' /><br />".$strimg."<input type='hidden' name='imgpath' value='".$scommend['imglogo']."' /></td></tr><tr><td colspan='2' style='text-align:left; padding-left:200px; padding-top:20px'><input type='submit' name='submit' value='提交' style='border:1px solid gray' /></td></tr></table></form>";
	}elseif($_GET['do'] == 'tydel') {
		$id = $_GET['gid'];
		$row = DB::query("delete from ".DB::table('home_scommendtype')." where tid=$id");		
		cpmsg('删除成功', 'action=scommend&operation=scommend&do=tylist');
		
	}elseif($_GET['do'] == 'tyedit') {
		$gid = empty($_GET['gid']) ? 0 : $_GET['gid'];
		if($gid>0){
  
	$query = DB::query('SELECT tid, tname FROM '.DB::table('home_scommendtype').' where tid='.$gid);
		while($value=DB::fetch($query)) {
showformheader("scommend&operation=scommend&do=editype"); 
	  $strecho="修改推荐分类<br>";
	  $strecho.="推荐分类名称:<input type=\"text\" name=tname id=tname  value=\"".$value['tname']."\"><br>"; 
	  echo $strecho;
	  showhiddenfields(array('gid'=>$value['tid']));
       }  

	showsubmit('editsubmit');
	showformfooter();
} 
	 
	}elseif($do == 'tylist') {
	
	  showtableheader("分站信息分类管理");   //显示表格的第一个tr 的 th
    showsubtitle(array('分类名称','分类ID','操作'));    //显示表格第二个的标题
	    $limitpage=10;   //每页显示多少个 
    	$page=empty($_GET["page"])?1:$_GET["page"];  //page是必须的一样的
    	$page=trim(intval($page));
        $start = ($page-1)*$limitpage;   //开始的条数		
        $countnum = DB::result(DB::query("select count(0) num from ".DB::table('home_scommendtype')));	   //判断总条数
		
	$query = DB::query('SELECT tid, tname FROM '.DB::table('home_scommendtype').' order by tid desc limit '.$start.','.$limitpage);;
			while($v=DB::fetch($query)) {
	 showtablerow('class="td25"','',array($v["tname"],$v["tid"],'<a href="?action=scommend&operation=scommend&do=tyedit&gid='.$v[tid].'">修改</a>'));   }
	    $theurl = ADMINSCRIPT.'?action=scommend&operation=scommend&do=tylist'; //地址

        //判断 如果用户随便输入一个大数,有没有超出最高限度
        $allpage=ceil($countnum/$limitpage);
        //echo $allpage;
        if($page>$allpage){
            header("Location:/");
            exit;
        }

	  showtablefooter(); 
      $disppage = multi($countnum, $limitpage, $page, $theurl);
      echo '<p>'.$disppage.'</p>';
} else {
		 
		 $tid=$_GET['tid'];
		 $ad_search=$_GET['ad_search'];
		 if($tid){
			$strwh=" and ha.scommendtype=". $tid; 
		 } 
		 if($ad_search){
			$strwh=" and (ha.scommendname like '%".$ad_search."%' or ha.scommendurl like '%".$ad_search."%')"; 	 
		 }
		 //增加搜索条件
		 	showformheader('scommend&operation=scommend&do=adlist');
		 $strecho=" <select name='type' id='sortid' onchange=\"javascript:window.location.href='admin.php?action=scommend&operation=scommend&do=adlist&tid='+this.options[this.selectedIndex].value\">"; 
	  $strecho.="<option value=''>--选择分类--</option> ";
$query = DB::query('SELECT tid, tname FROM '.DB::table('home_scommendtype'));

			while($value=DB::fetch($query)) {
				 if($value['tid']==$_GET['tid']){ $stre= ' selected ';} else
				 {$stre= '';}
				 $strecho.="<option value=\"".$value['tid']."\"  ".$stre." >".$value['tname']."</option> ";
 }
  $strecho.="</select>"; 

		showtableheader($strecho.'关键字：<input class="text" name="ad_search" size="10" > 
		<input type="submit" class="btn" value=" 搜 素 ">'
		);
		showformfooter();
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		$pagesize = 10;
		if($page < 1) $page = 1;
		$start = ($page-1)*$pagesize;
		$multipage = '';

		$str = "<table width='96%'><tr style='height:30px'><td>分站信息ID</td><td>分站信息名称</td><td>链接地址</td><td>分站信息分类</td><td>分站信息描述</td><td>图片地址</td><td>操作</td></tr>";
		$count = DB::result(DB::query("select count(*) from ".DB::table('home_scommend')." as ha left join ".DB::table('home_scommendtype')." as hat on hat.tid=ha.scommendtype where  1=1 $strwh")); 
 
		if($count) {
			$sql="select ha.*, hat.tname from ".DB::table('home_scommend')." as ha left join ".DB::table('home_scommendtype')." as hat on hat.tid=ha.scommendtype where  1=1 $strwh order by id desc limit $start, $pagesize";
			$query = DB::query($sql); 
			while($row = DB::fetch($query)) {
				if(file_exists($row['imglogo'])){
					$strimg="<img src=".$row['imglogo']." width='150' height='80' />";
				}else{
					$strimg="无图";
				}
				$str .= "<tr id=".$row['id']." onmouseover='showcolor(".$row['id'].")' onmouseout='clearcolor(".$row['id'].")' style='height:30px'><td>".$row['id']."</td><td>".$row['scommendname']."</td><td>".$row['scommendurl']."</td><td>".$row['tname']."</td><td>".$row['content']."</td><td>".$strimg."</td><td><a href='".ADMINSCRIPT."?action=scommend&operation=scommend&do=edit&id=".$row['id']."'>修改</a> | <a href='".ADMINSCRIPT."?action=scommend&operation=scommend&do=del&id=".$row['id']."'>删除</a></td></tr>";
			}
			$multipage = multi($count, $pagesize, $page, '?action=scommend&operation=scommend');
			echo $str."</table><br>".$multipage;
		}
	}
}
?>


<script>
function del(gid){
	location.href="admin.php?action=scommend&operation=del&gid="+gid;
}
function tjdel(gid){
	location.href="admin.php?action=scommend&operation=tjdel&gid="+gid;
}

</script>