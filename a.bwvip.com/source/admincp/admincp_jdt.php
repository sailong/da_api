<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_doing.php 22995 2011-06-13 03:15:57Z zhangguosheng $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
cpheader();
$operation=in_array($_GET['op'],array('jdt','list','edit','editsub','topic','page','topiccontent','topiccontenttwo','topadd','contentdel'))?$_GET['op']:'list';
echo "<script src='static/js/jquery1.5.js'></script>";
$do=trim($_GET["do"]);
$al=array("1"=>"首页焦点图","2"=>"球场焦点图","3"=>"赛事焦点图","4"=>"旅游焦点图","5"=>"资讯焦点图","6"=>"品牌俱乐部焦点图","7"=>"球星焦点图","8"=>'球星话题',"9"=>"赛事话题",'10'=>'城市挑战赛焦点图','11'=>'旅游话题','12'=>'球场话题','13'=>'资讯话题','14'=>'品牌俱乐部话题','15'=>'教学焦点图 ','16'=>'教学话题');
$navmenu[0] = array('焦点图推荐', 'jdt&op=jdt', $operation == 'jdt');
   	$navmenu[1] = array('话题推荐', 'jdt&op=topic', $operation == 'topic');
    $navmenu[2] = array('话题内容推荐', 'jdt&op=topiccontent', $operation == 'topiccontent');
    //$navmenu[3] = array('首页推荐', 'recommend&operation=other', $operation == 'other');
    //$navmenu[4] = array('自定义推荐', 'recommend&operation=custom', $operation == 'custom');
    showsubmenu('社区推荐', $navmenu);
if(!submitcheck('searchsubmit')){

	if($operation=='jdt'){
        showformheader('jdt&op=jdt','enctype="multipart/form-data"',"form1");   //显示<form acton='jdt'>

        echo "上传图片：<input type='file' name='file1'><br /><br />";
        echo "链接地址：<input type='text' name='href' size='80' /><br /><br />";
        echo "页面类型：<select name='typelist'>";
        //$i=1;
        foreach($al as $key=>$value){
            //if($i>7){
                //break;
            //}
            $at.="<option value='".$key."'>".$value."</option>";
            //$i++;
        }
        echo $at;
        echo "</select><br /><br />";
        echo "排序：<input type='text' name='seq' >";
        showsubmit('searchsubmit');   //显示 提交 按钮
        showformfooter();     //显示</form>
	}elseif($operation=='list'){
	   //列出上传的焦点图
        $limitpage=10;   //每页显示多少个
        //$limitpage = mob_perpage($limitpage);
    	$page=empty($_GET["page"])?1:$_GET["page"];  //page是必须的一样的
    	$page=trim(intval($page));
        $start = ($page-1)*$limitpage;   //开始的条数
        //ckstart($start, $limitpage);


       echo "<table width='100%'>";
       echo "<tr>";
       echo "<td>图片</td><td>链接</td><td>排序</td><td>推荐类型</td><td>操作</td>";
       echo "</tr>";
       $path="upload/jdt/";    //上传路径
       $lia=DB::query("SELECT `id`,`bigpic`,`href`,`type`,`seq` FROM pre_dazheng_recommend order by type asc,seq asc limit ".$start.",".$limitpage);
       while($row=DB::fetch($lia)){
            $lt=$al[$row["type"]];
            echo "<tr><td><img src='".$path.$row["bigpic"]."' width='100' height='70' /></td><td>".$row["href"]."</td><td>".$row["seq"]."</td><td>".$lt."</td><td><a href='".ADMINSCRIPT."?action=jdt&op=edit&id=".$row["id"]."'>编辑</a>&nbsp;/&nbsp;<a href='javascript:void(0)' onclick='if(!confirm(\"确认要删除吗？\")) return false;tjdel(".$row["id"].")'>删除</a></td></tr>";
       }

      echo "<table>";
      $theurl = ADMINSCRIPT.'?action=jdt'; //地址

    	//判断总条数
        $countnum = DB::result(DB::query("select count(0) num from pre_dazheng_recommend"));

        //判断 如果用户随便输入一个大数,有没有超出最高限度
        $allpage=ceil($countnum/$limitpage);
        //echo $allpage;
        if($page>$allpage){
            header("Location:/");
            exit;
        }

        $disppage = multi($countnum, $limitpage, $page, $theurl);
         echo $disppage;

	}elseif($operation=='edit'){
	   //查出来这些数据
       $ak=trim($_GET["id"]);
       if(empty($ak)){
            cpmsg("参数失败","action=jdt");
            exit;
       }
       //$al=array("1"=>"首页焦点图","2"=>"球场焦点图","3"=>"赛事焦点图","4"=>"旅游焦点图","5"=>"资讯焦点图","6"=>"品牌俱乐部焦点图","7"=>"球星焦点图");
       //var_dump($al);
       $lk=DB::query("SELECT `id`,`bigpic`,`seq`,`smallpic`,`href`,`content`,`type` FROM pre_dazheng_recommend WHERE id=".$ak);
       $lkre=DB::fetch($lk);
	    showformheader('jdt&op=editsub','enctype="multipart/form-data"',"form1");   //显示<form acton='jdt'>
        if(empty($lkre['content']) && !empty($lkre["href"])){
            echo "链接地址：<input type='text' name='href' value='".$lkre["href"]."' size='90' /><br /><br />";
            echo "<input type='hidden' name='mytype' value='href' />";     //判断是连接还是内容
        }elseif(!empty($lkre['content']) && empty($lkre["href"])){
            echo "话题：<input type='text' name='topic' value='".$lkre["content"]."' size='90' /><br /><br />";
            echo "<input type='hidden' name='mytype' value='content' />";     //判断是连接还是内容
        }
        echo "<input type='hidden' value='".$lkre["id"]."' name='nowid'";
        echo "<br />";
        echo "页面类型：".$al[$lkre["type"]];
        echo "<br />";
        echo "排序：<input type='text' name='seq' value='".$lkre["seq"]."' />";
        showsubmit('searchsubmit');   //显示 提交 按钮
        showformfooter();     //显示</form>
	}
}
//上传焦点图
if($operation=='jdt'){
    if(submitcheck('searchsubmit')){
        //var_dump($_FILES);
        $file=$_FILES["file1"];   //上传文件
        $url=$_POST["href"];   //连接
        $type=$_POST["typelist"];   //页面类型
        $path="upload/jdt/";    //上传路径
        $seq=$_POST["seq"];    //排序
        //var_dump($_POST);
        $re=upload($file,$path,$url,$type,$seq);
        cpmsg($re,"action=jdt");
    }
}
if($operation=='editsub'){
    //var_dump($_POST);
    //var_dump($_FILES);
    $nowid=$_POST["nowid"];
    $url=trim($_POST["href"]);    //接收的连接
    $topic=trim($_POST["topic"]);   //接收的话题
    $seq=trim($_POST["seq"]);
    $mytype=$_POST["mytype"];    //判断类型

    if(empty($nowid)){
        cpmsg("参数错误","action=jdt");
        exit;
    }
    if(empty($url) && empty($topic)){
        cpmsg("不能为空","action=jdt");
        exit;
    }
    //更新
    if($mytype=='href'){
        $flag=DB::update("dazheng_recommend",array("href"=>$url,"seq"=>$seq),array("id"=>$nowid));
    }elseif($mytype=='content'){
        $flag=DB::update("dazheng_recommend",array("content"=>$topic,"seq"=>$seq),array("id"=>$nowid));
    }


    if($flag){
        cpmsg("更新成功","action=jdt");
    }else{
        cpmsg("更新失败","action=jdt");
    }

}
if($operation=='topic'){
    if(!submitcheck('searchsubmit')){
        $act=empty($_GET["ac"])?'list':$_GET["ac"];
            showformheader('jdt&op=topic','enctype="multipart/form-data"',"form1");   //显示<form acton='topic'>
            echo "上传图片：<input type='file' name='file1'><br /><br />";
            echo "页面类型：<select name='typelist'>";
            foreach($al as $key=>$value){
                //if($key>'7'){
                    $at.="<option value='".$key."'>".$value."</option>";
                //}
            }
            echo $at;
            echo "</select><br /><br />";
            echo "话题：<input type='text' name='topic' size='50' /><br /><br />";
            echo "排序：<input type='text' name='seq' size='10' >";
            showsubmit('searchsubmit');   //显示 提交 按钮
            showformfooter();     //显示</form>


    }else{
        $file=$_FILES["file1"];   //上传文件
        $url=$_POST["topic"];   //话题
        $type=$_POST["typelist"];   //页面类型
        $path="upload/jdt/";    //上传路径
        $seq=$_POST["seq"];    //排序
        //var_dump($_POST);
        $re=upload($file,$path,$url,$type,$seq);
        cpmsg($re,"action=jdt&op=topic");
    }
}

if($operation=='topiccontent'){
	echo "<a href='".ADMINSCRIPT."?action=jdt&op=topiccontent&do=tj'>推荐话题内容</a>";
	echo "&nbsp;&nbsp;&nbsp;";
	echo "<a href='".ADMINSCRIPT."?action=jdt&op=topiccontent&do=list'>查看推荐话题</a>";
	echo "<br /><br />";
	if($do=='tj' || empty($do)){
		if(!submitcheck('searchsubmit')){
		 	showformheader('jdt&op=topiccontenttwo');   //显示<form acton='topic'>
			echo "页面类型：<select name='typelist' id='typelist' onchange='topic()'>";
			echo "<option value=''>请选择</option>";
		        $i=1;
		        foreach($al as $key=>$value){
		        	if($i>7){
		            	$at.="<option value='".$key."'>".$value."</option>";
		        	}
		        	$i++;
		        }
		        echo $at;
		        echo "</select>";
		        echo "话题：<select id='mytop' name='mytop'></select>";

		 	showsubmit('searchsubmit');   //显示 提交 按钮
	     	showformfooter();     //显示</form>
		}else{

		}
	}elseif ($do=='list'){
		if(!submitcheck('searchsubmit')){
				showformheader('jdt&op=topiccontent&do=list');   //显示<form acton='jdt'>
				echo "页面类型：<select name='typelist'>";
				$i=1;
	            foreach($al as $key=>$value){
	                if($i>7){
	                    $at.="<option value='".$key."'>".$value."</option>";
	                }
	                $i++;
	            }
	            echo $at;
	            echo "</select><br /><br />";
	            showsubmit('searchsubmit');   //显示 提交 按钮
	            showformfooter();
		}else{
			$tlist=getgpc("typelist");
			$sql="SELECT b.id,a.content,c.content as con FROM `pre_dazheng_recommend` AS a ,pre_dazheng_topic_recommend AS b,jishigou_topic AS c  WHERE b.pid =a.id AND b.item_id=c.tid AND a.type=".$tlist;
			//echo $sql;
			showformheader('jdt&op=contentdel');   //显示<form acton='jdt'>
			showtableheader($al[$tlist]);
			showsubtitle(array('删除','话题名称','话题内容'));    //显示表格第二个的标题
			$rk=DB::query($sql);
			while($ro=DB::fetch($rk)){
				showtablerow('class="tb tb2"','',array("<input type='checkbox' name='del[]' value='".$ro["id"]."' class='td25' />",$ro["content"],$ro["con"]));
			}
			showtablefooter();  //显示结束的</table>
			showsubmit("searchsubmit");
		}

	}


}
if($operation=='topiccontenttwo'){

				$pid=getgpc("typelist");     //那个类别,赛事，还是教学等
				//echo $pid;
				$v=getgpc("mytop");      //话题内容id  pre_dazheng_recommend  的id  类型下的话题
				if(empty($v)){
					cpmsg("没有推荐话题","action=jdt&op=topiccontent");
				}
				//通过id查找话题的名称

				$con=DB::fetch(DB::query("SELECT content FROM `pre_dazheng_recommend` WHERE id=".$v));
				//$con=$al[$v];
				//var_dump($con);
				$limitpage=10;   //每页显示多少个
			    //$limitpage = mob_perpage($limitpage);
				$page=empty($_GET["page"])?1:$_GET["page"];  //page是必须的一样的
				$page=trim(intval($page));
			    $start = ($page-1)*$limitpage;   //开始的条数
			   // ckstart($start, $limitpage);




				//echo $v;
				if(!empty($pid) && !empty($con["content"])){

					$sql="SELECT a.name,a.username,a.topic_count,b.item_id,c.content,d.pid,c.replys,e.type FROM jishigou_tag AS a LEFT JOIN jishigou_my_topic_tag AS b ON a.id=b.tag_id LEFT JOIN jishigou_topic AS c ON b.item_id=c.tid LEFT JOIN pre_dazheng_topic_recommend AS d ON b.item_id=d.item_id LEFT JOIN pre_dazheng_recommend AS e ON d.pid=e.id WHERE a.name='".$con["content"]."' AND c.content !='' order by e.id desc limit ".$start.",".$limitpage."";

                    $re=DB::query($sql);
					showformheader('jdt&op=topadd');   //显示<form acton='jdt'>
					showtableheader($con["content"]."-话题内容推荐");
					showsubtitle(array('推荐', '话题内容','评论数',"状态"));    //显示表格第二个的标题
					while($row=DB::fetch($re)){
						if($row["type"]!=$pid){
							$static = "未推荐";
						}else{
							$static = "<span style='color:#f00;'>已推荐</span>";
						}
						showtablerow('class="tb tb2"','',array("<input type='checkbox' name='ch[]' value='".$row["item_id"]."' />","{$row['content']}"."<div id='ajax_".$row['item_id']."' style='display:none;'></div>",'<a href="javascript:void(0);" onclick="ajax_replay('.$row['item_id'].')">'.$row["replys"].'</a>',$static));
						//$liarr[]=$row;
					}
					$theurl = ADMINSCRIPT.'?action=jdt&op=topiccontenttwo&mytop='.$v."&typelist=".$pid; //地址

					//判断总条数
				    $countnum = DB::result(DB::query("SELECT COUNT(0) num FROM jishigou_tag AS a LEFT JOIN jishigou_my_topic_tag AS b ON a.id=b.tag_id LEFT JOIN jishigou_topic AS c ON b.item_id=c.tid LEFT JOIN pre_dazheng_topic_recommend AS d ON b.item_id=d.item_id WHERE a.name='{$con["content"]}' AND c.content !='' "));
				    $disppage = multi($countnum, $limitpage, $page, $theurl);


					showtablefooter();  //显示结束的</table>
					showhiddenfields(array('pid' => $v));
					echo $disppage;
					showsubmit('searchsubmit');   //显示 提交 按钮
					showformfooter();
				}
}
if($operation=='contentdel'){
	//var_dump($_POST);
	$id=getgpc("del");
	if(count($id)<=0){
		cpmsg("请先选择要取消推荐的话题","action=jdt&op=topictent");
	}
	foreach ($id as $iv){
		$flag=DB::delete("dazheng_topic_recommend",array("id"=>$iv));
	}
	if($flag){
		cpmsg("取消成功","action=jdt&op=topictent");
	}else{
		cpmsg("取消失败","action=jdt&op=topictent");
	}
}
if($operation=='topadd'){
	$ch=getgpc("ch");     //话题id
	$pid=getgpc("pid");    //属于哪个话题
	//echo "<pre>";
	//var_dump($_POST);
	$c=count($ch);
	if($c>=1){
		foreach($ch as $in){
			$flag=DB::insert("dazheng_topic_recommend",array("pid"=>$pid,"item_id"=>$in));
		}
		if($flag){
			cpmsg("推荐成功","action=jdt&op=topictent");
		}else{
			cpmsg("推荐失败","action=jdt&op=topictent");
		}
	}

}


function upload($upfile,$path,$url,$typelist,$seq,$limitsize='1000000'){
    Global $_G;
    global $_GET;
	if(empty($upfile)){
		return '';
	}
    if(empty($path)){
        return '';
    }
	$type=$upfile["type"];     //类型
    //return $upfile.'*********';
	$temppath=$upfile["tmp_name"];    //临时路径
	$size=$upfile["size"];      //大小
	$error=$upfile["error"];   //错误提示
	$limitsize='1000000';   //1M
	$newpath=$path;   //文件路径
	switch ($type){
		case 'image/gif':
			$exp=".gif";
			break;
		case 'image/jpeg':
			$exp='.jpg';
			break;
		case 'image/pjpeg':
			$exp='.jpg';
			break;
	}
	$newname=time()."_big_".rand(100,999).$exp;
	if(empty($exp)){
	   //return $exp;
		return '图片类型不对';
	}
	if($size>$limitsize){
		return '超过最大限制1M';
	}
	if($error=='0'){
		$ak=move_uploaded_file($temppath,$newpath.$newname);
		if(!ak){
			return '上传出错了';
		}else{

              /***
            *缩略图
            ***/
            $width="78";
            $height="33";
            $path=$newpath.$newname;    //图片的路径
            $smallpath=time()."_small_".rand(100,999).$exp;      //新的图片路径
            $ak=getimagesize($path);     //为了获取文件的宽和高
            //var_dump($ak);
            $in=imagecreatefromjpeg($path);        //从 JPEG 文件或 URL 新建一图像
            $out=imagecreatetruecolor($width,$height);    //新建一个真彩色图像
            imagecopyresampled($out,$in,0,0,0,0,$width,$height,$ak[0],$ak[1]);    //重采样拷贝部分图像并调整大小
            imagejpeg($out,$newpath.$smallpath);    //以 JPEG 格式将图像输出到浏览器或文件
            imagedestroy($in);  //销毁一幅图像
            imagedestroy($out);   //销毁一幅图像
            //入库
            $nowtime=time();
            if(empty($_POST["topic"])){
                $flag=DB::insert("dazheng_recommend",array("userid"=>$_G["uid"],"bigpic"=>$newname,"smallpic"=>$smallpath,"href"=>$url,"type"=>$typelist,"dateline"=>$nowtime,"seq"=>$seq));
            }else{
                $flag=DB::insert("dazheng_recommend",array("userid"=>$_G["uid"],"bigpic"=>$newname,"smallpic"=>$smallpath,"content"=>$_POST["topic"],"type"=>$typelist,"dateline"=>$nowtime,"seq"=>$seq));
            }


            if($flag){
                return '上传成功';
            }else{
                return '操作失败';
            }

		}
	}
}

?>
<script>
function tjdel(did){
    //alert(did);
    $.post("/yuangong.php",{flag:'2',did:did},function(msg){
        //alert(msg);
        if(msg=='ok'){
          alert('删除成功');
          location.reload();
        }else{
            alert("删除失败");
            location.reload();
        }
    })
}
function topic(){
		var tid=$("#typelist").val();
		//alert(tid);
	 $.post("/yuangong.php",{flag:'3',tid:tid},function(msg){
        //alert(msg);
        var ja=jQuery.parseJSON(msg);   //获取json数据
        //alert(ja);
		if(ja.app){
        	var num=ja.app.length;     //获取循环的次数

        	if(num >= 1){
    	        var str='';
    		    for(i=0;i<num;i++){
    				str+="<option value='"+ja.app[i].id+"'>"+ja.app[i].content+"</option>";

    			}
    		    $("#mytop").html(str);
            }else{
            	$("#mytop").html('');
            }
		}else{
			$("#mytop").html('');
		}
    })
}

function ajax_replay(ajaxid){
	if(''!=ajaxid){
	   $.post("/yuangong.php",{flag:'4',tid:ajaxid},function(msg){
	       //alert(msg);
           var ja=jQuery.parseJSON(msg);   //获取json数据

           //alert(ja.app[0].content);
           //alert(ja.app[0].tid);
          if(ja.app){
            var str='';
            var num=ja.app.length;     //获取循环的次数
            //alert(num);
            for(i=0;i<num;i++){
				str+="<input type='checkbox' value='"+ja.app[i].replyid+"' name='ch[]' />"+ja.app[i].content;
  			}
            //alert(str);
          }

          $("#ajax_"+ajaxid).html(str);
          $("#ajax_"+ajaxid).toggle();
       })

	}
}


</script>

