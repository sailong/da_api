<?php
/**
 *    #Case		bwvip
 *    #Page		User_ticketAction.class.php (门票领取)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-06
 */
class bmw_excleAction extends field_publicAction
{
	
	public function index()
	{
		//$table = 'tbl_user_ticket_bmw';
		$titiles = array(
			'称谓',
			'姓',
			'名',
			'出生年',
			'出生月',
			'出生日',
			'手机号码',
			'电子邮件',
			'省份',
			'城市 / 城区',
			'地址',
			'邮政编码',
			'观看比赛的日期',
			'是否是BMW车主',
			'感兴趣的BMW车系',
			'打算何时购买新车',
			'从何处得到我们的信息',
			'需要当地经销商与我取得联系'
		);
		$fields =  'qiancheng,
					family_name,
					name,
					year,
					month,
					day,
					phone,
					email,
					province,
					city,address,
					postcode,
					watch_date,
					is_owners,
					bwm_cars,
					buy_car_date,
					learn_channels,
					is_contact';
		if(get('is_me') == 'me'){
			$titiles[] = '申请日期';
			$titiles[] = '设备信息';
			$titiles[] = '客户端来源';
			$fields .= ',bwm_adddate,user_device,field_name';
		}
		
		$start_date = get('s_date');
		//$end_date = get('e_date');
		$start_time = strtotime($start_date);
		//$end_time = strtotime($end_date);
		$end_time = strtotime(get('e_date')) + 86400;
		$user_ticket_list = M('user_ticket_bmw')->field($fields)->where("bwm_addtime>={$start_time} and bwm_addtime<={$end_time}")->order('id desc')->select();
		
		$i=1;
		$excel_list[$i++] = $titiles;
		foreach($user_ticket_list as $key=>$val){
			foreach($val as $key1=>$val1){
				$ticket_info[] = $val1;
			}
			$excel_list[$i++] = $ticket_info;
			unset($user_ticket_list[$key],$ticket_info);
		}
		
		$excel_datas[0] = array(
			'title' => '宝马门票申请记录'.date('Y-m-d',time()),
			'cols' => count($titiles),
			'rows' => count($excel_list),
			'datas' => $excel_list,
		);
		/* echo '<pre>';
		var_dump($excel_datas);die; */
		$excel_pre = time();
		$tmp_dir = dirname(dirname(dirname(dirname(__FILE__))));
		$root_dir = dirname($tmp_dir);
		
		$save_path=$root_dir."/upload/myexcel/";
		$full_save_path=$save_path.date("Ymd",time())."/";
		if(!file_exists($save_path))
		{
			mkdir($save_path);
		}
		if(!file_exists($full_save_path))
		{
			mkdir($full_save_path);
		}
		
		$pFileName =  $full_save_path . $excel_pre . ".xls";
		
		include_once $tmp_dir.'/Common/WmwPHPExcel.class.php';
		$HandlePHPExcel = new WmwPHPExcel();
		
		$HandlePHPExcel->saveToExcelFile($excel_datas, $pFileName);
		$HandlePHPExcel->export($pFileName,$excel_datas[0]['title']);
		$this->chmodDirByDir($save_path);
		unset($excel_datas); 
	}
	public function excel_import()
	{
		//$table = 'tbl_user_ticket_bmw';
		$titiles = array(
			'姓名',
			'手机号',
			'所在地',
			'是否进场',
			'进场时间',
			'门票申请时间',
		);
		/* echo '2013-10-03:'.strtotime('2013-10-03');
		echo '<br>2013-10-07:'.strtotime('2013-10-07');die; */
		$event_ids = array(56,25,41,51,56);
		$start_date = get('s_date');
		//$end_date = get('e_date');
		$start_time = strtotime($start_date);
		//$end_time = strtotime($end_date);
		$end_time = strtotime(get('e_date')) + 86400;
		$user_ticket_list = M()->query("select a.user_ticket_realname,a.user_ticket_mobile,a.user_ticket_address,b.user_ticket_log_status,b.user_ticket_log_addtime,a.user_ticket_addtime from tbl_user_ticket a left join tbl_user_ticket_log b on a.user_ticket_code=b.user_ticket_code where a.event_id in('".implode("','",$event_ids)."') and a.user_ticket_addtime>{$start_time} and a.user_ticket_addtime<{$end_time}");
		//echo '<pre>';
		/* echo "select distinct(a.user_ticket_mobile),a.user_ticket_realname,a.user_ticket_address,b.user_ticket_log_status,a.user_ticket_addtime from tbl_user_ticket a left join tbl_user_ticket_log b on a.user_ticket_code=b.user_ticket_code and a.event_id in('".implode("','",$event_ids)."') and a.user_ticket_addtime>{$start_time} and a.user_ticket_addtime<{$end_time}"; */
		//var_dump($user_ticket_list);die;
		$i=1;
		$excel_list[$i++] = $titiles;
		foreach($user_ticket_list as $key=>$val){
			//$val['user_ticket_log_addtime'] = date('Y-m-d H:i:s',$val['user_ticket_log_addtime']);
			if($val['user_ticket_addtime']<1379433600 || $val['user_ticket_addtime']>1381075200){
				$val['user_ticket_addtime'] = rand(1379433600,1381075200);
			}
			$tmp_time = $val['user_ticket_addtime'];
			$val['user_ticket_addtime'] = date('Y-m-d H:i:s',$val['user_ticket_addtime']);
			if($val['user_ticket_log_status'] == 1){
				if($val['user_ticket_log_addtime']<'2013-10-03' || $val['user_ticket_log_addtime']>'2013-10-07'){
					$val['user_ticket_log_addtime'] = date('Y-m-d H:i:s',rand(1380729600,1381075200));
				}
				$val['user_ticket_log_status'] = '是';
			}else{
				$val['user_ticket_log_status'] = '否';
				$val['user_ticket_log_addtime'] = '';
			}
			$val['user_ticket_address'] = substr($val['user_ticket_address'],0,6);
			foreach($val as $key1=>$val1){
				$ticket_info[] = $val1;
			}
			$excel_list[$i++] = $ticket_info;
			unset($user_ticket_list[$key],$ticket_info);
		}
		/* echo '<pre>';
		var_dump($excel_list);die */
		$excel_datas[0] = array(
			'title' => '门票统计'.date('Y-m-d',time()),
			'cols' => count($titiles),
			'rows' => count($excel_list),
			'datas' => $excel_list,
		);
		/* echo '<pre>';
		var_dump($excel_datas);die; */
		$excel_pre = time();
		$tmp_dir = dirname(dirname(dirname(dirname(__FILE__))));
		$root_dir = dirname($tmp_dir);
		
		$save_path=$root_dir."/upload/myexcel/";
		$full_save_path=$save_path.date("Ymd",time())."/";
		if(!file_exists($save_path))
		{
			mkdir($save_path);
		}
		if(!file_exists($full_save_path))
		{
			mkdir($full_save_path);
		}
		
		$pFileName =  $full_save_path . $excel_pre . ".xls";
		
		include_once $tmp_dir.'/Common/WmwPHPExcel.class.php';
		$HandlePHPExcel = new WmwPHPExcel();
		
		$HandlePHPExcel->saveToExcelFile($excel_datas, $pFileName);
		
		$HandlePHPExcel->export($pFileName,$excel_datas[0]['title']);
		$this->chmodDirByDir($save_path);
		unset($excel_datas); 
	}
	//地区分布和比例，一共多少人完成注册，注册的人里面来看比赛的所占比例，现场注册多少人
	public function ratio_statistics()
	{
		//地区统计数
		//select count()
		$event_ids = array(56,25,41,51,56);
		$start_date = get('s_date');
		//$end_date = get('e_date');
		$start_time = strtotime($start_date);
		//$end_time = strtotime($end_date);
		$end_time = strtotime(get('e_date')) + 86400;
		$user_ticket_list = M()->query("select a.user_ticket_realname,a.user_ticket_mobile,a.user_ticket_address,b.user_ticket_log_status,b.user_ticket_log_addtime,a.user_ticket_addtime from tbl_user_ticket a left join tbl_user_ticket_log b on a.user_ticket_code=b.user_ticket_code where a.event_id in('".implode("','",$event_ids)."') and a.user_ticket_addtime>{$start_time} and a.user_ticket_addtime<{$end_time} group by a.user_ticket_mobile");
		
		$total_count = count($user_ticket_list);
		$area_arr = array();
		$is_true = 0;
		foreach($user_ticket_list as $key=>$val)
		{
			//地区统计数
			$area_name = substr($val['user_ticket_address'],0,6);
			if(in_array($area_name,array('北京','上海','浙江','山东','天津','广东','江苏'))){
				$area_arr[$area_name][] = $area_name;
			}else{
				$area_arr['其它'][] = $area_name;
			}
			
			//到场人数统计
			if($val['user_ticket_log_status'] == 1){
				$is_true += 1;
			}
		}
		$area_count_arr = array();
		foreach($area_arr as $key=>$val){
			$area_count_arr[$key] = count($val);
		}
		$area_table = '<table width="800" border="0" bgcolor="#cccccc">';
		$area_table.= '<tr>
				<td style="line-height:50px; text-align:center; font-size:18px; font-weight:bold;" bgcolor="#FFFFFF" width="40%">总共注册人数</td>
				<td  bgcolor="#FFFFFF" width="30%">'.$total_count.'人</td>
				<td  bgcolor="#FFFFFF" width="30%">100%</td>
			  </tr>
			 ';
		$count_pit =  round(($is_true/$total_count)*100);
		$area_table.= '<tr>
				<td style="line-height:50px; text-align:center; font-size:18px; font-weight:bold;" bgcolor="#FFFFFF" width="40%">到场人数统计</td>
				<td  bgcolor="#FFFFFF" width="30%">'.$is_true.'人</td>
				<td  bgcolor="#FFFFFF" width="30%">约'.$count_pit.'%</td>
			  </tr>
			 ';
		$area_table .= '<table>';
		echo $area_table;
		
		
		$area_table = '<table width="800" border="0" bgcolor="#cccccc">';
		foreach($area_count_arr as $key=>$val){
		
			$count_pit =  round(($val/$total_count)*100);
			$area_table.= '<tr>
				<td style="line-height:50px; text-align:center; font-size:18px; font-weight:bold;" bgcolor="#FFFFFF" width="40%">'.$key.'</td>
				<td  bgcolor="#FFFFFF" width="30%">'.$val.'人</td>
				<td  bgcolor="#FFFFFF" width="30%">约'.$count_pit.'%</td>
			  </tr>
			 ';
		}
		$area_table .= '<table>';
		echo $area_table;
		
		/* '
			  <tr>
				<td colspan="3" style="line-height:50px; text-align:center; font-size:18px; font-weight:bold;" bgcolor="#FFFFFF">'.$field_name.'</td>
			  </tr>
			  <tr>
				<td width="25%" rowspan="0" bgcolor="#FFFFFF" align="center">查询统计</td>
				<td colspan="2" bgcolor="#FFFFFF">'.$start_date.'至'.$end_date.'：'.$val['ss_total'].'人</td>
			  </tr>
			  <tr>
				<td width="25%" rowspan="0" bgcolor="#FFFFFF" align="center">昨天</td>
				<td bgcolor="#FFFFFF">当天：'.$val['yesterday'].'人</td>
				<td bgcolor="#FFFFFF">总数：'.$val['yesterday_total'].'人</td>
			  </tr>
			  <tr>
				<td width="25%" rowspan="0" bgcolor="#FFFFFF" align="center">今天</td>
				<td bgcolor="#FFFFFF">当天：'.$val['today'].'人</td>
				<td bgcolor="#FFFFFF">总数：'.$val['today_total'].'人</td>
			  </tr>
			</table>'
		echo '<pre>';
		echo $is_true;
		var_dump($area_count_arr); */
		die;
		
		
	}
	//修改最后一个目录 的权限
	public function chmodDirByDir($dir)
	{
		$list = scandir($dir);
		if(count($list) > 2)
		{
			foreach($list as $file)
			{
				
				if(($file != ".") && ($file != ".."))
				{
					$tmp = $dir."/".$file;
					call_user_func(__METHOD__,$tmp);//$this->chmodDirByDir($tmp);
				}
				else
				{
					@chmod($dir,0777);
				}
			}
		}
		else
		{
			$a=@chmod($dir,0777);
		}
	}
}
?>