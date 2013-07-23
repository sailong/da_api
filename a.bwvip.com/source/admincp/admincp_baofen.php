<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_misc.php 25638 2011-11-16 09:26:19Z liulanbo $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

cpheader();
echo "<script src='static/js/jquery.js'></script>";
$url="action=baofen";
$op=in_array($_GET["op"],array("add","list","dong",'del'))?$_GET["op"]:'list';
shownav('dazheng', 'N洞报分');
$navmenu[] = array('查看分配球洞', 'baofen&op=list', $operation == 'list');
$navmenu[] = array('添加报分员', 'baofen&op=add', $operation == 'add');
showsubmenu("N洞报分管理",$navmenu);

if($op=='add'){
    if(!submitcheck('searchsubmit')) {
        showformheader("baofen&op=add");
        echo "用户名：<input type='text' name='username' id='username' /><br /><br />";
        echo "密&nbsp;&nbsp;码：<input type='password' name='pwd' id='pwd' /><br /><br />";
        showsubmit("searchsubmit");
        showformfooter();
    }else{
        $uname=getgpc("username");
        $pwd=getgpc("pwd");
        //$pwd=md5($pwd);
		//判断用户名是否存在
		$have=DB::fetch_first("SELECT username FROM pre_nd_baofen_user WHERE username='".$uname."'");
		if(empty($have["username"])){
			$flag=DB::insert("nd_baofen_user",array("username"=>$uname,"password"=>$pwd));
			if($flag){
				cpmsg("添加成功",$url);
			}else{
				cpmsg("添加失败",$url);
			}
		}else{
			cpmsg("用户名已经存在",$url);
		}
    }
}elseif($op=='list'){
    //所有报分人员，18个内
    $bf=DB::query("SELECT a.id,a.username,a.password,a.eventid,a.fieldid,a.hole,b.realname AS ss_name,c.`realname` AS qc_name FROM `pre_nd_baofen_user` AS a LEFT JOIN pre_common_member_profile AS b ON a.eventid=b.uid LEFT JOIN pre_common_member_profile AS c ON a.`fieldid`=c.`uid`");
    while($row=DB::fetch($bf)){
        $bfarr[]=$row;
    }

    showtableheader("分配球洞");   //显示表格的第一个tr 的 th
    showsubtitle(array('username','密码','已分配球洞','所属赛事','所属球场','分配球洞','操作'));    //显示表格第二个的标题
    if(!empty($bfarr)){
        foreach($bfarr as $v){
            showtablerow('class="td25"','',array($v["username"],$v["password"],"【".$v["hole"]."】洞口",$v["ss_name"],$v["qc_name"],'<a href="admin.php?action=baofen&op=dong&bid='.$v[id].'">分配球洞</a>','<a href="javascript:void(0)" onclick="if(!confirm(&quot;确认要删除吗？&quot;)) return false;tjdel('.$v["id"].')">删除</a>'));   //href="admin.php?action=baofen&op=del&bid='.$v["id"].'"
        }
    }
    showtablefooter();

}elseif($op=='dong'){
    if(!submitcheck("searchsubmit")){
        $bid=getgpc("bid");
        if(empty($bid)){
            cpmsg("参数失败",$url);
        }
		//如果有填写内容显示出来
		$ishave=DB::fetch_first("SELECT * FROM pre_nd_baofen_user WHERE id=".$bid);
		$harr=explode(",",$ishave["hole"]);
		//echo "<pre>";
		//print_r($ishave);
		$fieldarr=array("1083"=>"长沙梓山湖","1290"=>"广州风神",'1218'=>"海宁尖山高尔夫俱乐部","1026"=>"深圳正中","1204"=>"天津滨海湖","971"=>"北京银泰鸿业","1889284"=>"青岛桃园江景","1186"=>"上海美兰湖","1302"=>"大连红旗谷","1341"=>"成都蓝光观岭","1113"=>"苏州太湖","994"=>"福州长乐海峡","1203"=>"天津滨海森林");

	    /*分站信息*/
		$fenz_query = DB::query("SELECT * FROM ".DB::table("home_fenz")." where sais_id = '1000333' ");
		$fenz="分站名称：<select name=\"fenz_type\"><option value=\"0\">请选择分站</option>";
		while($result = DB::fetch($fenz_query)){
			$selected = "";
			if($ishave["fenz_type"]==$result['key']) $selected =" selected " ;
			$fenz.="<option value=\"".$result['key']."\" ".$selected.">".$result['value']."</option>";
		}
		$fenz.="<option value=\"1023\">天津滨海森林高尔夫俱乐部</option>";
		
		$fenz.="</select>";
		//var_dump($fenz);
		/*******/
		/*赛事列表*/
		$saishi_id = '25';
		$sql_query=  DB::query(" SELECT cm.uid , cmp.field1 FROM  ".DB::table('common_member')." as cm LEFT JOIN ".DB::table('common_member_profile')." as cmp ON cmp.uid = cm.uid  where cm.groupid= '".$saishi_id."' ");
		$sais_select  = " 选择赛事：<select name=\"sais_id\">";
		while($sais_result = DB::fetch($sql_query)){
			if($sais_result['field1']){
			  if($sais_result['field1']=='皇冠杯城市挑战赛'){ //临时这样 筛选出  皇冠杯城市挑战赛
					 $sais_select .= '<option value="'.$sais_result['uid'].'">'.$sais_result['field1'].'</option>';
			   }
			}

		}
		$sais_select .= '<option value="1000333">皇冠杯城市挑战赛</option>';
		$sais_select .="</select>";


        showformheader("baofen&op=dong");
        echo '选择赛事：'.$sais_select;
        echo $fenz;
        echo '球场：<select name="qiuc">';
		foreach($fieldarr as $key=>$vk){
			if($ishave["fieldid"]==$key){
				$opstr.="<option value=\"{$key}\" selected='selected'>{$vk}</option>";
			}else{
				$opstr.="<option value=\"{$key}\">{$vk}</option>";
			}
		}
		echo $opstr;
		echo '</select><br />';
        for($i=1;$i<=18;$i++){
			if(in_array($i,$harr)){
				$str.='球洞'.$i.'<input type="checkbox" name="qd[]" checked="true" value="'.$i.'" />&nbsp;&nbsp;';
			}else{
				$str.='球洞'.$i.'<input type="checkbox" name="qd[]" value="'.$i.'" />&nbsp;&nbsp;';
			}
		}
        echo $str;
        showhiddenfields(array('bid' => $bid));
        showsubmit("searchsubmit");
        showformfooter();
    }else{
        //echo "<pre>";
        //var_dump($_POST);
        $sid=getgpc("sais_id");   //赛事id
        $qcid=getgpc("qiuc");     //球场id
        $qd=getgpc("qd");		//球洞
        $fenz_type=getgpc("fenz_type");    //分站
        $bid=getgpc("bid");   //报分员id
        if(empty($bid)){
           cpmsg("参数失败",$url);
        }
        if(!empty($qd)){
            foreach($qd as $key=>$t){
                if($key==0){
                    $hole=$t;
                }else{
                    $hole.=",".$t;
                }
            }
        }
        //echo $hole;

        $flag=DB::update("nd_baofen_user",array("eventid"=>$sid,"fieldid"=>$qcid,"fenz_type"=>$fenz_type,"hole"=>$hole),array("id"=>$bid));
        if($flag){
            cpmsg("球洞分配成功",$url);
        }else{
            cpmsg("球洞分配失败",$url);
        }
    }

}elseif ($op=='del'){
	//echo "bid";
	$bid=getgpc("bid");
	if(!empty($bid)){
		$flag=DB::delete("nd_baofen_user",array("id"=>$bid));
		if($flag){
			cpmsg("删除成功",$url);
		}else{
			cpmsg("删除失败",$url);
		}
	}else{
		cpmsg("参数失败",$url);
	}
}

?>

<script>
function tjdel(bid){
	location.href="admin.php?action=baofen&op=del&bid="+bid;
}

</script>
