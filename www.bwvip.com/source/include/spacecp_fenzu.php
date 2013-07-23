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
$operation = in_array($_GET['op'], array('base', 'members_fenzu', 'm_fenzu_submit', 'event_fenzu_rule_list', 'del_rule', 'member_allot', 'start_nd')) ? trim($_GET['op']) : $defaultop;

$fenzu_rule =array(
    '1'=>'队籍交叉分组',
    '2'=>'差点高低分组',
    '3'=>'会员随机分组'
);




if($operation=='base'){

    $actives = array('m_list' =>' class="a"');

    $fz_rows = $qc_rows = array();
    $query  =DB::query(" SELECT `fz_id`,`fenz_name` from ".DB::table('fenzhan')." where sid= ".$_G['uid']."");
	while($result = DB::fetch( $query )) {
        $fz_rows[] = $result;
    }

    $query = DB::query("SELECT * FROM ".DB::table('saishi_qiuc')." where sid =".$_G['uid']);
    while($result = DB::fetch( $query )) {
        $qc_rows[] = $result;
    }

    /*搜索动作*/
    if(submitcheck('searchsubmit')) {

        $fenz_id  = getgpc('fenz');
        $field_id = getgpc('field');
        $fenzhan_members= array();
        /*查看规则是否被删除*/
        $event_rule_query = DB::result_first("SELECT * FROM ".DB::table('event_fenzu_rule_list')." where fenz_id=".$fenz_id." and field_id=".$field_id." and is_delete=0 ");
        if($event_rule_query){

            if(empty($fenz_id) || empty($field_id)){
                showmessage("请选择 搜索的条件");
            }
            $query = DB::query(" select * from ".DB::table('fenzu_members')." where sid = ".$_G['uid']." and fenz_id = ".$fenz_id." and field_id =".$field_id." ORDER BY team_number asc");

            while($result = DB::fetch($query)) {
                $result['start_time'] =  date('Y-m-d H:i:s',$result['start_time']);
                $fenzhan_members[] = $result;
            }
        }

    }

    include template("home/spacecp_fenzu");


}elseif($operation == 'members_fenzu'){


    $fz_rows = $qc_rows = array();
    $query  =DB::query(" SELECT `fz_id`,`fenz_name` from ".DB::table('fenzhan')." where sid= ".$_G['uid']."");
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
    if($is_fenzu) showmessage("该分站下 已经分组 你可以删除 从新分组");

    $data['fenz_id']        = getgpc('fenz');
    $data['field']          = getgpc('field');
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


    $members_list=array();

    /*分站会员列表*/
    $query = DB::query("select *  from ".DB::table("fenzhan_members")." where sid=".$_G['uid']." and fz_id = ".$data['fenz_id'] );
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


   /*针对 一上午 或 一下午能够打完比赛的*/
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
   {
        /*上午分组*/
        $k=$kk=$t=0;
        for($j=1;$j<=$tee_num;$j++){
          for($i=1;$i<=$am_kq_num;$i++){
              if($k < $fz_num ){
                  $k++;
                  $bs_data[$k]['start_time'] = $data['game_starttime'] +  ($kk * $data['game_jg_time']);
                  $bs_data[$k]['am_pm']      = 1; //上午
                  $bs_data[$k]['kq_tee']     = $data['tee'][$t];
                  //$bs_data[$k]['time']       = date("Y-m-d H:i:s",$bs_data[$k]['start_time'] ); //测试
                  $kk++;

              }
          }
          $t++;
          $kk=0;
        }
   }


    if($k < $fz_num)
    {
        /*下午分组*/
        $kk=1;$t=0;
        $pm_kq_num=ceil(($fz_num -($am_kq_num*$tee_num))/$tee_num);
        for($j=1;$j<=$tee_num;$j++){
            for($i=1;$i<=$pm_kq_num;$i++){
                $k++;
                $bs_data[$k]['start_time'] =  $data['rest_endtime'] +  ($kk * $data['game_jg_time']);
                $bs_data[$k]['am_pm']= 2; //下午
                $bs_data[$k]['kq_tee']=$data['tee'][$t];
                $kk++;
            }
        $t++; //空口游标
        $kk=0;
        }

    }


    $i=0; $z=0;

	foreach($members_list as $rows){
		if($i%$data['team_member_num']==0) $z++;
		$bs_data[$z]['users'][$i]['uid']      = $rows['uid'];
		$bs_data[$z]['users'][$i]['realname'] = $rows['realname'];
        $bs_data[$z]['users'][$i]['golf_team_id']  = $rows['team_id'];
        $bs_data[$z]['users'][$i]['golf_team_name']  = $rows['team_name'];
        $i++;
	}

   $insert_data['sid']        = $_G['uid'];
   $insert_data['fenz_id']    = $data['fenz_id'];
   $insert_data['field_id']   = $data['field'];


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
     DB::delete('event_fenzu_rule_list',array('e_r_id'=>$id));

    $event_fenzu_rule_list = event_fenzu_rule_list($_G['uid']);

    if ($event_fenzu_rule_list){
           $i = 0;
           foreach ($event_fenzu_rule_list as $key => $value){
             $i++; $class="";
             if($i%2==0){ $class = 'classs="alt"';}
             echo "<tr ".$class." id=\"rule\">".
                  "<td>".$value['fenz_name']."</td>".
                  "<td>".$value['field_name']."</td>".
                  "<td>".$fenzu_rule[$value['fenzu_rule']]."</td>".
                  "<td>".$value['team_member_num']."人组</td>".
                  "<td>". date('Y-m-d h:i:s',$value['addtime'])."</td>".
                "<td><a  href=\"javascript:;\" onclick=\"showDialog('你确定删除此规则？','confirm','信息提示：','del_rule(".$value['e_r_id'].")')\">【删除规则】".
                "</a> |".
                "<a href=\"javascript:;\" onclick=\"showDialog('确定正式分组球员没有问题？','confirm','信息提示：','start_nd(".$value['e_r_id'].")')\">【启动报分】</a></td>".
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
            $data['tee']          = getgpc('tee');
            $data['team_number']  = getgpc('team_number');
            $data['start_time']   = strtotime(getgpc("start_time"));
            DB::update("fenzu_members",$data,array('fz_m_id'=>$_G['gp_fz_m_id']));

            $result = DB::fetch_first("select uid,sid,fenz_id,field_id from ".DB::table("fenzu_members")." where fz_m_id =".$_G['gp_fz_m_id']);
            $data['team_num']     =  $data['team_number'];
            unset($data['team_number']);
            DB::update("golf_nd_baofen",$data,array('sid'=>$result['sid'],'fenz_id'=>$result['fenz_id'],'field_id'=>$result['field_id'],'uid'=>$result['uid']));

            showmessage("调拨成功","home.php?mod=spacecp&ac=fenzu&op=member_allot&fz_m_id=".$_G['gp_fz_m_id']);

      }


      $fz_m_id = getgpc('fz_m_id');
      $fenzu_member_info = DB::fetch_first("SELECT * FROM ".DB::table("fenzu_members")." where fz_m_id=".$fz_m_id);

      include template("home/spacecp_fenzu_member_allot");

}elseif($operation == 'start_nd'){

    include template('common/header_ajax');
    $e_r_id = getgpc('id');
    $result  = DB::fetch_first(" SELECT `sid`,`fenz_id`,`field_id` FROM ".DB::table("event_fenzu_rule_list")." where e_r_id = ".$e_r_id." and sid =".$_G['uid']);

  //检查是否启动nd报分
    $check_nd_is_exist = DB::fetch_first(" SELECT * FROM ".DB::table("golf_nd_baofen")." where sid=".$_G['uid']." and fenz_id=".$result['fenz_id']." and field_id=".$result['field_id']);

    if($check_nd_is_exist){
        echo '<input id="nd_status" value="1" type="hidden">';
    }else{
        mt_srand(mktime());
        $bf_rand = mt_rand();
        DB::query(" insert into ".DB::table("golf_nd_baofen")."(fenz_id,field_id,uid,sid,realname,g_team_id,g_team_name,team_num,tee,start_time,onlymark) select fenz_id,field_id,uid,sid,realname,golf_team_id,golf_team_name,team_number,tee,start_time,".$bf_rand." from ".DB::table("fenzu_members")." where sid=".$_G['uid']." and fenz_id = ".$result['fenz_id']." and field_id=".$result['field_id']);

        /*更新报分员 的 唯一标示*/
        DB::update("nd_baofen_users",array('onlymark'=>$bf_rand),array('sid'=>$_G['uid'],'fz_id'=>$result['fenz_id'],'fieldid'=>$result['field_id']));
        echo "<input id=\"nd_status\" value=\"0\">";
    }
    include template('common/footer_ajax');

}





/*获取 规则列表*/
function event_fenzu_rule_list($uid) {
    $query = DB::query(" select sq.field_name,e.*,fz.fenz_name from ".DB::table("event_fenzu_rule_list")." as e LEFT JOIN ".DB::table('fenzhan')." as fz ON fz.fz_id=e.fenz_id  LEFT JOIN ".DB::table('saishi_qiuc')." as sq ON sq.field_id = e.field_id and sq.sid=e.sid where e.sid=".$uid." and e.is_delete =0");
    while($result = DB::fetch($query)){
        $rows[] = $result;
    }
    return $rows;
}

?>