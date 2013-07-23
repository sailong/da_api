<?php
if (! defined ( 'IN_DISCUZ' ) || ! defined ( 'IN_ADMINCP' )) {
	exit ( 'Access Denied' );
}
cpheader (); //加载头部，样式等


?>
<style>
.bor{border:1px solid #ccc;line-height:60px;}
.bor tr:hover{background-color:#999;}
.bor td{border:1px solid #ccc;padding:5px 2px;}
</style>
<?php
/*showtips('blog_tips');     //技巧提示
showsubmenu('nav_misc_onlinelist');    //定义好的东西
shownav('user', 'nav_usergroups');    //导航菜单下面的这个       用户 » 用户组  [+]
//cpmsg("这个是啥啊","http://www.baidu.com/");    //这个是一个提示，提交完成可以调取这个
if(!submitcheck('typesubmit')) {
//判断提交没有提交
}
*/

$op = empty ( $_GET ["operation"] ) ? 'list' : $_GET ["operation"];
if ($op == 'list') {
	$limitpage = 20; //每页显示多少个
	$page = empty ( $_GET ["page"] ) ? 1 : $_GET ["page"]; //page是必须的一样的
	$page = trim ( intval ( $page ) );
	$start = ($page - 1) * $limitpage; //开始的条数
	//ckstart ( $start, $limitpage );

	$list = DB::query ( "SELECT `id`,`mobilename`,`mobile`,`user`,`status`,`addtime` FROM `pre_mobilerecord` ORDER BY ADDTIME DESC limit ".$start.",".$limitpage );
	$str = "<table width='99%' class='bor'>";
	$str .= "<tr><td>姓名</td><td>手机号</td><td>操作人</td><td>状态</td><td>预定时间</td><td>操作</td></tr>";
	while ( $re = DB::fetch ( $list ) ) {
		if ($re ["status"] == '1') {
			$re ['statust'] = '已审核';
		} else {
			$re ['statust'] = '未审核';
		}

		$str .= "<tr style='line-height:220%' onmouseover='this.style.backgourndColor=\'#f00\''><td>{$re["mobilename"]}</td><td>{$re["mobile"]}</td><td>{$re["user"]}</td><td>{$re["statust"]}</td><td>{$re["addtime"]}</td><td>";
		if($re ["status"]=='0'){
			$str .= "<a href='".ADMINSCRIPT."?action=shoujibao&operation=shen&id={$re["id"]}'>审核</a>";
		}
		$str .= "</td></tr>";
	}
	$str .= "</table>";
	$theurl = ADMINSCRIPT.'?action=shoujibao'; //地址
	//判断总条数
	$countnum = DB::result ( DB::query ( "SELECT COUNT(0) num FROM `pre_mobilerecord` ORDER BY ADDTIME DESC" ) );
	$disppage = multi ( $countnum, $limitpage, $page, $theurl );
	echo $str;

	echo $disppage;

}elseif ($op=="shen"){
	$id=$_GET["id"];
	if(empty($id)){
		cpmsg("少参数","action=shoujibao");
		exit;
	}else{
		$flag=DB::update("mobilerecord",array("status"=>1,"user"=>$_G["username"]),array("id"=>$id));
		if($flag){
			cpmsg("审核成功","action=shoujibao");
		}else{
			cpmsg("审核失败","action=shoujibao");
		}
	}


	//cpmsg("这个是啥啊","admin.php?action=shoujibao");
}
?>