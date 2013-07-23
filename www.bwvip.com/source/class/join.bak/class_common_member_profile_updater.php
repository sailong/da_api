<?php
	class common_member_profile_updater{
		public function update_by_uid($uid,$realname,$gender,$birthyear,$birthmonth,$birthday,$nationality,$mobile,$telephone,$idcardtype,$idcard,$address,$zipcode){
			$data = array(
				'realname'=>$realname,
				'gender'=>$gender,
				'birthyear'=>$birthyear,
				'birthmonth'=>$birthmonth,
				'birthday'=>$birthday,
				'nationality'=>$nationality,
				'mobile'=>$mobile,
				'telephone'=>$telephone,
				'idcardtype'=>$idcardtype,
				'idcard'=>$idcard,
				'address'=>$address,
				'zipcode'=>$zipcode
			);
			$condition = array(
                                'uid'=>$uid
                        );
			DB::update('common_member_profile',$data,$condition);
		}
	}
?>
