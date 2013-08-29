<?php
class AdminAuthAction extends Action {

	public function _initialize()
	{

		if(!isset($_SESSION['admin_id']))
		{
			$this->error("请登录",U('admin/public/login'));
		}

	}


}