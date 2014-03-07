<?php
/**
 *    #Case		bwvip.com
 *    #Page		baomingAction.class.php (报名页)
 *
 */
class baomingAction extends user_publicAction
{
	public function _intialize()
	{
		parent::_intialize();
	}
	

	
	//登录后报名页
	public function baoming_add()
	{

		$this->assign('site_title','比赛报名');

		$event_id = get('event_id');

		$is_baoming = M('baoming')->where('uid = '.$_SESSION['user_id'].' and event_id = '.$event_id)->count(); 
		if($is_baoming>0)
		{
			$this->error("报名已提交审核",U('user/baoming/baoming_list'));
		}
		
    	//当前位置
		$this->assign('index','baoming_list');

		$event_info = get_event_info($event_id);

		if(!empty($event_info[0]))
		{
			$event_info[0]['fenzhan'] = M()->query('select fenzhan_id,fenzhan_name from tbl_fenzhan where event_id = '.$event_id.' and fenzhan_name <> ""');
		}

		$this->assign("event_info",$event_info);

		$user_info = get_user_info($_SESSION['user_id']);
		$this->assign("user_info",$user_info);

		$this->display();
	}

	//报名方法
	public function baoming_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$user_info = get_user_info($_SESSION['user_id']);
			
			if($user_info[0]['user_role'] == 'qingshaonian')
			{
				if($_SESSION['mobile_verify'] != md5(post('mobile_verify')))
				{
					$this->error("手机验证码有误",U('user/baoming/baoming_add',array('event_id'=>post("event_id"))));
				}
				else
				{
					/*
					$mobile_info = M()->query('select * from pre_common_member_profile where mobile = '.post('parent_mobile'));
					if(empty($mobile_info))
					{
						$this->error("家长手机号有误",U('user/baoming/baoming_add',array('event_id'=>post("event_id"))));
					}

					$user_info = get_user_info($_SESSION['user_id']);
					if($mobile_info[0]['uid'] != $user_info[0]['parent_uid'])
					{
						$this->error("家长手机号有误",U('user/baoming/baoming_add',array('event_id'=>post("event_id"))));
					}

					$data["parent_uid"]=$user_info[0]['parent_uid'];
					*/
				
					$data["uid"]=post("uid");

					$data["event_id"]=post("event_id");
					$data["baoming_realname"]=post("baoming_realname");
					$data["baoming_mobile"]=post("baoming_mobile");
					$data["baoming_email"]=post("baoming_email");
					$data["baoming_sex"]=post("baoming_sex");
					
					if(!empty($_POST['fenzhan_ids']))
					{
						$data["fenzhan_ids"] = implode(',',$_POST['fenzhan_ids']);
					}
					//$data["fenzhan_id"]=post("fenzhan_id");			
					$data["baoming_addtime"]=time();
					
					$list=M("baoming")->add($data);
					$this->redirect(U('user/baoming/baoming_success'));
				}

			}
			else
			{
				$data["uid"]=post("uid");
				$data["event_id"]=post("event_id");
				$data["baoming_realname"]=post("baoming_realname");
				$data["baoming_mobile"]=post("baoming_mobile");
				$data["baoming_email"]=post("baoming_email");
				$data["baoming_sex"]=post("baoming_sex");
				
				if(!empty($_POST['fenzhan_ids']))
				{
					$data["fenzhan_ids"] = implode(',',$_POST['fenzhan_ids']);
				}
				//$data["fenzhan_id"]=post("fenzhan_id");			
				$data["baoming_addtime"]=time();
				
				$list=M("baoming")->add($data);
				$this->redirect(U('user/baoming/baoming_success'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('user/baoming/baoming_add',array('event_id'=>post("event_id"))));
		}
	}

	//我的报名页
	public function baoming_detail()
	{
		$this->assign('site_title','我的报名');
		$this->assign('index','baoming_detail');
		
		$data["item"] = M()->query('select b.event_id,b.event_name,b.event_uid,b.event_logo,b.event_timepic,b.event_starttime,b.event_endtime,b.event_city,a.* from tbl_baoming as a,tbl_event as b where b.event_id IN (select event_id from tbl_baoming where uid = '.$_SESSION['user_id'].') and a.event_id = b.event_id order by a.baoming_addtime desc');

		$data["total"] = 0;

		if(!empty($data["item"][0]))
		{
			$data["total"] = count($data["item"]);
			
			//得到分站名称
			if(!empty($data["item"][0]['fenzhan_ids']))
			{
				$fenzhan_ids = explode(',',$data["item"][0]['fenzhan_ids']);
				foreach($fenzhan_ids as $fk=>$f)
				{
					$fenzhan_info = M()->query('select fenzhan_name from tbl_fenzhan where fenzhan_id = '.$f);
					$data["item"][0]['fenzhan_name'][$fk] = $fenzhan_info[0]['fenzhan_name'];
				}
			}
		}

		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		$this->assign("list",$data["item"]);
		$this->assign("pages",$data["pages"]);
		$this->assign("total",$data["total"]);

		$this->display();

	}

	//报名成功页
	public function baoming_success()
	{
		$this->assign('site_title','报名成功');
		$this->assign('index','baoming_list');
		
		$sort=" event_sort desc,event_addtime desc ";
		$event_list = M("event","tbl_")->order($sort)->limit(2)->select();
		$this->assign("list",$event_list);

		$this->display();
	}


	//登录后报名列表页
	public function	baoming_list()
	{
		$this->assign('site_title','比赛报名');
		//当前位置
		$this->assign('index','baoming_list');

		$event_list = D("event")->event_list_pro();
		$this->assign("list",$event_list["item"]);
		$this->assign("pages",$event_list["pages"]);
		$this->assign("total",$event_list["total"]);

    	$this->display();
	}


	//赛事直播
	public function event_zhibo()
	{
		$this->assign('site_title','比赛直播');
		//当前位置
		$this->assign('index','event_zhibo');

		$event_list = D("event")->event_list_pro();
		$this->assign("list",$event_list["item"]);
		$this->assign("pages",$event_list["pages"]);
		$this->assign("total",$event_list["total"]);

    	$this->display();
	}

	//赛事直播详情
	public function event_detail()
	{
		$this->assign('site_title','比赛详情');

		//当前位置
		$this->assign('index','event_zhibo');
		
		$event_id = get('event_id');

		$event_info = get_event_info($event_id);
		
		$this->assign("event_info",$event_info);

    	$this->display();
	}

	public function score_list()
	{
		$this->assign('site_title','我的成绩卡');
		//当前位置
		$this->assign('index','score_list');
		
		//$uid = $_SESSION['user_id'];
		$uid = '1899419';

		$data["item"] = M()->query('select * from tbl_baofen where uid='.$uid.' order by addtime desc');
		

		if(!empty($data["item"]))
		{
			foreach($data["item"] as $sk=>$s)
			{

				$data["item"][$sk]['par_array'] = '';
				$data["item"][$sk]['score_array'] = '';

				if(!empty($s['par']))
				{
					$par_array = explode('|',$s['par']);
					$data["item"][$sk]['par_array'] = $par_array;
				}

				if(!empty($s['score']))
				{
					$score_array = explode('|',$s['score']);
					$data["item"][$sk]['score_array'] = $score_array;
				}

				$event_info = get_event_info($s['event_id']);
				$data["item"][$sk]['event_name'] = $event_info[0]['event_name'];

			}
		}


		$data["total"] = 0;

		if(!empty($data["item"][0]))
		{
			$data["total"] = count($data["item"]);
		}

		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		$this->assign("list",$data["item"]);
		$this->assign("pages",$data["pages"]);
		$this->assign("total",$data["total"]);

		$this->display();

	}

	//我的成绩卡
	public function score_detail()
	{

		$baofen_id = get('baofen_id');
		//当前位置
		$this->assign('index','score_list');
		
		$score_info = M()->query('select * from tbl_baofen where baofen_id='.$baofen_id);

		if(!empty($score_info))
		{
			foreach($score_info as $sk=>$s)
			{
				$score_info[$sk]['par_array'] = '';
				$score_info[$sk]['score_array'] = '';

				if(!empty($s['par']))
				{
					$par_array = explode('|',$s['par']);
					$score_info[$sk]['par_array'] = $par_array;
				}

				if(!empty($s['score']))
				{
					$score_array = explode('|',$s['score']);
					$score_info[$sk]['score_array'] = $score_array;
				}
				

				$event_info = get_event_info($s['event_id']);
				$score_info[$sk]['event_name'] = $event_info[0]['event_name'];

			}
		}

		$this->assign('site_title',$event_info[0]['event_name'].'的成绩卡');

		$this->assign("score_info",$score_info);

		$this->display();
	}
}
?>