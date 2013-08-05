<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_profile.php 24010 2011-07-17 07:35:13Z angf $
 */


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$defaultop = 'base';
$operation = in_array($_GET['op'], array('base', 'members_fenzu', 'm_fenzu_submit', 'event_fenzu_rule_list', 'del_rule', 'member_allot', 'start_nd', 'stopgame', 'baofen','member_get')) ? trim($_GET['op']) : $defaultop;

$fenzu_rule =array(
    '1'=>'队籍交叉分组',
    '2'=>'差点高低分组',
    '3'=>'会员随机分组'
);


	 

if($operation=='base'){

    $actives = array('m_list' =>' class="a"');

    $fz_rows = $qc_rows = array();
    $query  =DB::query(" SELECT `fenzhan_id`,`fenzhan_name` from tbl_fenzhan where sid= ".$_G['uid']."");
	while($result = DB::fetch( $query )) {
        $fz_rows[] = $result;
    }

    $query = DB::query("SELECT * FROM ".DB::table('saishi_qiuc')." where sid =".$_G['uid']);
    while($result = DB::fetch( $query )) {
        $qc_rows[] = $result;
    }
	   $where="where sid= ".$_G['uid'];
	    $fenzhan_id  = $_G['gp_fenz'];
	   if($fenzhan_id){
		 	$where.=" and  fenzhan_id= ".$fenzhan_id;
	   }
	   
        $field_id = $_G['gp_field'];   
		if($field_id){
		$where.=" and  field_id= ".$field_id;
	   }
	   
	/*导出动作*/
		if(isset($_G['gp_export'])){ 
             
			 // $query = DB::query("SELECT * FROM ". DB::table('home_dazbm')." " .$where. " ORDER BY bm_id desc " );
			  
			  $query = DB::query("SELECT * FROM ". DB::table('fenzu_members')." $where ORDER BY tee asc,team_number asc " );
			  while($result_list = DB::fetch($query)){
				  $result_list['start_time']=date('Y-m-d H:i:s',$result_list['start_time']);
				  
				   $result_list['fenzhan_name']= DB::result_first("select fenzhan_name from tbl_fenzhan  where fenzhan_id='".$result_list['fenzhan_id']."' ");
				
				  $result_list['field_id']= DB::result_first("select fieldname from ".DB::table('common_field')."  where uid='".$result_list['field_id']."' ");   
				  
            unset( $result_list['golf_team_id']);
            unset( $result_list['golf_team_name']);
				  $export_list[] = $result_list; 
			  }   
			  dz2excel($export_list,'dazbm_export');
			  exit;
		}
    /*搜索动作*/
    if(submitcheck('searchsubmit')) {

        $fenzhan_id  = $_G['gp_fenz'];
        $field_id = $_G['gp_field'];
        $fenzhan_members= array();
        /*查看规则是否被删除*/
        $event_rule_query = DB::result_first("SELECT * FROM ".DB::table('event_fenzu_rule_list')." where fenz_id=".$fenzhan_id." and field_id=".$field_id." and is_delete=0 ");
		
        if($event_rule_query){

            if(empty($fenzhan_id) || empty($field_id)){
                showmessage("请选择 搜索的条件");
            }
            $query = DB::query(" select * from ".DB::table('fenzu_members')." where sid = ".$_G['uid']." and fenz_id = ".$fenzhan_id." and field_id =".$field_id." ORDER BY tee asc,team_number asc ");

            while($result = DB::fetch($query)) {
                $result['start_time'] =  date('Y-m-d H:i:s',$result['start_time']);
                $fenzhan_members[] = $result;
            }
        }

    }

    include template("home/spacecp_fenzu");


}elseif($operation == 'members_fenzu'){


    $fz_rows = $qc_rows = array();
    $query  =DB::query(" SELECT `fenzhan_id`,`fenzhan_name` from tbl_fenzhan where sid= ".$_G['uid']."");
	while($result = DB::fetch( $query )) {
        $fz_rows[] = $result;
    }

    $query = DB::query("SELECT * FROM ".DB::table('saishi_qiuc')." where sid =".$_G['uid']);
    while($result = DB::fetch( $query )) {
        $qc_rows[] = $result;
    }

    $html_tee ="";
    for ($i = 1; $i <= 18; $i++) {
         $html_tee .= "<input name=\"tee[]\" type=\"checkbox\" value=\"".$i."\">".$i."台";
         if($i % 9==0){$html_tee.="<br>";}
    }


    $actives = array('qyfenzu' =>' class="a"');
    include template("home/spacecp_qyfenzu");


}elseif($operation == "m_fenzu_submit"){   //分组



    /*判断是否 分站 已经分组*/
    $is_fenzu = DB::result_first("select * from ".DB::table('fenzu_members')." where fenz_id=".$_G['gp_fenz']." and field_id = ".$_G['gp_field'] );
	echo "select * from ".DB::table('fenzu_members')." where fenz_id=".$_G['gp_fenz']." and field_id = ".$_G['gp_field'] ;
    if($is_fenzu) showmessage("该分站下 已经分组 你可以删除 从新分组");

    $data['fenz_id']        = $_G['gp_fenz'];
    $data['field']          = $_G['gp_field'];
    $data['game_starttime'] = strtotime(getgpc('game_starttime'));
    $data['rest_starttime'] = strtotime(getgpc('rest_starttime'));
    $data['rest_endtime']   = strtotime(getgpc('rest_endtime'));
    $data['game_jg_time']   = getgpc('game_jg_time')*60;
    $data['fenzu_rule']     = getgpc('fenzu_rule');
    $data['tee']            = getgpc('tee');
    $data['team_member_num']= getgpc('team_member_num');

    if(empty($data['fenz_id'])){
        showmessage('请选择分站');
    }
    if(empty($data['field'])){
        showmessage('请选择球场');
    }
    if(empty($data['game_starttime']) ||  empty($data['rest_starttime']) || empty($data['game_jg_time'])){
        showmessage('请认真填写比赛数据');
    }
    if(empty($data['tee'])){
        showmessage('请选择 赛事同时发球的Tee 台');
    }
    if($data['game_starttime'] >= $data['rest_starttime']){
        showmessage("比赛开始时间 不能 在休息时间之后");
    }
    if($data['rest_endtime'] <= $data['rest_starttime']){
        showmessage("比赛休息结束时间 不能 在开始休息时间之前");
    }
 if($data['fenzu_rule']==2){
        $orderby="order by chadian asc";
    }
 if($data['fenzu_rule']==3){
        $orderby=" ";
    }
  
    $members_list=array();

    /*分站会员列表*/
    $query = DB::query("select *  from ".DB::table("fenzhan_members")." where sid=".$_G['uid']." and fz_id = ".$data['fenz_id']." $orderby" );
    while($result =  DB::fetch($query)){
        $members_list[] = $result;
    }

    if(empty($members_list)){
        showmessage("sorry！你的分站暂时还没有会员 请为分组添加相应的会员");
    }

    //算出总组数   总人数/每组人数
    $fz_num         = ceil(count($members_list)/$data['team_member_num']);
    $tee_num        = ceil(count($data['tee']));
    $am_game_time   = $data['rest_starttime'] -$data['game_starttime'];  //上半场开球时间
    $am_kq_num      = ceil($am_game_time /$data['game_jg_time']);      //计算上午能分出多少组  用上午比赛的时间/间隔时间*开球tee数
	 
/* 针对 一上午 或 一下午能够打完比赛的
   if($am_kq_num * $tee_num >= $fz_num)
   {
       $k=$kk=$t=0;$pm=$up_d=1;
       foreach($data['tee'] as $key=>$value){
            for($i=1;$i<=ceil($fz_num/$tee_num);$i++)
            {
                $k++;
                if($k<=$fz_num)
                {
                    $start_time = $data['game_starttime'] +  ($kk * $data['game_jg_time']);
                    if($start_time > ( $data['rest_starttime']-$data['game_jg_time'])){
                        $start_time = $data['rest_endtime'] +  ($data['game_jg_time']*$pm);
                        $up_d=2;
                    }
                    $bs_data[$k]['start_time'] = $start_time;
                    $bs_data[$k]['am_pm']      = $up_d; //上午
                    $bs_data[$k]['kq_tee']     = $data['tee'][$key];
                    //$bs_data[$k]['time']       = date("Y-m-d H:i:s",$bs_data[$k]['start_time'] );//测试
                }
            }
            if($start_time > ( $data['rest_starttime']-$data['game_jg_time'])) $pm++;
            $kk++;
       }

   }
   else
   {  $dt  = $this->member->from(null, 'id')->where(' groupid =4')->select(); 
	 
	       $list = array(); 
			foreach ($dt as $t) 
			{		  
			$list[] = $t['id'];  
			}
			 
			//取余 得到$list元素的位置
			$num=$order%$total; 
		
			$data['suid']  =$list[$num]; 
	
   */
        /*上午分组*/
          $k=$kk=$t=0;
    for($j=1;$j<=$tee_num;$j++){
      for($i=1;$i<=$am_kq_num;$i++){
		  $k++; 
          $bs_data[$k]['start_time'] = $data['game_starttime'] +  ($kk * $data['game_jg_time']);
		  $bs_data[$k]['am_pm']      = 1; //上午
		  
		  $num=($k-1)%$tee_num; 
		  $bs_data[$k]['kq_tee']     = $data['tee'][$num];
		   
		  if($k%2==0)
		  {$kk++;} 
      }
	  //echo $t;
	  $t++; //空口游标 
    }
/*}*/
  
        /*下午分组*/
 $kk=0;$t=0;
   $pm_kq_num=ceil(($fz_num -($am_kq_num*$tee_num))/$tee_num);

    for($j=1;$j<=$tee_num;$j++){
      for($i=1;$i<=$pm_kq_num;$i++){
		  $k++;		  
		$bs_data[$k]['start_time'] =  $data['rest_endtime'] +  ($kk * $data['game_jg_time']);
		$bs_data[$k]['am_pm']= 2; //下午
		  $num=($k-1)%$tee_num; 
		  $bs_data[$k]['kq_tee']     = $data['tee'][$num];
           if($k%2==0)
		  {$kk++;} 
      }
	  //echo $t;
	  $t++; //空口游标
	   
    }
 

    $i=0; $z=0;


	foreach($members_list as $rows){
		if($i%$data['team_member_num']==0) $z++;
		$bs_data[$z]['users'][$i]['uid']      = $rows['uid'];
		$bs_data[$z]['users'][$i]['realname'] = $rows['realname'];
        $bs_data[$z]['users'][$i]['golf_team_id']  = $rows['team_id'];
        $bs_data[$z]['users'][$i]['golf_team_name']  = $rows['team_name'];
        $bs_data[$z]['users'][$i]['chadian']     = $rows['chadian'];
		 
 //$bs_data[$z]['users']= sortByCol($bs_data[$z]['users'], 'chadian', SORT_ASC);  
        $i++;
	}

 
 //array_multisort($start_time, SORT_ASC,$bs_data); //对相同差点的人 从新排序  
   $insert_data['sid']        = $_G['uid'];
   $insert_data['fenz_id']    = $data['fenz_id'];
   $insert_data['field_id']   = $data['field'];
  
 //print_r($bs_data); exit;
 
  $fenz_arr = array(
                 '1'=>'1',
                 '3'=>'2',
                 '5'=>'3',
                 '7'=>'4',
                 '9'=>'5',
                 '11'=>'6',
                 '13'=>'7',
                 '15'=>'8',
                 '17'=>'9',
                 '19'=>'10',
                 '21'=>'11',
                 '23'=>'12',
                 '25'=>'13',
                 '27'=>'14',
                 '29'=>'15',
                 '31'=>'16',
                 '33'=>'17',
                 '35'=>'18',
                 '37'=>'19',
                 '39'=>'20',
                 '41'=>'21',
                 '43'=>'22',
                 '45'=>'23',
                 '47'=>'24',
                 '49'=>'25',
                 '51'=>'26',
                 '53'=>'27',
                 '55'=>'28',
                 '57'=>'29',
                 '59'=>'30',
                 '61'=>'31',
                 '63'=>'32',
                 '65'=>'33',
                 '67'=>'34',
                 '69'=>'35',
                 '61'=>'36',
                 '63'=>'37',
                 '65'=>'38',
                 '67'=>'39',
                 '69'=>'40',
                 '71'=>'41',
                 '73'=>'42',
                 '75'=>'43',
                 '77'=>'44',
                 '79'=>'45',
                 '81'=>'46',
                 '83'=>'47',
                 '85'=>'48',
                 '87'=>'49',
                 '89'=>'50',
                 ); 
   foreach($bs_data as $key=>$value){
     foreach($value['users'] as $k =>$v){
                $v['start_time'] = $value['start_time'];
                $v['am_pm']      = $value['am_pm'];
                $v['tee']        = $value['kq_tee']; 
                $v['team_number']= $key;
                $rows = array_merge($v,$insert_data);
                DB::insert('fenzu_members',$rows);
            }
   }
 $query = DB::query("SELECT * FROM ".DB::table('fenzu_members')."  where tee=1 and  team_number>1 and sid=".$_G['uid']." and fenz_id = ".$insert_data['fenz_id']." and field_id=".$insert_data['field_id']); 
    while($result = DB::fetch( $query )) { 
	$tnum=$result['team_number'];
	if($tnum){
	$fenz=$fenz_arr[$tnum];
	 if($fenz) DB::query("update ".DB::table("fenzu_members")." set team_number=$fenz   where team_number>1 and fz_m_id=".$result['fz_m_id']); 
     }
	}
  
	   $team_number =DB::result_first("SELECT team_number FROM ".DB::table("fenzu_members")." where  tee=1 and  team_number>1 and sid=".$_G['uid']." and fenz_id = ".$insert_data['fenz_id']." and field_id=".$insert_data['field_id']." order by team_number desc"); 
	   if($team_number){
		   $team_number=$team_number;
	   }else{
	   
	   $team_number=1;
	   }
	   
	   $cc =DB::result_first("SELECT uid FROM ".DB::table("fenzu_members")." where  tee=10 and  team_number>1 and sid=".$_G['uid']." and fenz_id = ".$insert_data['fenz_id']." and field_id=".$insert_data['field_id']); 
	 if($cc)DB::query("update ".DB::table("fenzu_members")." set team_number=team_number/2+$team_number   where tee=10 and  team_number>1 and sid=".$_G['uid']." and fenz_id = ".$insert_data['fenz_id']." and field_id=".$insert_data['field_id']); 

   /*赛事分组 数据*/
   $golf_event_rule_info = array(
        'sid'         => $_G['uid'],
        'fenz_id'     => $data['fenz_id'],
        'field_id'    => $data['field'],
        'game_jg_time'=> getgpc('game_jg_time'),
        'fenzu_rule'  => $data['fenzu_rule'],
        'team_member_num'=>$data['team_member_num'],
        'addtime'     =>time()
       );
   DB::insert("event_fenzu_rule_list",$golf_event_rule_info);
   showmessage("分组成功",'home.php?mod=spacecp&ac=fenzu');


}elseif($operation == "event_fenzu_rule_list"){

    $actives = array('event_rule_list' =>' class="a"');
    $event_fenzu_rule_list = event_fenzu_rule_list($_G['uid']);
    include template("home/spacecp_event_fenzu_rule_list");


}elseif($operation =="del_rule"){


     include template('common/header_ajax');
     $id =  getgpc('id');
     $result = DB::fetch_first(" select * from ".DB::table('event_fenzu_rule_list')." where e_r_id=".$id);
     DB::delete("fenzu_members",array('sid'=>$_G['uid'],'fenz_id'=>$result['fenz_id'],'field_id'=>$result['field_id']));
    //DB::delete("golf_nd_baofen",array('sid'=>$_G['uid'],'fenzhan_id'=>$result['fenz_id'],'field_id'=>$result['field_id']));
     DB::delete('event_fenzu_rule_list',array('e_r_id'=>$id));

    $event_fenzu_rule_list = event_fenzu_rule_list($_G['uid']);

    if ($event_fenzu_rule_list){
           $i = 0;
           foreach ($event_fenzu_rule_list as $key => $value){
             $i++; $class="";
             if($i%2==0){ $class = 'classs="alt"';}
             echo "<tr ".$class." id=\"rule\">".
                  "<td>".$value['fenzhan_name']."</td>".
                  "<td>".$value['field_name']."</td>".
                  "<td>".$fenzu_rule[$value['fenzu_rule']]."</td>".
                  "<td>".$value['team_member_num']."人组</td>".
                  "<td>". date('Y-m-d h:i:s',$value['addtime'])."</td>".
                "<td><a  href=\"javascript:;\" onclick=\"showDialog('你确定删除此规则？','confirm','信息提示：','del_rule(".$value['e_r_id'].")')\">【删除规则】".
                "</a> |".
                "<a href=\"javascript:;\" onclick=\"showDialog('确定正式分组球员没有问题？','confirm','信息提示：','start_nd(".$value['e_r_id'].")')\">【启动报分】</a></td>".
				 "<a href=\"home.php?mod=spacecp&ac=fenzu&op=start_nd&id=".$value['e_r_id']."\" >【启动报分】</a></td>".
            "</tr>";
           }
    }else{
        if (submitcheck('searchsubmit')){
             echo  ' <tr>'.
                   ' <td colspan="8" style="vertical-align:middle; text-align:center; color:red;"> <font>没有相关数据 请从新搜索</font></td>'.
                   ' </tr>';
        }
    }
    include template('common/footer_ajax');

}elseif($operation == "member_allot"){


      if(submitcheck('member_allotsubmit')){
		  
            $result = DB::fetch_first("select uid,sid,fenz_id,field_id from ".DB::table("fenzu_members")." where fz_m_id =".$_G['gp_fz_m_id']);
            $data['tee']          = getgpc('tee');
            $data['team_number']  = getgpc('team_number'); 
			
			 if(getgpc('uid'))
	        { $data['uid']  = getgpc('uid');} 
			$realname= DB::result_first("select realname from ".DB::table('common_member_profile')."  where uid='".$data['uid']."' ");
			 if($realname)
	        {$data['realname']=$realname;} 

			$data['chadian'] = DB::result_first("select cahdian from ".DB::table('home_dazbm')."  where uid='".$data['uid']."' ");
            $data['start_time']   = strtotime(getgpc("start_time"));
            DB::update("fenzu_members",$data,array('fz_m_id'=>$_G['gp_fz_m_id']));

            $data['team_num']     =  $data['team_number'];
            unset($data['team_number']);
            unset($data['chadian']);
           // DB::update("golf_nd_baofen",$data,array('sid'=>$result['sid'],'fenz_id'=>$result['fenz_id'],'field_id'=>$result['field_id'],'uid'=>$result['uid']));
    $sql=" update  tbl_baofen set tee='".$data['tee']."',realname='".$data['realname']."',uid='".$data['uid']."',start_time='".$data['start_time']."',team_num='".$data['team_num']."' where sid= ".$result['sid']." and fenz_id= ".$result['fenz_id']." and field_id= ".$result['field_id']." and uid=".$data['uid']."";
  DB::query($sql);
 
            showmessage("调拨成功","home.php?mod=spacecp&ac=fenzu&op=member_allot&fz_m_id=".$_G['gp_fz_m_id']);

      }


      $fz_m_id = getgpc('fz_m_id');
      $fenzu_member_info = DB::fetch_first("SELECT * FROM ".DB::table("fenzu_members")." where fz_m_id=".$fz_m_id);

      include template("home/spacecp_fenzu_member_allot");

}elseif($operation == "stopgame"){

 
        $id = getgpc('fz_m_id'); 
        $fenz_id  = getgpc('fenz');	   
        $field_id = getgpc('field');   
         DB::update("fenzu_members",array('team_number'=>0,'tee'=>0),array('fz_m_id'=>$id));
    /*徐玉枭 更新成绩表*/
    $usid =DB::result_first("SELECT uid FROM ".DB::table("fenzu_members")." where fz_m_id=$id");
	//DB::update("golf_nd_baofen",array('g_team_id'=>0,'g_team_name'=>'','cave_18'=>-2,'tlcave'=>1001),array('uid'=>$usid,'fenz_id'=>$fenz_id,'field_id'=>$field_id));
    /*end*/

showmessage("操作成功","home.php?mod=spacecp&ac=fenzu&op=base&fenz=".$fenz_id."&field=".$field_id);


}elseif($operation == 'start_nd'){

    //include template('common/header_ajax');
    $e_r_id = getgpc('id');
    $result  = DB::fetch_first(" SELECT `sid`,`fenz_id`,`field_id` FROM ".DB::table("event_fenzu_rule_list")." where e_r_id = ".$e_r_id." and sid =".$_G['uid']);
        mt_srand(mktime());
        $bf_rand = mt_rand();

 //检查是否启动nd报分
  
    $check_nd_is_exist = DB::fetch_first(" SELECT * FROM tbl_baofen where sid=".$_G['uid']." and fenzhan_id=".$result['fenz_id']." and field_id=".$result['field_id']);

    if($check_nd_is_exist){
		
   showmessage("已经生成");
    }else{
        DB::query(" insert into tbl_baofen(fenzhan_id,field_id,uid,sid,realname,fenzu_id,tee,start_time,chadian) select fenz_id,field_id,uid,sid,realname,team_number,tee,start_time,chadian from ".DB::table("fenzu_members")." where sid=".$_G['uid']." and fenz_id = ".$result['fenz_id']." and field_id=".$result['field_id']);
      echo "<input id=\"nd_status\" value=\"0\">";
	  

   showmessage("启动成功");
    }
   // include template('common/footer_ajax'); 
	
	

}elseif($operation == 'baofen'){
	 $aa  = $_G['gp_aa']; 
	 $uid  = $_G['gp_uid']; 
	 $tee  = $_G['gp_tee']; 
	 $fenz  = $_G['gp_fenz']; 
	 $field  = $_G['gp_field']; 
	 $team_num  = $_G['gp_team_num']; 
	 $start_time  =  strtotime(getgpc("start_time"));  
	 
if($aa=='addd'){
   $tt['sid']        = $_G['uid'];
   
   $realname  = $_G['gp_realname']; 
   if($uid){
   $tt['uid']    = $uid;}
   $tt['fenz_id']    = $fenz;
   $tt['field_id']   = $field;
	 $tt['start_time'] = $start_time; 
	 if($realname)
	 {$tt['realname']=$realname;}else
	 {$tt['realname'] = DB::result_first("select realname from ".DB::table('common_member_profile')."  where uid='$uid' ");
	 }

	$tt['tee']        = $tee; 
	$tt['team_num']= $team_num;
	  $sid   = $_G['uid'];
	//$tt['onlymark'] = DB::result_first("select onlymark from ".DB::table('nd_baofen_users')."  where sid='$sid' and fenzhan_id='$fenz' ");
				  
				 // echo "select onlymark from ".DB::table('nd_baofen_users')."  where sid='$sid' and fenzhan_id='$fenz'" ;exit;
               // DB::insert('golf_nd_baofen',$tt); 

        DB::query(" insert into tbl_baofen(fenzhan_id,field_id,uid,sid,realname,fenzu_id,tee,start_time) value('$fenz','$field','$uid','$sid','$realname','$team_num','$tee','$start_time')");
		
		
	  unset($tt['team_num']);
	$tt['team_number']= $team_num;			
				
                DB::insert('fenzu_members',$tt);

            showmessage("添加成功","home.php?mod=spacecp&ac=fenzu&op=baofen&aa=add");
}
 $fz_rows = $qc_rows = array();
    $query  =DB::query(" SELECT `fenzhan_id`,`fenzhan_name` from tbl_fenzhan where sid= ".$_G['uid']."");
	while($result = DB::fetch( $query )) {
        $fz_rows[] = $result;
    }

    $query = DB::query("SELECT * FROM ".DB::table('saishi_qiuc')." where sid =".$_G['uid']);
    while($result = DB::fetch( $query )) {
        $qc_rows[] = $result;
    }
	
 /*搜索动作*/
    if(submitcheck('searchsubmit')) {

        $fenzhan_id  = $_G['gp_fenz']; 
        $fenzhan_members= array();
        /*查看规则是否被删除*/ 

            if(empty($fenzhan_id)){
                showmessage("请选择 搜索的条件");
            }
            $query = DB::query(" select * from tbl_baofen where sid = ".$_G['uid']." and fenzhan_id = ".$fenzhan_id." ORDER BY tee asc,fenzu_id asc ");
 
            while($result = DB::fetch($query)) {
                $result['start_time'] =  date('Y-m-d H:i:s',$result['start_time']);
                $fenzhan_members[] = $result;
            } 

    }

    $actives = array('baofen' =>' class="a"');
    include template("home/spacecp_baofen");


}elseif($operation == "member_get"){ 

      if(submitcheck('member_getsubmit')){
            $tee         = getgpc('tee');
            $fenzu_id  = getgpc('team_num'); 
            $uid  = getgpc('uid');
			if($uid>0){
				
			$realname = DB::result_first("select realname from ".DB::table('common_member_profile')."  where uid='".$uid."' ");
			$update=",realname='$realname'";
			}
			$data['chadian'] = DB::result_first("select cahdian from ".DB::table('home_dazbm')."  where uid='".$uid."' ");
            $data['start_time']   = strtotime(getgpc("start_time"));
            $nd_id= getgpc('nd_id');
            //unset($data['team_number']);
            unset($data['chadian']); 
			//print_r($data);exit;
           // DB::update("golf_nd_baofen",$data,array('nd_id'=>$_G['gp_nd_id']));
		 $row = DB::query(" update  tbl_baofen set tee='$tee',fenzu_id='$fenzu_id',uid='$uid' ".$update."  where  baofen_id = ".$_G['gp_nd_id']);
 
			
			
            showmessage("调拨成功","home.php?mod=spacecp&ac=fenzu&op=member_get&nd_id=".$_G['gp_nd_id']);

      }


      $nd_id = getgpc('nd_id');
      $fenzu_member_info = DB::fetch_first("SELECT * FROM tbl_baofen where baofen_id=".$nd_id);

      include template("home/spacecp_fenzu_member_get");

}




function dz2excel($new_data,$type='') {
	
	
	  require_once libfile('class/excel');

    //if($type==''){ echo " 参数有误 请连续开发人员functiton_core.php dzexcel()  \$type 丢失 或 没有";}
   /*取得数据源 标识 选手名称 比赛开始时间 开球Tee台 分组标示 上/下 半场 
*/
    if($type=='dazbm_export'){	
   			 $title_arrs = array(  
			 'fz_m_id' => 'ID', 
            'uid' =>'UID',
            'realname'=>'选手名称',
            'chadian' =>'差点',
            'tee'=>'开球Tee台',
            'start_time'=>'比赛开始时间',
            'am_pm'=>'半场',
            'team_number'=>'分组',
            'fenzhan_id'=> '分站',
            'field_id' => '球场', 
    							);
    							
    }
  

    
    /*根据 原数据标识 取对应的数据库字段*/						
    if($type=='dazbm_export'){
    	 $index_field = array_keys($title_arrs);
    }
    
    
    


   
   /*重新排序*/
    foreach(array_keys($new_data) as $key=>$value){
        foreach($title_arrs as $k=>$v){
         $news_data_list[$key][$k]= $new_data[$key][$k];
        }	
    }
   
    $export = new io_xls();
    $keys = array_values($title_arrs); 
    $export->export_begin($keys,'比赛分组表',count($data));
	
    $export->export_rows($news_data_list,$index_field);
    $export->export_finish();    
}






/*获取 规则列表*/
function event_fenzu_rule_list($uid) {
    $query = DB::query(" select sq.field_name,e.*,fz.fenzhan_name from ".DB::table("event_fenzu_rule_list")." as e LEFT JOIN tbl_fenzhan as fz ON fz.fenzhan_id=e.fenz_id  LEFT JOIN ".DB::table('saishi_qiuc')." as sq ON sq.field_id = e.field_id and sq.sid=e.sid where e.sid=".$uid." and e.is_delete =0");
    while($result = DB::fetch($query)){
        $rows[] = $result;
    }
    return $rows;
}


   function sortByCol($array, $keyname, $dir = SORT_ASC)
          {
              return sortByMultiCols($array, array($keyname => $dir));
          }
          /**
           * 将一个二维数组按照多个列进行排序，类似 SQL 语句中的 ORDER BY
           *
           * 用法：
           * @code php
           * $rows = sortByMultiCols($rows, array(
           *           'parent' => SORT_ASC, 
           *           'name' => SORT_DESC,
           * ));
           * @endcode
           *
           * @param array $rowset 要排序的数组
           * @param array $args 排序的键
           *
           * @return array 排序后的数组
           */
           function sortByMultiCols($rowset, $args)
          {
              $sortArray = array();
              $sortRule = '';
              foreach ($args as $sortField => $sortDir) 
              {
                  foreach ($rowset as $offset => $row) 
                  {
                      $sortArray[$sortField][$offset] = $row[$sortField];
                  }
                  $sortRule .= '$sortArray[\'' . $sortField . '\'], ' . $sortDir . ', ';
              }
              if (empty($sortArray) || empty($sortRule)) { return $rowset; }
              eval('array_multisort(' . $sortRule . '$rowset);');
              return $rowset;
          }
?>