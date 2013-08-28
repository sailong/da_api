<?php
/**
 *    #Case		mlh
 *    #Page		field_menuAction.class.php(菜单操作)
 *
 *    @author		changsailong
 *    @E-mail		653690921@qq.com
 */
class field_menuAction extends field_publicAction
{

	public function _basic()	
	{
		parent::_basic();
	}
	public function first_menu_list($first_menu=false)
	{
//	    $sql = "select * from tbl_field_1stmenu where field_uid='1186' and field_1stmenu_type='1' order by field_1stmenu_id asc";
//	    $aaa = M()->query($sql);
//	    foreach($aaa as $key=>$val)
//        {
//            $field_1stmenu_ids[$val['field_1stmenu_id']] = $val['field_1stmenu_id'];
//        }
//        $field_1stmenu_id_str = implode("','",$field_1stmenu_ids);
//	    $sql2 = "select * from tbl_field_2ndmenu where field_uid='1186' and field_1stmenu_id in('{$field_1stmenu_id_str}') order by field_2ndmenu_id desc";
//	    
//	    
//	    $bbb = M()->query($sql2);
//	    foreach($bbb as $key2=>$val2) {
//	        $ccc[$val2['field_1stmenu_id']][] = $val2;
//	    }
//	     dump($aaa);
//    	foreach($aaa as $key=>&$val) {
//            if(!empty($ccc[$val['field_1stmenu_id']])) {
//                $val['menu_list'] = $ccc[$val['field_1stmenu_id']];
//            }else{
//                $val['menu_list'] = null;
//            }
//        }
//	    foreach($aaa as $key1=>&$val1) {
//	        foreach($bbb as $key2=>$val2) {
//	            if($val1['field_1stmenu_id'] == $val2['field_1stmenu_id']){
//	                $val1['menu_list'][] = $val2;
//	            }
//	        }
//	    }
//	    dump($aaa);
	    
	    
	    
	    
	    
	    $page = get('page');
	    $page = max(1,$page);
        $language = get('language');
        if(empty($language)) {
            $language = 'cn';
        }
	    $field_uid = $_SESSION["field_uid"];
	    $field_menu_model = new field_menuModel();
	    $list = $field_menu_model->get_1stmenu_list($field_uid,$page,20);
	    foreach($list['item'] as $key=>&$val) {
			
			if(!empty($val['category_id'])){
				$category_ids[$val['category_id']] = $val['category_id'];
			}
			
			
	        if($language == 'en') {
	            $val['field_1stmenu_name'] = $val['field_1stmenu_name_en'];
	        }
	        unset($val['field_1stmenu_name_en']);
	        if($val['field_1stmenu_type'] == 1){
	            $val['type_name'] = '餐厅用餐';
	        }elseif($val['field_1stmenu_type'] == 2){
	            $val['type_name'] = '场下送餐';
	        }
	    }
		
		
		$category_data = M('category')->where("category_id in('".implode("','",$category_ids)."')")->select();
		
		foreach($category_data as $key=>$val){
			$category_list[$val['category_id']] =  $val;
		}
		unset($category_ids,$category_data);
		
	    if($first_menu) {
	        return $list['item'];exit;
	    }
		
		$this->assign("category_list",$category_list);
	    $this->assign('list',$list['item']);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
        $this->assign('language',$language);
		$this->assign("page_title","文章");
	    
	    $this->display('first_menu_list');
	}
	public function add_1st_menu()
	{
	    $language = get('language');
	    if(empty($language)) {
	        $language = 'cn';
	    }
		$category_list = $this->get_category_list();
		
		$this->assign('category_list',$category_list);
	    $this->assign('language',$language);
	    $this->display('add_1st_menu');
	}
    //添加菜单分类
	public function add_1st_menu_action() 
	{
	    if(M()->autoCheckToken($_POST))
		{
		    $language = post('language');
    		if(empty($language)) {
    	        $language = 'cn';
    	    }
		    $data['uid'] = 'minilong';//post('uid');
		    $data['field_uid'] = $_SESSION["field_uid"];//post('field_uid');
		    $data['field_1stmenu_type'] = post('field_1stmenu_type');
		    if(!empty($language) && $language=='en')
		    {
		        $data['field_1stmenu_name_en '] = post('field_1stmenu_name');
		    }
		    else
		    {
		        $data['field_1stmenu_name'] = post('field_1stmenu_name');
		    }
			$data["category_id"]=post("category_id");
		    $data['field_1stmenu_addtime'] = time();
		    $list=M("field_1stmenu")->add($data);
		    if($list!=false)
			{
				$this->success("添加成功",U('field/field_menu/first_menu_list'));
			}
			else
			{				
				$this->error("添加失败",U('field/field_menu/first_menu_list'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('field/field_menu/first_menu_list'));
		}
	        
	}
	//修改一级菜单页面
	public function upd_1st_menu() 
	{
	    $language = get('language');
	    $field_1stmenu_id = get('field_1stmenu_id');
	    if(empty($language)) {
	        $language = 'cn';
	    }
	    if(empty($field_1stmenu_id)) 
	    {
	        $this->error('缺少参数',U('field/field_menu/first_menu_list'));
	    }
	    $field_menu_model = new field_menuModel();
	    $info  = $field_menu_model->get_1stmenu_info($field_1stmenu_id);
	    
	    if(empty($info))
	    {
	        $this->error('暂无信息',U('field/field_menu/first_menu_list'));
	    }
	    if($language == 'en') {
	        $info['field_1stmenu_name'] = $info['field_1stmenu_name_en'];
	    }
	    unset($info['field_1stmenu_name_en']);
		$category_list = $this->get_category_list();
		
		$this->assign('category_list',$category_list);
	    $this->assign('language',$language);
	    $this->assign('data',$info);
	    
	    $this->display('upd_1st_menu');
	}
	//修改菜单分类
	public function upd_1st_menu_action()
	{
	    if(M()->autoCheckToken($_POST))
		{
		    $language = post('language');
		    $data['field_1stmenu_type'] = post('field_1stmenu_type');
		    if($language=='en')
		    {
		        $data['field_1stmenu_name_en'] = post('field_1stmenu_name');
		    }
		    else
		    {
		        $data['field_1stmenu_name'] = post('field_1stmenu_name');
		    }
		    $data["category_id"]=post("category_id");
		    $data['field_1stmenu_addtime'] = time();
		    $list=M("field_1stmenu")->where('field_1stmenu_id='.post('field_1stmenu_id'))->save($data);
		    if($list!=false)
			{
				$this->success("修改成功",U('field/field_menu/first_menu_list'));
			}
			else
			{				
				$this->error("修改失败",U('field/field_menu/upd_1st_menu',array('field_1stmenu_id'=>$data['field_1stmenu_id'])));
			}
		}
		else
		{
			$this->error("不能重复提交",U('field/field_menu/upd_1st_menu',array('field_1stmenu_id'=>$data['field_1stmenu_id'])));
		}
	    
	}
	//删除菜单分类
	public function del_1st_menu()
	{
	    $field_1stmenu_id = intval(post('ids'));
	    if($field_1stmenu_id>0)
		{
		    //验证以及下有没有菜单，有不可删，没有则删
		    $list=M("field_2ndmenu")->where("field_1stmenu_id=".$field_1stmenu_id)->find();
		    if($list!=false) {
		        echo "error^此分类下有菜单，不可删除";exit;//echo "succeed^fffff";
		        //$this->error("此分类下有菜单，不可删除",U('field/field_menu/field_1stmenu_list'));
		    }
		    $list=M("field_1stmenu")->where("field_1stmenu_id=".$field_1stmenu_id)->delete();
		    if($list!=false)
			{
				echo "succeed^删除成功";exit;//echo "succeed^fffff";
			    //$this->success("删除成功",U('field/field_menu/field_1stmenu_list'));
			}
			else
			{				
				echo "error^删除失败";exit;//echo "succeed^fffff";
			    //$this->error("删除失败",U('field/field_menu/field_1stmenu_list'));
			}
		}
		else
		{
		    echo "error^不能重复提交";exit;//echo "succeed^fffff";
			//$this->error("不能重复提交",U('field/field_menu/field_1stmenu_list'));
		}
	}
	
	//二级菜单列表
	public function field_2ndmenu_list(){
	    $page = get('page');
	    $page = max(1,$page);
	    $field_1stmenu_id = get('field_1stmenu_id');
	    $language = get('language');
        if(empty($language)) {
            $language = 'cn';
        }
	    $first_list = $this->first_menu_list($language,true);
	    if(empty($field_1stmenu_id)) {
	        $field_1stmenu_id = $first_list[0]['field_1stmenu_id'];
	    }
	    
	    $field_menu_model = new field_menuModel();
	    $list = $field_menu_model->get_2ndmenu_list($field_1stmenu_id,$page,20);
	    
	    foreach($list['item'] as $key=>&$val) {
	        if($language == 'en') {
	            $val['field_2ndmenu_name'] = $val['field_2ndmenu_name_en'];
	        }
	        unset($val['field_2ndmenu_name_en']);
	        if($val['field_1stmenu_type'] == 1){
	            $val['type_name'] = '餐厅用餐';
	        }elseif($val['field_1stmenu_type'] == 2){
	            $val['type_name'] = '场下送餐';
	        }
	    }
	    $this->assign('field_1stmenu_id',$field_1stmenu_id);
	    $this->assign('list',$list['item']);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
        $this->assign('language',$language);
		$this->assign("first_list",$first_list);
		
		$this->display('second_menu_list');
	}
	//添加菜单页面
	public function add_2nd_menu()
	{
	    $field_1stmenu_id = get('field_1stmenu_id');
	    $list = M('field_1stmenu')->where("field_1stmenu_id='{$field_1stmenu_id}'")->find();
	    
	    $this->assign('data',$list);
	    $this->display('add_2nd_menu');
	}
    //添加菜单名称
	public function add_2nd_menu_action() 
	{
	    if(M()->autoCheckToken($_POST))
		{
		    $language = post('language');
		    $data['field_1stmenu_id'] = post('field_1stmenu_id');
		    $data['uid'] = $_SESSION["uid"];//post('uid');
		    $data['field_uid'] = $_SESSION["field_uid"];//post('field_uid');
		    if($language == 'en') {
		        $data['field_2ndmenu_name_en'] = post('field_2ndmenu_name');
		    }else{
		        $data['field_2ndmenu_name'] = post('field_2ndmenu_name');
		    }
		    $data['field_1stmenu_type'] = post('field_1stmenu_type');
		    $menu_pic_url = $this->upd_menu_pic();
			if(!empty($menu_pic_url)) {
				$data['field_2ndmenu_pic'] = $menu_pic_url;
			}
		    
		    $data['field_2ndmenu_price'] = post('field_2ndmenu_price');
		    $data['field_2ndmenu_addtime'] = time();
		    $list=M("field_2ndmenu")->add($data);
		    if($list!=false)
			{
				$this->success("添加成功",U('field/field_menu/field_2ndmenu_list',array('language'=>$language,'field_1stmenu_id'=>$data['field_1stmenu_id'])));
			}
			else
			{				
				$this->error("添加失败",U('field/field_menu/field_2ndmenu_list',array('language'=>$language,'field_1stmenu_id'=>$data['field_1stmenu_id'])));
			}
		}
		else
		{
			$this->error("不能重复提交",U('field/field_menu/field_2ndmenu_list',array('language'=>$language,'field_1stmenu_id'=>$data['field_1stmenu_id'])));
		}
	        
	}
	//修改二级菜单
	public function upd_2nd_menu()
	{
	    $language = get('language');
	    $field_2ndmenu_id = get('field_2ndmenu_id');
	    $field_1stmenu_id = get('field_1stmenu_id');
	    if(empty($language)) {
	        $language = 'cn';
	    }
	    if(empty($field_2ndmenu_id)) 
	    {
	        $this->error('缺少参数',U('field/field_menu/field_2ndmenu_list',array('language'=>$language,'field_1stmenu_id'=>$field_1stmenu_id)));
	    }
	    $field_menu_model = new field_menuModel();
	    $info  = $field_menu_model->get_2ndmenu_info($field_2ndmenu_id);
	    
	    if(empty($info))
	    {
	        $this->error('暂无信息',U('field/field_menu/field_2ndmenu_list',array('language'=>$language,'field_1stmenu_id'=>$field_1stmenu_id)));
	    }
	    if($language == 'en') {
	        $info['field_2ndmenu_name'] = $info['field_2ndmenu_name_en'];
	    }
	    unset($info['field_2ndmenu_name_en']);
	    $info['field_2ndmenu_pic']=$this->http_url.$info['field_2ndmenu_pic'];
	    $this->assign('language',$language);
	    $this->assign('data',$info);
	    
	    $this->display('upd_2nd_menu');
	}
	//修改菜单名称
	public function upd_2nd_menu_action()
	{
	    if(M()->autoCheckToken($_POST))
		{
		    $language = post('language');
		    $field_1stmen_id = post('field_1stmenu_id');
		    $data['field_2ndmenu_id'] = post('field_2ndmenu_id');
		    if($language=='en')
		    {
		        $data['field_2ndmenu_name_en'] = post('field_2ndmenu_name');
		    }
		    else
		    {
		        $data['field_2ndmenu_name'] = post('field_2ndmenu_name');
		    }
		    $menu_pic_url = $this->upd_menu_pic();
			
			if(!empty($menu_pic_url)) {
			
				$data['field_2ndmenu_pic'] = $menu_pic_url;
			}
		   
		    $data['field_2ndmenu_price'] = post('field_2ndmenu_price');
		    $data['field_2ndmenu_addtime'] = time();
		    $list=M("field_2ndmenu")->save($data);
		    
		    if($list!=false)
			{
				$this->success("修改成功",U('field/field_menu/field_2ndmenu_list',array('field_1stmenu_id'=>$field_1stmen_id,'language'=>$language)));
			}
			else
			{				
				$this->error("修改失败",U('field/field_menu/field_2ndmenu_list',array('field_1stmenu_id'=>$field_1stmen_id,'language'=>$language)));
			}
		}
		else
		{
			$this->error("不能重复提交",U('field/field_menu/field_2ndmenu_list',array('field_1stmenu_id'=>$field_1stmen_id,'language'=>$language)));
		}
	    
	}
	//删除菜单名称
	public function del_2nd_menu()
	{
	    $field_2ndmenu_id = intval(post('ids'));
	    if($field_2ndmenu_id>0)
		{
		    $list=M("field_2ndmenu")->where("field_2ndmenu_id='{$field_2ndmenu_id}'")->delete();
		    if($list!=false)
			{
				echo "succeed^删除成功";exit;//echo "succeed^fffff";
			    //$this->success("删除成功",U('field/field_menu/field_1stmenu_list'));
			}
			else
			{				
				echo "error^删除失败";exit;//echo "succeed^fffff";
			    //$this->error("删除失败",U('field/field_menu/field_1stmenu_list'));
			}
		}
		else
		{
			echo "error^不能重复提交";
		}
	}
	//查看二级菜单的详情
	public function field_2ndmenu_detail()
	{
	    $field_2ndmenu_id = get('field_2ndmenu_id');
	    if(empty($field_2ndmenu_id)) {
	        $this->assign('info',true);
	    }
	    $info = M('field_2ndmenu')->where("field_2ndmenu_id='{$field_2ndmenu_id}'")->find();
	    if(empty($info['field_2ndmenu_pic'])) {
	        $this->assign('info',true);
	    }
	    $this->assign('pic',$this->http_url.$info['field_2ndmenu_pic']);
	    $this->display('field_2ndmenu_detail');
	}
	
	
	public function up_pic()
	{
    	if($_FILES["menu_pic"]["error"]<=0 && $_FILES["menu_pic"]["name"])
    	{
    	    
	        $pic_path = "/upload/menu_pic/";
	        $time_name=time();
			if(!file_exists(WEB_ROOT_PATH.$pic_path))
			{
				mkdir(WEB_ROOT_PATH.$pic_path);
			}
			$pic_path .= date("Ymd",$time_name)."/";
	        if(!file_exists(WEB_ROOT_PATH.$pic_path))
			{
				mkdir(WEB_ROOT_PATH.$pic_path);
			}
			$pic_path .= $time_name.$_FILES["menu_pic"]["name"];
			move_uploaded_file($_FILES["menu_pic"]["tmp_name"], WEB_ROOT_PATH.$pic_path);//将上传的文件存储到服务器
			$extname=end(explode(".",$pic_path));
    		if(file_exists(WEB_ROOT_PATH.$pic_path))
    		{
    		    import('resizeimage',WEB_ROOT_PATH.'/dazheng/Common/','.class.php');
    			$aa=new resizeimage(WEB_ROOT_PATH.$pic_path,100,100,1);
    		}
    		else
    		{
    		    return false;
    		}
    	}
	}
	public function upd_menu_pic()
	{
    	if($_FILES["menu_pic"]["error"]<=0 && $_FILES["menu_pic"]["name"])
    	{
    		$file_path="/upload/menu_pic/";
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
			$extname=end(explode(".",$_FILES["menu_pic"]["name"]));
			//$file_name = iconv('gb2312','utf-8',$_FILES["menu_pic"]["name"]);
			$file_path .= $time_name.'.'.$extname;
			move_uploaded_file($_FILES["menu_pic"]["tmp_name"], WEB_ROOT_PATH.$file_path);//将上传的文件存储到服务器
			
			$extname=end(explode(".",$file_path));
    		if($extname=="jpg" || $extname == 'JPG')
    		{
    			$pic_source=imagecreatefromjpeg(WEB_ROOT_PATH.$file_path);
    		}
    
    		$file_path2=WEB_ROOT_PATH.$file_path."_small";
    		//echo $file_path2;
    		if(file_exists(WEB_ROOT_PATH.$file_path))
    		{
    			self::resizeImage($pic_source,120,90,$file_path2,".".$extname);
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
    private function resizeImage($im,$maxwidth,$maxheight,$name,$filetype)
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
	//菜单所属分类
	public function get_category_list()
	{
		$field_uid = $_SESSION["field_uid"];
		$category_type = 'canyin';
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