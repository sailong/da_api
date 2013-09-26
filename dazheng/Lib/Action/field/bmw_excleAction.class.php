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