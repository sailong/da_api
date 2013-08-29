<?php
/**
 *    #Case		bwvip
 *    #Page		QiutongAction.class.php (球童)
 *
 *    @author		Zhang Long
 *    @E-mail		123695069@qq.com
 */
class qiutongAction extends field_publicAction
{

	public function _basic()	
	{
		parent::_basic();
	}
   
	public function qiutong()
	{
	    $where = '';
	    $k = get('k');
	    $where = " field_uid='{$_SESSION["field_uid"]}'";
	    if(!empty($k)) {
	        $where .= " and qiutong_name like '%{$k}%'";
	    }
	    
		$list=D("qiutong")->qiutong_list_pro($where);
		foreach($list["item"] as $key=>&$val) {
		    if(strlen($val['qiutong_content'])>20) {
		        $val['qiutong_content'] = mb_substr($val['qiutong_content'],0,20,'utf8').'...';
		    }
		}
		
		foreach($list['item'] as $key=>$value){
			if(!empty($value['category_id'])){
				$category_ids[$value['category_id']] = $value['category_id'];
			}
		}
		$category_data = M('category')->where("category_id in('".implode("','",$category_ids)."')")->select();
		
		foreach($category_data as $key=>$value){
			$category_list[$value['category_id']] =  $value;
		}
		unset($category_ids,$category_data);
		
		$this->assign("category_list",$category_list);
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","球童");
    	$this->display();
	}

	public function qiutong_add()
	{
		
		$category_list = $this->get_category_list();
		
		$this->assign('category_list',$category_list);
		$this->assign("page_title","添加球童");
    	$this->display();
	}

	public function qiutong_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["qiutong_number"]=post("qiutong_number");
			$data["qiutong_name"]=post("qiutong_name");
			$data["qiutong_name_en"]=post("qiutong_name_en");
			if(empty($data["qiutong_number"])) {
			    $this->error("添加失败",U('field/qiutong/qiutong_add'));
			}
			if(!$this->check_qiutong_number_unique($data["qiutong_number"])) {
			    $this->error("添加失败,球童编号不可重复",U('field/qiutong/qiutong_add'));
			}
			$uid = post("uid");//$this->reg_act($data["qiutong_number"]);
			if(empty($uid)) {
			    $uid = '';
			    //$this->error("添加失败",U('field/qiutong/qiutong_add'));
			}
			if($_FILES["qiutong_photo"]["error"]==0)
			{
				$uploadinfo=$this->upd_qiutong_photo();//upload_file("upload/qiutong/");
				$data["qiutong_photo"]=$uploadinfo;
			}
			$data["field_uid"]=$_SESSION["field_uid"];//post("field_uid");
			$data["uid"]=$uid;
			$data["category_id"]=post("category_id");
			$data["qiutong_content"]=post("qiutong_content");
			$data["qiutong_addtime"]=time();
			$list=M("qiutong")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('field/qiutong/qiutong'));
			}
			else
			{				
				$this->error("添加失败",U('field/qiutong/qiutong_add'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('field/qiutong/qiutong_add'));
		}

	}


	public function qiutong_edit()
	{
		if(intval(get("qiutong_id"))>0)
		{
			$data=M("qiutong")->where("qiutong_id=".intval(get("qiutong_id")))->find();
			
			
			$category_list = $this->get_category_list();
		
			$this->assign('category_list',$category_list);
			
			$this->assign("data",$data);
			$this->assign("page_title","修改球童");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function qiutong_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["qiutong_id"]=post("qiutong_id");
			$data["qiutong_number"]=post("qiutong_number");
			$data["qiutong_name"]=post("qiutong_name");
			$data["qiutong_name_en"]=post("qiutong_name_en");
			$data["uid"]=post("uid");
			if($_FILES["qiutong_photo"]["error"]==0)
			{
				$uploadinfo=$this->upd_qiutong_photo();//upload_file("upload/qiutong/");
				$data["qiutong_photo"]=$uploadinfo;
			}
			$data["field_uid"]=$_SESSION["field_uid"];//post("field_uid");
			$data["category_id"]=post("category_id");
			$data["qiutong_content"]=post("qiutong_content");
			
			$list=M("qiutong")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('field/qiutong/qiutong'));
			}
			else
			{				
				$this->error("修改失败",U('field/qiutong/qiutong'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('field/qiutong/qiutong'));
		}

	}

	public function qiutong_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("qiutong")->where("qiutong_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function qiutong_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_qiutong set qiutong_state=1 where qiutong_id=".$ids_arr[$i]." ");
			}
			if($res)
			{
				echo "succeed^审核成功";
			}
			else
			{
				echo "error^审核成功";
			}			
			
		}
	}

	public function qiutong_detail()
	{
		if(intval(get("qiutong_id"))>0)
		{
			$data=M("qiutong")->where("qiutong_id=".intval(get("qiutong_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["qiutong_name"]."球童");
				$this->display();
			}
			else
			{
				$this->error("您该问的信息不存在");	
			}
			
		}
		else
		{
			$this->error("您该问的信息不存在");
		}

	}
	//注册和激活
	public function reg_act($bianhao='')
	{
	    $url = 'http://192.168.1.151:802/api/field.php?mod=reg_activate&no_token=11';
	    //$url = 'http://a.bwvip.com/api/field.php?mod=reg_activate&ac=reg_activate&field_uid=1186&no_token=11';
	    //$data['username'] = 'minilong20';
	    //$data['password'] = 'minilong20';
	    $data['mobile'] ='minilong2111';//$bianhao;
	    //$data['field_uid'] = 1186;
	    $data['email'] = 'minilong2111'.'@bw.com';
	    $data['realname'] = 'minilong2111';;
	    //echo 5555;
	    
	    $res = self::requestByPost($url,$data);//
	    $res = explode('thisisasplit',$res);
	    //返回值-3为mobile已存在
	    //返回值-6为email已存在
	    $res = stripslashes($res[1]);
	    if($res == NULL) {
	        return false;
	    }
	    $res = json_decode($res);
	    $str_arr = self::objectToArray($res);
	    $uid = $str_arr['uid'];
	    
	    if($str_arr['error'] == 0) {
	        return $uid;
	    }
	    
	    return false;
	}
	//jsonToArray
   public static function objectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__METHOD__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}
    /**
     * curl的post请求
     *
     * @param string $url url网址
     * @param array $data post数组
     * @param int $timeout 超时时间
     * @return false 或 执行返回结果
     */
    public static function requestByPost($url, $data, $timeout=3) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $result = curl_exec($ch);
        if ($result !== false) {
            curl_close($ch);
            return $result;//$result;
        } else {
            $curl_error = curl_error($ch);
            //$_data = is_array($data) ? implode('&', $data) : $data;
            curl_close($ch);
            //error_log(__METHOD__ . ':' . $url . '?' . $_data . ':' . $curl_error);
            return false;
        }
    }
    public function upd_qiutong_photo()
	{
    	if($_FILES["qiutong_photo"]["error"]<=0 && $_FILES["qiutong_photo"]["name"])
    	{
    		$file_path="/upload/qiutong/";
    		$time_name = time();
			if(!file_exists(WEB_ROOT_PATH.$file_path))
			{
				mkdir(WEB_ROOT_PATH.$file_path);
			}
			$file_path .=date("Ymd",$time_name)."/";
			if(!file_exists(WEB_ROOT_PATH.$file_path))
			{
				mkdir(WEB_ROOT_PATH.$file_path);
			}
			$extname=end(explode(".",$_FILES["qiutong_photo"]["name"]));
			//$file_name = iconv('utf-8','gb2312',$_FILES["qiutong_photo"]["name"]);
			$file_path .= $time_name.'.'.$extname;
			move_uploaded_file($_FILES["qiutong_photo"]["tmp_name"], WEB_ROOT_PATH.$file_path);//将上传的文件存储到服务器
			
			$extname=end(explode(".",$file_path));
    		if($extname=="jpg" || $extname=="JPG")
    		{
    			$pic_source=imagecreatefromjpeg(WEB_ROOT_PATH.$file_path);
    		}
    
    		$file_path2=WEB_ROOT_PATH.$file_path."_small";
    		
    		if(file_exists(WEB_ROOT_PATH.$file_path))
    		{
    			$this->resizeImage($pic_source,110,110,$file_path2,".".$extname);
    			return $file_path;
    		}
    		else
    		{
    			return false;
    		}
    
    	}
    	else
    	{
    		return false;
    	}
	}
    function resizeImage($im,$maxwidth,$maxheight,$name,$filetype)
    {
        $pic_width = imagesx($im);
        $pic_height = imagesy($im);
    
        if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight))
        {
            if($maxwidth && $pic_width>$maxwidth)
            {
                $widthratio = $maxwidth/$pic_width;
                $resizewidth_tag = true;
            }
    
            if($maxheight && $pic_height>$maxheight)
            {
                $heightratio = $maxheight/$pic_height;
                $resizeheight_tag = true;
            }
    
            if($resizewidth_tag && $resizeheight_tag)
            {
                if($widthratio<$heightratio)
                    $ratio = $widthratio;
                else
                    $ratio = $heightratio;
            }
    
            if($resizewidth_tag && !$resizeheight_tag)
                $ratio = $widthratio;
            if($resizeheight_tag && !$resizewidth_tag)
                $ratio = $heightratio;
    
            $newwidth = $pic_width * $ratio;
            $newheight = $pic_height * $ratio;
    
            if(function_exists("imagecopyresampled"))
            {
                $newim = imagecreatetruecolor($newwidth,$newheight);
               imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
            }
            else
            {
                $newim = imagecreate($newwidth,$newheight);
               imagecopyresized($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
            }
    
            $name = $name.$filetype;
            imagejpeg($newim,$name);
            imagedestroy($newim);
        }
        else
        {
            $name = $name.$filetype;
            imagejpeg($im,$name);
        }           
    }
	
    //检查球童编号是否唯一
    public function check_qiutong_number_unique($qiutong_number) {
        if(empty($qiutong_number)) {
            return false;
        }
        
        $info = M("qiutong")->where("qiutong_number='$qiutong_number' and field_uid='{$_SESSION["field_uid"]}'")->find();
        if(empty($info)) {
            return true;
        }
        
        return false;
    }
	//球童所属分类
	public function get_category_list()
	{
		$field_uid = $_SESSION["field_uid"];
		$category_type = 'qiutong';
		$category_list = M('category')->where("field_uid='{$field_uid}' and category_type='{$category_type}'")->select();
		
		if(empty($category_list))
		{
			return false;
		}
		$data_list = array();
		foreach($category_list as $key=>$val)
		{
			$data_list[$val['category_id']] = $val;
		}
		unset($category_list);
		return $data_list;
	}

}
?>