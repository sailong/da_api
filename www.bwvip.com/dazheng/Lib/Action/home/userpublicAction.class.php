<?php
 /**
     * 探矿工程 userPublic 
     * jack 20130201
     *
     */
class userpublicAction extends Action
{
	public function _initialize()
	{
		if(!isset($_SESSION['user_id']))
		{
			$this->error("请登录",U('home/public/login'));
		}

	}



}
?>