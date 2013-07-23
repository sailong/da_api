<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_profile.php 24010 2011-08-19 07:35:13Z zhengqingpeng $
 */

if (! defined ( 'IN_DISCUZ' )) {
	exit ( 'Access Denied' );
}

$ac = trim ( $_GET ['ac'] );
$url="/home.php?mod=spacecp&ac=shopping";
$operation = in_array ( $_GET ['op'], array ('add','ajax','del_image','huopin','huopinadd','remove','edit') ) ? trim ( $_GET ['op'] ) : 'add';
$gid=intval($_GET["gid"]);    //商品id
$albums = getalbums($_G['uid']);
$picpath="/data/attachment/shop/";
$datab="daz_shop.dz_";     //ecshop数据库和对应的前缀
define("ROOT_PATH",$_SERVER["DOCUMENT_ROOT"]."/shop/images");  //图片的物理路径   /home/www/dzbwvip/
//echo ROOT_PATH;
$url="home.php?mod=spacecp&ac=shopping&op=add";
$imgpath="images";     //入库时带的前面的路径
$delpath="shop/";
$url="/home.php?mod=spacecp&ac=shopping";
//查看商品id
if($gid){
	$shopsql="SELECT * FROM ".$datab."goods WHERE goods_id=".$gid;
	$shop=DB::fetch(DB::query($shopsql));
	//var_dump($shop);
	if($shop["uid"] && $shop["uid"]!=$_G["uid"]){
		showmessage("抱歉，您没有权限操作该商品",$url);
	}
	$shop["goods_thumb2"]=$shop["goods_thumb"];
	if(!empty($shop["goods_thumb"])){
		$shop["goods_thumb"]="/shop/".$shop["goods_thumb"];     //为了在ecshop下面看到的缩略图是正常的
	}
	//判断上架下架
	
}
if(empty($shop["is_on_sale"]) || $shop["is_on_sale"]=='0'){
	$shop["shenhe"]='0';
}
//读取缩略图的尺寸
$picsql="SELECT `code`,`value` FROM ".$datab."shop_config WHERE CODE='thumb_width' OR CODE='thumb_height' OR CODE='image_width' OR CODE='image_height'";
$repic=DB::query($picsql);
while($rowpic=DB::fetch($repic)){
	$picarr[$rowpic["code"]]=$rowpic["value"];
}
//var_dump($picarr);
if(empty($picarr["image_height"])){
	$picarr["image_height"]="230";
}
if(empty($picarr["image_width"])){
	$picarr["image_width"]="230";
}
if(empty($picarr["thumb_height"])){
	$picarr["thumb_height"]="100";
}
if(empty($picarr["thumb_width"])){
	$picarr["thumb_width"]="100";
}
//是否有相册
if($shop["goods_id"]){
	$sqlal="SELECT img_desc,img_id,goods_id,thumb_url  FROM ".$datab."goods_gallery WHERE goods_id=".$shop["goods_id"];
	$alarr=DB::query($sqlal);
	while($rowdb=DB::fetch($alarr)){
		$rowdb["thumb_url"]="/shop/".$rowdb["thumb_url"];
		$albumarr[$rowdb["img_id"]]=$rowdb;
	}
	//var_dump($albumarr);
}

//获取品牌信息
 $sqlbrand = "SELECT brand_id, brand_name FROM ".$datab."brand ORDER BY sort_order";
    $res = DB::query($sqlbrand);
	$brand_list = array();
    while($row=DB::fetch($res)){
        $brand_list[$row['brand_id']] = addslashes($row['brand_name']);
    }
	//var_dump($brand_list);

require_once libfile ( 'function/ecshop' );

//包含上传函数
require_once libfile ( 'function/ecupload' );
//获取分类
$class=cat_list(0,$shop["cat_id"]);
//var_dump($class);

if(empty($shop["goods_type"])){
	$shop["goods_type"]=0;
}


//属性分类表
$goods_type=goods_type_list($shop["goods_type"]);
$goods_attr_html=build_attr_html($shop["goods_type"],$shop["goods_id"]);

//包含上传类
require_once libfile ( 'class/ecimage' );
$image = new cls_image();

if ($operation=='add'){
	//添加
	if (submitcheck ( 'blogsubmit', 0, $seccodecheck, $secqaacheck )) {
		require_once libfile ( 'function/blog' );
		$POST = $_POST;
		//var_dump($_FILES["file"]);
		//exit;
		//var_dump($shop["goods_id"]);
		if(empty($shop["goods_id"]) && $_FILES["file"]["error"]=='4'){
			showmessage("请上传商品图片");
		}
		
		//exit;
		$POST ['message'] = checkhtml ( $POST ['message'] );
		//var_dump($POST['message']);
		$POST ['message'] = getstr ( $POST ['message'], 0, 1, 0, 0, 1 );
		$POST ['message'] = censor ( $POST ['message'] );
		$POST ['message'] = preg_replace ( array ("/\<div\>\<\/div\>/i", "/\<a\s+href\=\"([^\>]+?)\"\>/i" ), array ('', '<a href="\\1" target="_blank">' ), $POST ['message'] );
		$message = $POST ['message'];
		
		//判断内容中是否有图片
		$uploads = array ();
		if (! empty ( $POST ['picids'] )) {
			$picids = array_keys ( $POST ['picids'] );
			$query = DB::query ( "SELECT * FROM " . DB::table ( 'home_pic' ) . " WHERE picid IN (" . dimplode ( $picids ) . ") AND uid='$_G[uid]'" );
			while ( $value = DB::fetch ( $query ) ) {
				if (empty ( $titlepic ) && $value ['thumb']) {
					//$titlepic = getimgthumbname($value['filepath']);
					$blogarr ['picflag'] = $value ['remote'] ? 2 : 1;
				}
				$uploads [$POST ['picids'] [$value ['picid']]] = $value;
			}
			if(empty($titlepic) && $value) {
				 $titlepic = $value['filepath'];
				 $blogarr['picflag'] = $value['remote']?2:1;
				 }
		}
		
		if ($uploads) {
			preg_match_all ( "/\[imgid\=(\d+)\]/i", $message, $mathes );
			if (! empty ( $mathes [1] )) {
				$searchs = $replaces = array ();
				foreach ( $mathes [1] as $key => $value ) {
					if (! empty ( $uploads [$value] )) {
						$picurl ="/". pic_get ( $uploads [$value] ['filepath'], 'album', $uploads [$value] ['thumb'], $uploads [$value] ['remote'], 0 );
						$searchs [] = "[imgid=$value]";
						$replaces [] = "<img src=\"$picurl\">";
						unset ( $uploads [$value] );
					}
				}
				if ($searchs) {
					$message = str_replace ( $searchs, $replaces, $message );
				}
			}
			foreach ( $uploads as $value ) {
				$picurl = "/".pic_get ( $value ['filepath'], 'album', $value ['thumb'], $value ['remote'], 0 );
				$message .= "<div class=\"uchome-message-pic\"><img src=\"$picurl\"><p>$value[title]</p></div>";
			}
		}
		//var_dump($message);
		$ckmessage = preg_replace ( "/(\<div\>|\<\/div\>|\s|\&nbsp\;|\<br\>|\<p\>|\<\/p\>)+/is", '', $message );
			//exit;
		//echo "**";
		///var_dump($_FILES);
		///exit;
		/*上传商品的图片处理*/
		
		
		$goods_desc = addslashes ( $message ); //转义，入库  描述
		//var_dump($goods_desc);
		//exit;
		$goods_name= addslashes($POST["subject"]);   //商品名称
		$goods_sn=addslashes($POST["goods_sn"]);    //商品编号
		$market_price=intval($POST["market_price"]);   //市场价
		$our_price=intval($POST["our_price"]);      //本店售价
		$brand_id=empty($_POST["brand_id"])?0:$_POST["brand_id"];         //品牌
		$goods_number=empty($_POST["goods_number"])?1:$_POST["goods_number"];       //库存数量
		$warn_number=empty($_POST["warn_number"])?1:$_POST["warn_number"];         //库存警告数量
		$goods_brief=empty($_POST["goods_brief"])?'':$_POST["goods_brief"];      //商品简单描述
		$seller_note=empty($_POST["seller_note"])?'':$_POST["seller_note"];      //商家备注
		$keywords=$_POST["keywords"];       //商品关键词
		$cat_id=empty($_POST["cat_id"])?0:$_POST["cat_id"];         //分类
		$is_best=empty($_POST["is_best"])?0:$_POST["is_best"];      //精品
		$is_new=empty($_POST["is_new"])?0:$_POST["is_new"];    //新品
		$is_hot=empty($_POST["is_hot"])?0:$_POST["is_hot"];       //热销
		$goods_type=$_POST["goods_type"];      //属性的分类
		$attr_value_list=$_POST["attr_value_list"];    //属性值
		$attr_id_list=$_POST["attr_id_list"];    //属性id
		$attr_price_list=$_POST["attr_price_list"];    //价格
		$img_desc=$_POST["img_desc"];       //商品相册的描述
		$img_url=$_FILES["img_url"];      //商品相册
		
		$is_on_sale=intval($POST["is_on_sale"]);     //是否下架
		//var_dump($POST);
		//echo $message;
		$nowtime=time();

		//exit;
		//var_dump($gid);
		//这个gid是form表单提交过来的，如果输入了一个不存在的id，form提交过来的就是空
		
		
		//查看商品的编号是否已存在
		$isgoods_id=DB::fetch(DB::query("SELECT goods_id FROM ".$datab."goods WHERE goods_sn='".$goods_sn."'"));
		if(!empty($isgoods_id["goods_id"]) && $isgoods_id["goods_id"]!=$gid){
			showmessage("商品编号已存在",$url);
		}
		
		
		
		if($shop["goods_id"]){
		//如果存在就更新
			$upsql="update ".$datab."goods set goods_name='".$goods_name."',is_on_sale='".$is_on_sale."',goods_desc='".$goods_desc."',goods_sn='".$goods_sn."',brand_id=$brand_id,market_price=$market_price,shop_price=$our_price,goods_number=$goods_number,warn_number=$warn_number,goods_brief='".$goods_brief."',seller_note='".$seller_note."',keywords='".$keywords."',cat_id=$cat_id,is_best=$is_best,is_new=$is_new,is_hot=$is_hot,goods_type=$goods_type where uid=".$_G["uid"]." AND goods_id=".$shop["goods_id"];
			//echo $upsql;
			$flag=DB::query($upsql);
			//echo $upsql;
			if($flag){
				$msg="更新成功";
			}else{
				$msg="更新失败";
			}
		}else{
		//不存在就添加
				$flag=DB::query("insert into ".$datab."goods (goods_name,uid,is_on_sale,goods_desc,goods_sn,brand_id,market_price,shop_price,goods_number,warn_number,goods_brief,seller_note,keywords,cat_id,is_best,is_new,is_hot,goods_type) values('".$goods_name."',".$_G["uid"].",'".$is_on_sale."','".$goods_desc."','".$goods_sn."',$brand_id,$market_price,$our_price,$goods_number,$warn_number,'".$goods_brief."','".$seller_note."','".$keywords."',$cat_id,$is_best,$is_new,$is_hot,$goods_type)");

				if($flag){
					$msg="添加成功";
				}else{
					$msg="添加失败";
				}
			//}
		}
		//echo $msg;
		//exit;
		if($shop["goods_id"]){
			$goods_id=$shop["goods_id"];       //获取商品的id     //如果有就是更新
		}else{
			$goods_id=DB::insert_id();       //获取商品的id      //没有就是添加
		}
		
		/*******************处理商品图片开始*********************/
		if($_FILES["file"]){
				$upload=$_FILES["file"];
				if($upload["error"]=='0'){
					
					//var_dump($upload);
					$original_img=$image->upload_image($upload);     //原始图片1
					//echo $original_img."<br />";
					$goods_thumb = $image->make_thumb(ROOT_PATH."/" . $original_img, $picarr["thumb_width"], $picarr["thumb_height"]);      //商品缩略图2
					//echo $goods_thumb."<br />";
					$goods_img = $image->make_thumb(ROOT_PATH."/" . $original_img, $picarr["image_width"],$picarr["image_height"]);      //商品中等缩略图3
					//echo $goods_img."<br />";
					
					//下面的三张是相册中的图片
					//复制一份图像
					$img        = $original_img;   // 相册图片
					$pos        = strpos(basename($img), '.');
					$newname    = dirname($img) . '/' . $image->random_filename() . substr(basename($img), $pos);
					if (!copy( ROOT_PATH. $img,  ROOT_PATH . $newname))
					{
						//echo "复制文件错误";
                        showmessage("复制文件错误");
					}
					$gallery_img = $newname;    //商品的原图4
					//echo $gallery_img."<br />";
					//复制第二份图像
					$img        = $original_img;   // 相册图片
					$pos        = strpos(basename($img), '.');
					$newname    = dirname($img) . '/' . $image->random_filename() . substr(basename($img), $pos);
					if (!copy( ROOT_PATH. $img,  ROOT_PATH . $newname))
					{
						//echo "复制文件错误";
                        showmessage("复制文件错误");
					}
					$img = $newname;    //商品的原图4
					//echo $img."<br />";
					//生成缩略图
					$gallery_thumb = $image->make_thumb(ROOT_PATH."/" . $original_img, '100',  '100');      //商品中等缩略图3
					//echo $gallery_thumb."<br />"; 
					
					//移动文件夹
					$original_img = reformat_image_name('goods', $goods_id , $original_img, 'source');      //原图
					//var_dump($original_img);
				   $goods_img = reformat_image_name('goods', $goods_id , $goods_img, 'goods');    //商品图片
					//var_dump($goods_img);
					$goods_thumb = reformat_image_name('goods_thumb',$goods_id, $goods_thumb, 'thumb');     //缩略图
					//var_dump($goods_thumb);
					
					$img = reformat_image_name('gallery',$goods_id, $img, 'source');
					$gallery_img = reformat_image_name('gallery',$goods_id, $gallery_img, 'goods');
					$gallery_thumb = reformat_image_name('gallery_thumb',$goods_id, $gallery_thumb, 'thumb');
				}
			//exit;
		}
      
		//处理商品图片开始
		if($img_desc){
			//var_dump($img_desc);
			//var_dump($img_url);
			foreach($img_desc as $key=>$v){
				//echo $img_url["error"][$key]."<br />";
				if($img_url["error"][$key]=='0'){
					//echo $img_url[$key]["tmp_name"];
					
					 $upload = array(
						'name' => $img_url['name'][$key],
						'type' => $img_url['type'][$key],
						'tmp_name' => $img_url['tmp_name'][$key],
						'size' => $img_url['size'][$key],
					);
					//var_dump($upload);
					$gallery_img2=$image->upload_image($upload);     //原始图片1
					//echo "****".$gallery_img2."<br />";
					
					//copy原图
					$img2        = $gallery_img2;   // 相册图片
					$pos        = strpos(basename($img2), '.');
					$newname    = dirname($img2) . '/' . $image->random_filename() . substr(basename($img2), $pos);
					if (!copy( ROOT_PATH. $img2,  ROOT_PATH . $newname))
					{
						//echo "复制文件错误";
                        showmessage("复制文件错误");
					}
					$img2 = $newname;    //商品的原图4
					//echo $img2."<br />";
					//生成缩略图
					$gallery_thumb2 = $image->make_thumb(ROOT_PATH."/" . $gallery_img2, '100',  '100');      //商品中等缩略图3
					//echo $gallery_thumb2."<br />";
					
					//移动图片位置
					$img2 = reformat_image_name('gallery',$goods_id, $img2, 'source');
					$gallery_img2 = reformat_image_name('gallery',$goods_id, $gallery_img2, 'goods');
					$gallery_thumb2 = reformat_image_name('gallery_thumb',$goods_id, $gallery_thumb2, 'thumb');
					
					//相册插入数据库
					$img2=$imgpath.$img2;
					$gallery_thumb2=$imgpath.$gallery_thumb2;
					$gallery_img2=$imgpath.$gallery_img2;
					//echo $img2."<br />";
					//echo $gallery_thumb2."<br />";
					//echo $gallery_img2."<br />";
					DB::query("insert into ".$datab."goods_gallery (goods_id,img_url,img_desc,thumb_url,img_original) values ($goods_id,'".$img2."','$v','".$gallery_thumb2."','".$gallery_img2."')");
										
				}
			}
			
		}

		/*******************处理商品图片结束*********************/
		
		/**********处理相册的描述开始*********/
		if ($shop["goods_id"] && isset($_POST['old_img_desc']))
		{
			foreach ($_POST['old_img_desc'] AS $img_id => $img_desc)
			{
				$sql = "UPDATE " . $datab ."goods_gallery SET img_desc = '$img_desc' WHERE img_id = '$img_id' LIMIT 1";
				DB::query($sql);
			}
		}
		/**********处理相册的描述结束*********/
		
		/*************把商品图片路径写入数据库开始***************/
		if(!empty($original_img) || !empty($goods_img) || !empty($goods_thumb)){
			$original_img=$imgpath.$original_img;
			$goods_img=$imgpath.$goods_img;
			$goods_thumb=$imgpath.$goods_thumb;
			if(!empty($goods_id)){
				DB::query("update ".$datab."goods set goods_thumb='".$goods_thumb."',goods_img='".$goods_img."',original_img='".$original_img."' where goods_id=".$goods_id);
			}else{
				//echo "出错了";
                showmessage("写数据出错了");
			}
			//另一张表
			$img=$imgpath.$img;
			$gallery_thumb=$imgpath.$gallery_thumb;
			$gallery_img=$imgpath.$gallery_img;
			//echo $img."<br />";
			//echo $gallery_thumb."<br />";
			//echo $gallery_img."<br />";
			$flag=DB::query("insert into ".$datab."goods_gallery (goods_id,img_url,img_desc,thumb_url,img_original) values ($goods_id,'".$img."','','".$gallery_thumb."','".$gallery_img."')");
			/*****************删除原来的商品图片开始*************************/
			
			if($flag){
				if ($shop['goods_thumb2'] != '' && is_file( $delpath.$shop['goods_thumb2']))
				{
					@unlink($delpath.$shop['goods_thumb2']);
					//echo $delpath.$shop['goods_thumb']."3333333333<br />";
				}
					if ($shop['goods_img'] != '' && is_file( $delpath.$shop['goods_img']))
				{
					@unlink($delpath.$shop['goods_img']);
					//echo $delpath.$shop['goods_img']."222222<br />";
				}
					if ($shop['original_img'] != '' && is_file( $delpath.$shop['original_img']))
				{
					@unlink($delpath.$shop['original_img']);
					//echo $delpath.$shop['original_img']."111111111111";
				}
			}
			//goods_thumb,goods_img,original_img
			/*****************删除原来的商品图片结束*************************/
		}
		
		/*************把图片路径写入数据库结束***************/
		//var_dump($goods_id);
		
		/* 处理属性 */
    if ((isset($_POST['attr_id_list']) && isset($_POST['attr_value_list'])) || (empty($_POST['attr_id_list']) && empty($_POST['attr_value_list'])))
    {
		
        // 取得原有的属性值
        $goods_attr_list = array();

        $keywords_arr = explode(" ", $_POST['keywords']);

        $keywords_arr = array_flip($keywords_arr);
        if (isset($keywords_arr['']))
        {
            unset($keywords_arr['']);
        }

        $sql = "SELECT attr_id, attr_index FROM " . $datab ."attribute WHERE cat_id = '$goods_type'";

        $attr_res = DB::query($sql);

        $attr_list = array();

        while ($row = DB::fetch($attr_res))
        {
            $attr_list[$row['attr_id']] = $row['attr_index'];
        }

        $sql = "SELECT g.*, a.attr_type FROM " . $datab."goods_attr AS g LEFT JOIN " . $datab."attribute AS a ON a.attr_id = g.attr_id WHERE g.goods_id = '$goods_id'";

        $res = DB::query($sql);

        while ($row = DB::fetch($res))
        {
            $goods_attr_list[$row['attr_id']][$row['attr_value']] = array('sign' => 'delete', 'goods_attr_id' => $row['goods_attr_id']);
        }
        // 循环现有的，根据原有的做相应处理
        if(isset($_POST['attr_id_list']))
        {
            foreach ($_POST['attr_id_list'] AS $key => $attr_id)
            {
                $attr_value = $_POST['attr_value_list'][$key];
                $attr_price = $_POST['attr_price_list'][$key];
                if (!empty($attr_value))
                {
                    if (isset($goods_attr_list[$attr_id][$attr_value]))
                    {
                        // 如果原来有，标记为更新
                        $goods_attr_list[$attr_id][$attr_value]['sign'] = 'update';
                        $goods_attr_list[$attr_id][$attr_value]['attr_price'] = $attr_price;
                    }
                    else
                    {
                        // 如果原来没有，标记为新增
                        $goods_attr_list[$attr_id][$attr_value]['sign'] = 'insert';
                        $goods_attr_list[$attr_id][$attr_value]['attr_price'] = $attr_price;
                    }
                    $val_arr = explode(' ', $attr_value);
                    foreach ($val_arr AS $k => $v)
                    {
                        if (!isset($keywords_arr[$v]) && $attr_list[$attr_id] == "1")
                        {
                            $keywords_arr[$v] = $v;
                        }
                    }
                }
            }
        }
        $keywords = join(' ', array_flip($keywords_arr));

        $sql = "UPDATE " .$datab."goods SET keywords = '$keywords' WHERE goods_id = '$goods_id' LIMIT 1";

        DB::query($sql);

        /* 插入、更新、删除数据 */
        foreach ($goods_attr_list as $attr_id => $attr_value_list)
        {
            foreach ($attr_value_list as $attr_value => $info)
            {
                if ($info['sign'] == 'insert')
                {
                    $sql = "INSERT INTO " .$datab."goods_attr (attr_id, goods_id, attr_value, attr_price)".
                            "VALUES ('$attr_id', '$goods_id', '$attr_value', '$info[attr_price]')";
                }
                elseif ($info['sign'] == 'update')
                {
                    $sql = "UPDATE " .$datab."goods_attr SET attr_price = '$info[attr_price]' WHERE goods_attr_id = '$info[goods_attr_id]' LIMIT 1";
                }
                else
                {
                    $sql = "DELETE FROM " .$datab."goods_attr WHERE goods_attr_id = '$info[goods_attr_id]' LIMIT 1";
                }
                DB::query($sql);
            }
        }
    }
		/**************************************************/
		
		clear_cache_files();    //清楚ecshop的缓存
		showmessage($msg,$url);
		
		//echo $message;
	}
	$templates = 'home/spacecp_shopping_add';
}elseif($operation=='ajax'){
//读取商品的属性
	//echo "dasfas";
	//exit;
	$goods_id   = empty($_GET['gid']) ? 0 : intval($_GET['gid']);
    $goods_type = empty($_GET['gtype']) ? 0 : intval($_GET['gtype']);
    $content    = build_attr_html($goods_type, $goods_id);
	//var_dump($content);
	echo $content;
	exit;
	/*$endarr=array("message"=>$content);
	$jsonarr=json_encode($endarr);
	print_r($jsonarr);
	exit;*/
}elseif($operation=='del_image'){
	$img_id = empty($_REQUEST['img_id']) ? 0 : intval($_REQUEST['img_id']);
	//echo $img_id;
	//exit;

    /* 删除图片文件 */
    $sql = "SELECT a.*,b.uid FROM " . $datab ."goods_gallery AS a LEFT JOIN " . $datab ."goods AS b ON a.goods_id=b.goods_id WHERE a.img_id=".$img_id;
    
	$ry = DB::query($sql);
	$row=DB::fetch($ry);
	//var_dump($row);
	//判断是不是这个用户的商品
	if($row["uid"]!=$_G["uid"]){
		return '';
	}
	//echo $delpath.$row['img_url'];
	$ty=is_file( $delpath.$row['img_url']);
	//var_dump($ty);
    if ($row['img_url'] != '' && is_file( $delpath.$row['img_url']))
    {
		//echo $delpath.$row['img_url'];
        @unlink($delpath.$row['img_url']);
    }
    if ($row['thumb_url'] != '' && is_file( $delpath.$row['thumb_url']))
    {
        @unlink($delpath.$row['thumb_url']);
    }
    if ($row['img_original'] != '' && is_file( $delpath.$row['img_original']))
    {
        @unlink($delpath.$row['img_original']);
    }

    /* 删除数据 */
    $sql = "DELETE FROM " .$datab."goods_gallery WHERE img_id = '$img_id' LIMIT 1";
	$flag=DB::query($sql);
	if($flag){
		echo $img_id;
	}
	clear_cache_files();    //清楚ecshop的缓存
	exit;
}elseif($operation=='huopin'){
	
	$goods_id=intval($_GET["gid"]);
	//如果没有商品的id
	if(empty($goods_id)){
		header("Location:".$url."&op=add");
		exit;
	}

	
	
	/* 取出商品信息 */
    $sql = "SELECT goods_id,goods_sn, goods_name, goods_type, shop_price FROM " . $datab."goods WHERE goods_id = '$goods_id'";
    $goods = DB::fetch(DB::query($sql));
	//var_dump($goods);
	
	
	//echo $goods_id;
	/* 获取商品规格列表 */
    $attribute = get_goods_specifications_list($goods_id);
	//var_dump($attribute);
	foreach ($attribute as $attribute_value)
    {
        //转换成数组
        $_attribute[$attribute_value['attr_id']]['attr_values'][] = $attribute_value['attr_value'];
        $_attribute[$attribute_value['attr_id']]['attr_id'] = $attribute_value['attr_id'];
        $_attribute[$attribute_value['attr_id']]['attr_name'] = $attribute_value['attr_name'];
    }
	//var_dump($_attribute);
	/* 取商品的货品 */
    $product = product_list($goods_id, '');
	//var_dump($product);
	$product_list=$product['product'];
	
	
	$navtitle="货品分配";
	$templates = 'home/spacecp_shopping_huopin';
	include_once (template ( $templates ));
	exit;
}elseif($operation=='huopinadd'){
	$product['goods_id']        = intval($_POST['goods_id']);
    $product['attr']            = $_POST['attr'];
    $product['product_sn']      = $_POST['product_sn'];
    $product['product_number']  = $_POST['product_number'];
	//var_dump($_POST);
	//var_dump($product['product_number']);
    /* 是否存在商品id */
    if (empty($product['goods_id']))
    {
        header("Location:/");
    }
   
    /* 取出商品信息 */
    $sql = "SELECT goods_id,goods_sn, goods_name, goods_type, shop_price FROM " . $datab."goods WHERE goods_id = '" . $product['goods_id'] . "'";
    $goods = DB::fetch(DB::query($sql));
	//var_dump($goods);
    if (empty($goods["goods_id"]))
    {
        showmessage("出错了，没有这个商品");
    }

    /*  */
	//var_dump($product['product_sn']);
    foreach($product['product_sn'] as $key => $value)
    {
        //过滤
        $product['product_number'][$key] = empty($product['product_number'][$key]) ? (empty($_CFG['use_storage']) ? 0 : $_CFG['default_storage']) : trim($product['product_number'][$key]); //库存

        //获取规格在商品属性表中的id
        foreach($product['attr'] as $attr_key => $attr_value)
        {

            /* 检测：如果当前所添加的货品规格存在空值或0 */
            if (empty($attr_value[$key]))
            {
                continue 2;
            }

            $is_spec_list[$attr_key] = 'true';

            $value_price_list[$attr_key] = $attr_value[$key] . chr(9) . ''; //$key，当前

            $id_list[$attr_key] = $attr_key;
        }

        $goods_attr_id = handle_goods_attr($product['goods_id'], $id_list, $is_spec_list, $value_price_list);
        //var_dump($goods_attr_id);
        /* 是否为重复规格的货品 */
        $goods_attr = sort_goods_attr_id_array($goods_attr_id);
        $goods_attr = implode('|', $goods_attr['sort']);
		//var_dump($goods_attr);
        if (check_goods_attr_exist($goods_attr, $product['goods_id']))
        {
            continue;
        }
        //货品号不为空
        if (!empty($value))
        {
            /* 检测：货品货号是否在商品表和货品表中重复 */
            if (check_goods_sn_exist($value))
            {
                //continue;
				showmessage("商品编号重复",$url);
            }
            if (check_product_sn_exist($value))
            {
                //continue;
				showmessage("货品编号重复",$url);
            }
        }

        /* 插入货品表 */
        $sql = "INSERT INTO " . $datab."products (goods_id, goods_attr, product_sn, product_number)  VALUES ('" . $product['goods_id'] . "', '$goods_attr', '$value', '" . $product['product_number'][$key] . "')";
        //echo $sql."*****<br />";
		$flag=DB::query($sql);
		$insert_id=DB::insert_id();
		if (!$flag)
        {
            continue;
        }

        //货品号为空 自动补货品号
        if (empty($value))
        {
            $sql = "UPDATE " . $datab."products SET product_sn = '" . $goods['goods_sn'] . "g_p" . $insert_id . "' WHERE product_id = '" . $insert_id . "'";
          //  echo $sql."<br />";
			DB::query($sql);
        }
		
    }

    clear_cache_files();
	showmessage("操作成功",$url);
}elseif($operation=="remove"){
	//删除货品
	$product_id=intval($_GET["gid"]);
	if(empty($product_id)){
		echo "不存在这个货品";
		exit;
	}
	//查看货品存不存在
	$sql="SELECT a.uid FROM ".$datab."goods AS a LEFT JOIN ".$datab."products AS b ON a.goods_id=b.goods_id WHERE b.product_id=".$gid;
	$rk=DB::fetch(DB::query($sql));
	if($rk["uid"]!==$_G["uid"]){
		echo "没有权限删除此货品";
		exit;
	}
	$sqld = "DELETE FROM " . $datab."products WHERE product_id = '$product_id'";
	$falg=DB::query($sqld);
	clear_cache_files();
	if($falg){
		echo "删除成功";
	}else{
		echo "删除失败";
	}
	 
	exit;
}elseif($operation=="edit"){
	//编辑货品的货品编号和库存
	$value=$_POST["value"];   //货品编号
	$pid=$_POST["pid"];
	$type=$_POST["type"];
	if(empty($value) || empty($pid) && empty($type)){
		exit;
	}
	//判断这个商品是不是他的
	$sqlu="SELECT a.product_sn,b.uid,b.goods_sn FROM ".$datab."products AS a LEFT JOIN ".$datab."goods AS b ON a.goods_id=b.goods_id WHERE a.product_id=$pid LIMIT 1";
	$fu=DB::fetch(DB::query($sqlu));
	//print_r($fu);
	if($fu["uid"]!=$_G["uid"]){
		exit;
	}
	
	
	/* 修改货品库存和货号 */
	if($type=='number'){
		$sqlhp = "UPDATE " . $datab."products SET product_number = '$value' WHERE product_id = '$pid'";
	}else{
			//如果传过来的货号没有变化，就不用比对重复了
			if($value!=$fu["product_sn"]){
				//货品号不为空
				if (!empty($value))
				{
					/* 检测：货品货号是否在商品表和货品表中重复 */
					if (check_goods_sn_exist($value))
					{
						$f = "goods";   //商品编号重复
						$earr=array("type"=>$f,"msg"=>"商品编号重复","sn"=>$fu["product_sn"]);
						$jsarr=json_encode(array("m"=>$earr));
						print_r($jsarr);
						exit;
					}
				
					if (check_product_sn_exist($value))
					{
						$f = "pro";   //货品编号重复
						$earr=array("type"=>$f,"msg"=>"货品编号重复","sn"=>$fu["product_sn"]);
						$jsarr=json_encode(array("m"=>$earr));
						print_r($jsarr);
						exit;
					}
				}
				$sqlhp = "UPDATE " . $datab."products SET product_sn = '$value' WHERE product_id = '$pid'";
			}
		
	}
	if(!empty($sqlhp)){
		$flag=DB::query($sqlhp);
		if($flag){
			$f= "ok";
			$msg='';
		}else{
			$f = "no";
			$msg='更新失败';
		}
	}
	$earr=array("type"=>$f,"msg"=>"yes");
	$jsarr=json_encode(array("m"=>$earr));
	print_r($jsarr);
	exit;
}


if($shop["uid"]){
	$navtitle=$shop["goods_name"];
}else{
	$navtitle="发布新商品";
}

include_once (template ( $templates ));

?>