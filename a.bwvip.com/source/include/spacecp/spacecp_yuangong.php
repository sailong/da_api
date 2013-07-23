<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$gid=$_G['groupid'];
if($gid<'20'){
    header("Location:/");
    exit;
}
$url="/home.php?mod=spacecp&ac=yuangong";
$op=in_array($_GET["op"],array("index","search","del","subadd"))?$_GET["op"]:'index';

//var_dump($op);
if($op=='index'){
    //默认列出全部的员工
    $lia=DB::query("SELECT a.yid,b.`realname` FROM pre_jigou_yuangong a LEFT JOIN pre_common_member_profile b ON a.`yid`=b.`uid` WHERE a.`jid`=".$_G["uid"]);
    while($row=DB::fetch($lia)){
        $listarr[]=$row;
    }
    //var_dump($listarr);
}elseif($op=='search'){
    //var_dump($_POST);    
    $id=trim($_POST["id"]);
    $username=($_POST["username"]);
    if(empty($id) && empty($username)){
        //列出所有员工
        //showmessage('缺少参数',$url);
        $limitpage=20;   //每页显示多少个
        $limitpage = mob_perpage($limitpage);
    	$page=empty($_GET["page"])?1:$_GET["page"];  //page是必须的一样的
    	$page=trim(intval($page));
        $start = ($page-1)*$limitpage;   //开始的条数
        ckstart($start, $limitpage);

        $re=DB::query("SELECT a.uid,a.realname,b.`groupid` FROM pre_common_member_profile a LEFT JOIN pre_common_member b ON a.uid=b.`uid` WHERE b.`groupid`=10 AND a.`realname` !='' limit ".$start.",".$limitpage);
        while($ak=DB::fetch($re)){
            $rear[]=$ak;
        }
        //var_dump($rear);
        $theurl = $url."&op=search"; //地址

    	//判断总条数
        $countnum = DB::result(DB::query("SELECT COUNT(0) FROM pre_common_member_profile a LEFT JOIN pre_common_member b ON a.uid=b.`uid` WHERE b.`groupid`=10 AND a.`realname` !=''"));
    
        //判断 如果用户随便输入一个大数,有没有超出最高限度
        $allpage=ceil($countnum/$limitpage);
        //echo $allpage;
        if($page>$allpage){
            header("Location:".$url);
            exit;
        }
     
        $disppage = multi($countnum, $limitpage, $page, $theurl);  
    }else{
        if(!empty($id)){
            //根据id查
            $re=DB::query("SELECT a.uid,a.realname,b.`groupid` FROM pre_common_member_profile a LEFT JOIN pre_common_member b ON a.uid=b.`uid` WHERE a.uid={$id} AND b.`groupid`=10");
            while($ak=DB::fetch($re)){
                $rear[]=$ak;
            }
            //var_dump($rear);
        }elseif(!empty($username)){
            //根据名称查
            $re=DB::query("SELECT a.uid,a.realname,b.`groupid` FROM pre_common_member_profile a LEFT JOIN pre_common_member b ON a.uid=b.`uid` WHERE a.realname='{$username}' AND b.`groupid`=10");
            while($ak=DB::fetch($re)){
                $rear[]=$ak;
            }
            
            
        }
    }
      
}elseif($op=='subadd'){
    //添加员工
    $getuid=$_POST["puid"];
    
    //var_dump($getuid);
    if(empty($getuid[0])){
        showmessage('缺少参数',$url);
    }
    //echo $uid;
    $time=time();
    $jid=$_G["uid"];
    
    foreach($getuid as $vk){
        //判断有没有添加过
        $ya=DB::query("SELECT yid FROM pre_jigou_yuangong WHERE yid=".$vk." AND jid=".$jid);
        $yare=DB::fetch($ya);
        if(!empty($yare["yid"])){
            continue;      
        }
        $flag=DB::insert("jigou_yuangong",array("yid"=>$vk,"jid"=>$jid,"dateline"=>$time));
    }
    if($flag){
        showmessage('添加员工成功',$url);
    }else{
        showmessage('添加员工失败',$url);
    }
}

if($op=='del'){
    echo "oo";
    exit;
}else{
    $template = 'home/spacecp_yuangong';
    include template($template);
}

   

?>