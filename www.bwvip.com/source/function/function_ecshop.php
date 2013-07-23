<?php
/**
 * 获得指定分类下的子分类的数组
 *
 * @access  public
 * @param   int     $cat_id     分类的ID
 * @param   int     $selected   当前选中分类的ID
 * @param   boolean $re_type    返回的类型: 值为真时返回下拉列表,否则返回数组
 * @param   int     $level      限定返回的级数。为0时返回所有级数
 * @param   int     $is_show_all 如果为true显示所有分类，如果为false隐藏不可见分类。
 * @return  mix
 */
function cat_list($cat_id = 0, $selected = 0, $re_type = true, $level = 0, $is_show_all = true)
{
	global $datab;
    static $res = NULL;

    if ($res === NULL)
    {
            $sql = "SELECT c.cat_id, c.cat_name, c.measure_unit, c.parent_id, c.is_show, c.show_in_nav, c.grade, c.sort_order, COUNT(s.cat_id) AS has_children FROM ".$datab."category AS c LEFT JOIN ".$datab."category AS s ON s.parent_id=c.cat_id GROUP BY c.cat_id ORDER BY c.parent_id, c.sort_order ASC";
            $sqlone = DB::query($sql);
			while($row=DB::fetch($sqlone)){
				$res[]=$row;
			}
			
			
            $sql = "SELECT cat_id, COUNT(*) AS goods_num " .
                    " FROM ".$datab."goods".
                    " WHERE is_delete = 0 AND is_on_sale = 1 " .
                    " GROUP BY cat_id";
            $sql2 = DB::query($sql);
			while($row=DB::fetch($sql2)){
				$res2[]=$row;
			}
            $sql = "SELECT gc.cat_id, COUNT(*) AS goods_num " .
                    " FROM ".$datab."goods_cat AS gc , ".$datab."goods AS g " .
                    " WHERE g.goods_id = gc.goods_id AND g.is_delete = 0 AND g.is_on_sale = 1 " .
                    " GROUP BY gc.cat_id";
            $sql3 = DB::query($sql);
			while($row=DB::fetch($sql3)){
				$res3[]=$row;
			}
            $newres = array();
            foreach($res2 as $k=>$v)
            {
                $newres[$v['cat_id']] = $v['goods_num'];
                foreach($res3 as $ks=>$vs)
                {
                    if($v['cat_id'] == $vs['cat_id'])
                    {
                    $newres[$v['cat_id']] = $v['goods_num'] + $vs['goods_num'];
                    }
                }
            }

            foreach($res as $k=>$v)
            {
                $res[$k]['goods_num'] = !empty($newres[$v['cat_id']]) ? $newres[$v['cat_id']] : 0;
            }

    }

    if (empty($res) == true)
    {
        return $re_type ? '' : array();
    }
//var_dump($res);
    $options = cat_options($cat_id, $res); // 获得指定分类下的子分类的数组


    $children_level = 99999; //大于这个分类的将被删除
    if ($is_show_all == false)
    {
        foreach ($options as $key => $val)
        {
            if ($val['level'] > $children_level)
            {
                unset($options[$key]);
            }
            else
            {
                if ($val['is_show'] == 0)
                {
                    unset($options[$key]);
                    if ($children_level > $val['level'])
                    {
                        $children_level = $val['level']; //标记一下，这样子分类也能删除
                    }
                }
                else
                {
                    $children_level = 99999; //恢复初始值
                }
            }
        }
    }

    /* 截取到指定的缩减级别 */
    if ($level > 0)
    {
        if ($cat_id == 0)
        {
            $end_level = $level;
        }
        else
        {
            $first_item = reset($options); // 获取第一个元素
            $end_level  = $first_item['level'] + $level;
        }

        /* 保留level小于end_level的部分 */
        foreach ($options AS $key => $val)
        {
            if ($val['level'] >= $end_level)
            {
                unset($options[$key]);
            }
        }
    }
//var_dump($options);
    if ($re_type == true)
    {
        $select = '';
        foreach ($options AS $var)
        {
            $select .= '<option value="' . $var['cat_id'] . '" ';
            $select .= ($selected == $var['cat_id']) ? "selected='ture'" : '';
            $select .= '>';
            if ($var['level'] > 0)
            {
                $select .= str_repeat('&nbsp;', $var['level'] * 4);
            }
            $select .= htmlspecialchars(addslashes($var['cat_name']), ENT_QUOTES) . '</option>';
        }
        //var_dump($select);
        return $select;
    }
    else
    {
        foreach ($options AS $key => $value)
        {
            $options[$key]['url'] = build_uri('category', array('cid' => $value['cat_id']), $value['cat_name']);
        }
        return $options;
        
    }
    //var_dump($options);
   
}


/**
 * 过滤和排序所有分类，返回一个带有缩进级别的数组
 *
 * @access  private
 * @param   int     $cat_id     上级分类ID
 * @param   array   $arr        含有所有分类的数组
 * @param   int     $level      级别
 * @return  void
 */
function cat_options($spec_cat_id, $arr)
{
	global $datab;
    static $cat_options = array();

    if (isset($cat_options[$spec_cat_id]))
    {
        return $cat_options[$spec_cat_id];
    }

    if (!isset($cat_options[0]))
    {
        $level = $last_cat_id = 0;
        $options = $cat_id_array = $level_array = array();
            while (!empty($arr))
            {
                foreach ($arr AS $key => $value)
                {
                    $cat_id = $value['cat_id'];
                    if ($level == 0 && $last_cat_id == 0)
                    {
                        if ($value['parent_id'] > 0)
                        {
                            break;
                        }

                        $options[$cat_id]          = $value;
                        $options[$cat_id]['level'] = $level;
                        $options[$cat_id]['id']    = $cat_id;
                        $options[$cat_id]['name']  = $value['cat_name'];
                        unset($arr[$key]);

                        if ($value['has_children'] == 0)
                        {
                            continue;
                        }
                        $last_cat_id  = $cat_id;
                        $cat_id_array = array($cat_id);
                        $level_array[$last_cat_id] = ++$level;
                        continue;
                    }

                    if ($value['parent_id'] == $last_cat_id)
                    {
                        $options[$cat_id]          = $value;
                        $options[$cat_id]['level'] = $level;
                        $options[$cat_id]['id']    = $cat_id;
                        $options[$cat_id]['name']  = $value['cat_name'];
                        unset($arr[$key]);

                        if ($value['has_children'] > 0)
                        {
                            if (end($cat_id_array) != $last_cat_id)
                            {
                                $cat_id_array[] = $last_cat_id;
                            }
                            $last_cat_id    = $cat_id;
                            $cat_id_array[] = $cat_id;
                            $level_array[$last_cat_id] = ++$level;
                        }
                    }
                    elseif ($value['parent_id'] > $last_cat_id)
                    {
                        break;
                    }
                }

                $count = count($cat_id_array);
                if ($count > 1)
                {
                    $last_cat_id = array_pop($cat_id_array);
                }
                elseif ($count == 1)
                {
                    if ($last_cat_id != end($cat_id_array))
                    {
                        $last_cat_id = end($cat_id_array);
                    }
                    else
                    {
                        $level = 0;
                        $last_cat_id = 0;
                        $cat_id_array = array();
                        continue;
                    }
                }

                if ($last_cat_id && isset($level_array[$last_cat_id]))
                {
                    $level = $level_array[$last_cat_id];
                }
                else
                {
                    $level = 0;
                }
            }
            
        
        $cat_options[0] = $options;
    }
    else
    {
        $options = $cat_options[0];
    }

    if (!$spec_cat_id)
    {
        return $options;
    }
    else
    {
        if (empty($options[$spec_cat_id]))
        {
            return array();
        }

        $spec_cat_id_level = $options[$spec_cat_id]['level'];

        foreach ($options AS $key => $value)
        {
            if ($key != $spec_cat_id)
            {
                unset($options[$key]);
            }
            else
            {
                break;
            }
        }

        $spec_cat_id_array = array();
        foreach ($options AS $key => $value)
        {
            if (($spec_cat_id_level == $value['level'] && $value['cat_id'] != $spec_cat_id) ||
                ($spec_cat_id_level > $value['level']))
            {
                break;
            }
            else
            {
                $spec_cat_id_array[$key] = $value;
            }
        }
        $cat_options[$spec_cat_id] = $spec_cat_id_array;

        return $spec_cat_id_array;
    }
}


/**
 * 获得商品类型的列表
 *
 * @access  public
 * @param   integer     $selected   选定的类型编号
 * @return  string
 */
function goods_type_list($selected)
{
	global $datab;
    $sql = "SELECT cat_id, cat_name FROM ".$datab."goods_type WHERE enabled = 1";
    $res = DB::query($sql);

    $lst = '';
    while ($row = DB::fetch($res))
    {
        $lst .= "<option value='$row[cat_id]'";
        $lst .= ($selected == $row['cat_id']) ? ' selected="true"' : '';
        $lst .= '>' . htmlspecialchars($row['cat_name']). '</option>';
    }

    return $lst;
}


/**
 * 取得通用属性和某分类的属性，以及某商品的属性值
 * @param   int     $cat_id     分类编号
 * @param   int     $goods_id   商品编号
 * @return  array   规格与属性列表
 */
function get_attr_list($cat_id, $goods_id = 0)
{
	global $datab;
    if (empty($cat_id))
    {
        return array();
    }

    // 查询属性值及商品的属性值
    $sql = "SELECT a.attr_id, a.attr_name, a.attr_input_type, a.attr_type, a.attr_values, v.attr_value, v.attr_price ".
            "FROM ".$datab."attribute AS a ".
            "LEFT JOIN ".$datab."goods_attr AS v ".
            "ON v.attr_id = a.attr_id AND v.goods_id = '$goods_id' ".
            "WHERE a.cat_id = " . intval($cat_id) ." OR a.cat_id = 0 ".
            "ORDER BY a.sort_order, a.attr_type, a.attr_id, v.attr_price, v.goods_attr_id";

    $re = DB::query($sql);
	while($row=DB::fetch($re)){
		$rowarr[]=$row;
	}
    return $rowarr;
}
/**
 * 根据属性数组创建属性的表单
 *
 * @access  public
 * @param   int     $cat_id     分类编号
 * @param   int     $goods_id   商品编号
 * @return  string
 */
function build_attr_html($cat_id, $goods_id = 0)
{
	global $datab;
    $attr = get_attr_list($cat_id, $goods_id);
    $html = '<table width="100%" id="attrTable">';
    $spec = 0;

    foreach ($attr AS $key => $val)
    {
        $html .= "<tr><td class='label'>";
        if ($val['attr_type'] == 1 || $val['attr_type'] == 2)
        {
            $html .= ($spec != $val['attr_id']) ?
                "<a href='javascript:;' onclick='addSpec(this)'>[+]</a>" :
                "<a href='javascript:;' onclick='removeSpec(this)'>[-]</a>";
            $spec = $val['attr_id'];
        }

        $html .= "$val[attr_name]</td><td><input type='hidden' name='attr_id_list[]' value='$val[attr_id]' />";

        if ($val['attr_input_type'] == 0)
        {
            $html .= '<input name="attr_value_list[]" type="text" value="' .htmlspecialchars($val['attr_value']). '" size="40" /> ';
        }
        elseif ($val['attr_input_type'] == 2)
        {
            $html .= '<textarea name="attr_value_list[]" rows="3" cols="40">' .htmlspecialchars($val['attr_value']). '</textarea>';
        }
        else
        {
            $html .= '<select name="attr_value_list[]">';
            $html .= '<option value="">请选择</option>';

            $attr_values = explode("\n", $val['attr_values']);

            foreach ($attr_values AS $opt)
            {
                $opt    = trim(htmlspecialchars($opt));

                $html   .= ($val['attr_value'] != $opt) ?
                    '<option value="' . $opt . '">' . $opt . '</option>' :
                    '<option value="' . $opt . '" selected="selected">' . $opt . '</option>';
            }
            $html .= '</select> ';
        }

        $html .= ($val['attr_type'] == 1 || $val['attr_type'] == 2) ?
            $GLOBALS['_LANG']['spec_price'].' <input type="text" name="attr_price_list[]" value="' . $val['attr_price'] . '" size="5" maxlength="10" />' :
            ' <input type="hidden" name="attr_price_list[]" value="0" />';

        $html .= '</td></tr>';
    }

    $html .= '</table>';

    return $html;
}

/**
 * 清除缓存文件
 *
 * @access  public
 * @param   mix     $ext    模版文件名， 不包含后缀
 * @return  void
 */
function clear_cache_files($ext = '')
{
    return clear_tpl_files(true, $ext);
}

/**
 *  清除指定后缀的模板缓存或编译文件
 *
 * @access  public
 * @param  bool       $is_cache  是否清除缓存还是清出编译文件
 * @param  string     $ext       需要删除的文件名，不包含后缀
 *
 * @return int        返回清除的文件个数
 */
function clear_tpl_files($is_cache = true, $ext = '')
{
	$tmp_dir=str_replace('\\',"/",$_SERVER["DOCUMENT_ROOT"])."/shop/temp";
    $dirs = array();

    if ($is_cache)
    {
        $cache_dir =  $tmp_dir . '/caches/';
        $dirs[] =  $tmp_dir . '/query_caches/';
        $dirs[] = $tmp_dir . '/static_caches/';
        for($i = 0; $i < 16; $i++)
        {
            $hash_dir = $cache_dir . dechex($i);
            $dirs[] = $hash_dir . '/';
        }
    }
    else
    {
        $dirs[] =  $tmp_dir . '/compiled/';
        $dirs[] =  $tmp_dir . '/compiled/admin/';
    }

    $str_len = strlen($ext);
    $count   = 0;

    foreach ($dirs AS $dir)
    {
        $folder = @opendir($dir);

        if ($folder === false)
        {
            continue;
        }

        while ($file = readdir($folder))
        {
            if ($file == '.' || $file == '..' || $file == 'index.htm' || $file == 'index.html')
            {
                continue;
            }
            if (is_file($dir . $file))
            {
                /* 如果有文件名则判断是否匹配 */
                $pos = ($is_cache) ? strrpos($file, '_') : strrpos($file, '.');

                if ($str_len > 0 && $pos !== false)
                {
                    $ext_str = substr($file, 0, $pos);

                    if ($ext_str == $ext)
                    {
                        if (@unlink($dir . $file))
                        {
                            $count++;
                        }
                    }
                }
                else
                {
                    if (@unlink($dir . $file))
                    {
                        $count++;
                    }
                }
            }
        }
        closedir($folder);
    }

    return $count;
}

/**
 * 获得商品已添加的规格列表
 *
 * @access      public
 * @params      integer         $goods_id
 * @return      array
 */
function get_goods_specifications_list($goods_id)
{
	global $datab;
    if (empty($goods_id))
    {
        return array();  //$goods_id不能为空
    }

    $sql = "SELECT g.goods_attr_id, g.attr_value, g.attr_id, a.attr_name FROM " . $datab."goods_attr AS g LEFT JOIN " . $datab."attribute AS a ON a.attr_id = g.attr_id WHERE goods_id = '$goods_id' AND a.attr_type = 1 ORDER BY g.attr_id ASC";
    //echo $sql;
	$rek=DB::query($sql);
	while($row = DB::fetch($rek)){
		$results[]=$row;
	}

    return $results;
}

/**
 * 获得商品的货品列表
 *
 * @access  public
 * @params  integer $goods_id
 * @params  string  $conditions
 * @return  array
 */
function product_list($goods_id, $conditions = '')
{
	global $datab;
    /* 过滤条件 */
    $param_str = '-' . $goods_id;
    $result = get_filter($param_str);
    if ($result === false)
    {
        $day = getdate();
        $today = local_mktime(23, 59, 59, $day['mon'], $day['mday'], $day['year']);

        $filter['goods_id']         = $goods_id;
        $filter['keyword']          = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
        $filter['stock_warning']    = empty($_REQUEST['stock_warning']) ? 0 : intval($_REQUEST['stock_warning']);

        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
        {
            $filter['keyword'] = json_str_iconv($filter['keyword']);
        }
        $filter['sort_by']          = empty($_REQUEST['sort_by']) ? 'product_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order']       = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
        $filter['extension_code']   = empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']);
        $filter['page_count'] = isset($filter['page_count']) ? $filter['page_count'] : 1;

        $where = '';

        /* 库存警告 */
        if ($filter['stock_warning'])
        {
            $where .= ' AND goods_number <= warn_number ';
        }

        /* 关键字 */
        if (!empty($filter['keyword']))
        {
            $where .= " AND (product_sn LIKE '%" . $filter['keyword'] . "%')";
        }

        $where .= $conditions;

        /* 记录总数 */
        $sql = "SELECT COUNT(*) FROM " .$datab."products AS p WHERE goods_id = $goods_id $where";
        $filter['record_count'] = DB::result(DB::query($sql));

        $sql = "SELECT product_id, goods_id, goods_attr, product_sn, product_number
                FROM " . $datab."products AS g
                WHERE goods_id = $goods_id $where
                ORDER BY $filter[sort_by] $filter[sort_order]";

        $filter['keyword'] = stripslashes($filter['keyword']);
        //set_filter($filter, $sql, $param_str);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    //$row = $GLOBALS['db']->getAll($sql);
	$rb=DB::query($sql);
	while($tb=DB::fetch($rb)){
		$row[]=$tb;
	}

    /* 处理规格属性 */
    $goods_attr = product_goods_attr_list($goods_id);
    foreach ($row as $key => $value)
    {
        $_goods_attr_array = explode('|', $value['goods_attr']);
        if (is_array($_goods_attr_array))
        {
            $_temp = '';
            foreach ($_goods_attr_array as $_goods_attr_value)
            {
                 $_temp[] = $goods_attr[$_goods_attr_value];
            }
            $row[$key]['goods_attr'] = $_temp;
        }
    }

    return array('product' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

/**
 * 获得商品的规格属性值列表
 *
 * @access      public
 * @params      integer         $goods_id
 * @return      array
 */
function product_goods_attr_list($goods_id)
{
	global $datab;
    if (empty($goods_id))
    {
        return array();  //$goods_id不能为空
    }

    $sql = "SELECT goods_attr_id, attr_value FROM " . $datab."goods_attr WHERE goods_id = '$goods_id'";
   
	$ty=DB::query($sql);
	while($row=DB::fetch($ty)){
		$results[]=$row;
	}
    $return_arr = array();
    foreach ($results as $value)
    {
        $return_arr[$value['goods_attr_id']] = $value['attr_value'];
    }

    return $return_arr;
}

/**
 * 取得上次的过滤条件
 * @param   string  $param_str  参数字符串，由list函数的参数组成
 * @return  如果有，返回array('filter' => $filter, 'sql' => $sql)；否则返回false
 */
function get_filter($param_str = '')
{
    $filterfile = basename( $_SERVER['PHP_SELF'], '.php');
    if ($param_str)
    {
        $filterfile .= $param_str;
    }
    if (isset($_GET['uselastfilter']) && isset($_COOKIE['ECSCP']['lastfilterfile'])
        && $_COOKIE['ECSCP']['lastfilterfile'] == sprintf('%X', crc32($filterfile)))
    {
        return array(
            'filter' => unserialize(urldecode($_COOKIE['ECSCP']['lastfilter'])),
            'sql'    => base64_decode($_COOKIE['ECSCP']['lastfiltersql'])
        );
    }
    else
    {
        return false;
    }
}

/**
 *  生成一个用户自定义时区日期的GMT时间戳
 *
 * @access  public
 * @param   int     $hour
 * @param   int     $minute
 * @param   int     $second
 * @param   int     $month
 * @param   int     $day
 * @param   int     $year
 *
 * @return void
 */
function local_mktime($hour = NULL , $minute= NULL, $second = NULL,  $month = NULL,  $day = NULL,  $year = NULL)
{
    $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];

    /**
    * $time = mktime($hour, $minute, $second, $month, $day, $year) - date('Z') + (date('Z') - $timezone * 3600)
    * 先用mktime生成时间戳，再减去date('Z')转换为GMT时间，然后修正为用户自定义时间。以下是化简后结果
    **/
    $time = mktime($hour, $minute, $second, $month, $day, $year) - $timezone * 3600;

    return $time;
}

/**
 * 插入或更新商品属性
 *
 * @param   int     $goods_id           商品编号
 * @param   array   $id_list            属性编号数组
 * @param   array   $is_spec_list       是否规格数组 'true' | 'false'
 * @param   array   $value_price_list   属性值数组
 * @return  array                       返回受到影响的goods_attr_id数组
 */
function handle_goods_attr($goods_id, $id_list, $is_spec_list, $value_price_list)
{
	global $datab; 
    $goods_attr_id = array();

    /* 循环处理每个属性 */
    foreach ($id_list AS $key => $id)
    {
        $is_spec = $is_spec_list[$key];
        if ($is_spec == 'false')
        {
            $value = $value_price_list[$key];
            $price = '';
        }
        else
        {
            $value_list = array();
            $price_list = array();
            if ($value_price_list[$key])
            {
                $vp_list = explode(chr(13), $value_price_list[$key]);
                foreach ($vp_list AS $v_p)
                {
                    $arr = explode(chr(9), $v_p);
                    $value_list[] = $arr[0];
                    $price_list[] = $arr[1];
                }
            }
            $value = join(chr(13), $value_list);
            $price = join(chr(13), $price_list);
        }

        // 插入或更新记录
        $sql = "SELECT goods_attr_id FROM " . $datab."goods_attr WHERE goods_id = '$goods_id' AND attr_id = '$id' AND attr_value = '$value' LIMIT 0, 1";
        $rid=DB::fetch(DB::query($sql));
		$result_id = $rid["goods_attr_id"];
        if (!empty($result_id["goods_attr_id"]))
        {
            $sql = "UPDATE " . $datab."goods_attr SET attr_value = '$value' WHERE goods_id = '$goods_id' AND attr_id = '$id' AND goods_attr_id = '$result_id'";
            $goods_attr_id[$id] = $result_id;
        }
        else
        {
            $sql = "INSERT INTO " . $datab."goods_attr (goods_id, attr_id, attr_value, attr_price) VALUES ('$goods_id', '$id', '$value', '$price')";
        }

        DB::query($sql);

        if ($goods_attr_id[$id] == '')
        {
            $goods_attr_id[$id] = DB::insert_id();
        }
    }

    return $goods_attr_id;
}


/**
 * 将 goods_attr_id 的序列按照 attr_id 重新排序
 *
 * 注意：非规格属性的id会被排除
 *
 * @access      public
 * @param       array       $goods_attr_id_array        一维数组
 * @param       string      $sort                       序号：asc|desc，默认为：asc
 *
 * @return      string
 */
function sort_goods_attr_id_array($goods_attr_id_array, $sort = 'asc')
{
	global $datab;
    if (empty($goods_attr_id_array))
    {
        return $goods_attr_id_array;
    }

    //重新排序
    $sql = "SELECT a.attr_type, v.attr_value, v.goods_attr_id
            FROM " .$datab."attribute AS a
            LEFT JOIN " .$datab."goods_attr AS v
                ON v.attr_id = a.attr_id
                AND a.attr_type = 1
            WHERE v.goods_attr_id " . db_create_in($goods_attr_id_array) . "
            ORDER BY a.attr_id $sort";
    $re = DB::query($sql);
	while($rb=DB::fetch($re)){
		$row[]=$rb;
	}

    $return_arr = array();
    foreach ($row as $value)
    {
        $return_arr['sort'][]   = $value['goods_attr_id'];

        $return_arr['row'][$value['goods_attr_id']]    = $value;
    }

    return $return_arr;
}


/**
 * 创建像这样的查询: "IN('a','b')";
 *
 * @access   public
 * @param    mix      $item_list      列表数组或字符串
 * @param    string   $field_name     字段名称
 *
 * @return   void
 */
function db_create_in($item_list, $field_name = '')
{
    if (empty($item_list))
    {
        return $field_name . " IN ('') ";
    }
    else
    {
        if (!is_array($item_list))
        {
            $item_list = explode(',', $item_list);
        }
        $item_list = array_unique($item_list);
        $item_list_tmp = '';
        foreach ($item_list AS $item)
        {
            if ($item !== '')
            {
                $item_list_tmp .= $item_list_tmp ? ",'$item'" : "'$item'";
            }
        }
        if (empty($item_list_tmp))
        {
            return $field_name . " IN ('') ";
        }
        else
        {
            return $field_name . ' IN (' . $item_list_tmp . ') ';
        }
    }
}


/**
 * 商品的货品规格是否存在
 *
 * @param   string     $goods_attr        商品的货品规格
 * @param   string     $goods_id          商品id
 * @param   int        $product_id        商品的货品id；默认值为：0，没有货品id
 * @return  bool                          true，重复；false，不重复
 */
function check_goods_attr_exist($goods_attr, $goods_id, $product_id = 0)
{
	global $datab;
    $goods_id = intval($goods_id);
    if (strlen($goods_attr) == 0 || empty($goods_id))
    {
        return true;    //重复
    }

    if (empty($product_id))
    {
        $sql = "SELECT product_id FROM " . $datab. "products WHERE goods_attr = '$goods_attr' AND goods_id = '$goods_id'";
    }
    else
    {
        $sql = "SELECT product_id FROM " . $datab."products WHERE goods_attr = '$goods_attr' AND goods_id = '$goods_id' AND product_id <> '$product_id'";
    }

    $res = DB::fetch(DB::query($sql));

    if (empty($res["product_id"]))
    {
        return false;    //不重复
    }
    else
    {
        return true;    //重复
    }
}

/**
 * 商品货号是否重复
 *
 * @param   string     $goods_sn        商品货号；请在传入本参数前对本参数进行SQl脚本过滤
 * @param   int        $goods_id        商品id；默认值为：0，没有商品id
 * @return  bool                        true，重复；false，不重复
 */
function check_goods_sn_exist($goods_sn, $goods_id = 0)
{
	global $datab;
    $goods_sn = trim($goods_sn);
    $goods_id = intval($goods_id);
    if (strlen($goods_sn) == 0)
    {
        return true;    //重复
    }

    if (empty($goods_id))
    {
        $sql = "SELECT goods_id FROM " . $datab."goods WHERE goods_sn = '$goods_sn'";
    }
    else
    {
        $sql = "SELECT goods_id FROM " . $datab."goods WHERE goods_sn = '$goods_sn' AND goods_id <> '$goods_id'";
    }

    $res = DB::fetch(DB::query($sql));

    if (empty($res["goods_id"]))
    {
        return false;    //不重复
    }
    else
    {
        return true;    //重复
    }

}

/**
 * 商品的货品货号是否重复
 *
 * @param   string     $product_sn        商品的货品货号；请在传入本参数前对本参数进行SQl脚本过滤
 * @param   int        $product_id        商品的货品id；默认值为：0，没有货品id
 * @return  bool                          true，重复；false，不重复
 */
function check_product_sn_exist($product_sn, $product_id = 0)
{
	global $datab;
    $product_sn = trim($product_sn);
    $product_id = intval($product_id);
    if (strlen($product_sn) == 0)
    {
        return true;    //重复
    }
    $sql="SELECT goods_id FROM ". $datab."goods WHERE goods_sn='$product_sn'";
    $flag=DB::fetch(DB::query($sql));
	if($flag["goods_id"])
    {
        return true;    //重复
    }


    if (empty($product_id))
    {
        $sql = "SELECT product_id FROM " . $datab."products WHERE product_sn = '$product_sn'";
    }
    else
    {
        $sql = "SELECT product_id FROM " . $datab."products WHERE product_sn = '$product_sn' AND product_id <> '$product_id'";
    }

    $res = DB::fetch(DB::query($sql));

    if (empty($res["product_id"]))
    {
        return false;    //不重复
    }
    else
    {
        return true;    //重复
    }
}

/**
 * 修改商品库存
 * @param   string  $goods_id   商品编号，可以为多个，用 ',' 隔开
 * @param   string  $value      字段值
 * @return  bool
 */
function update_goods_stock($goods_id, $value)
{
	global $datab;
    if ($goods_id)
    {
        
        $sql = "UPDATE " . $datab."goods SET goods_number = goods_number + $value,last_update = '". gmtime1() ."' WHERE goods_id = '$goods_id'";
        $result = DB::query($sql);

        /* 清除缓存 */
        clear_cache_files();

        return $result;
    }
    else
    {
        return false;
    }
}

/**
 * 获得当前格林威治时间的时间戳
 *
 * @return  integer
 */
function gmtime1()
{
    return (time() - date('Z'));
}


/**
 * 为某商品生成唯一的货号
 * @param   int     $goods_id   商品编号
 * @return  string  唯一的货号
 */
function generate_goods_sn($goods_id)
{
	global $datab;
    $goods_sn = "EC" . str_repeat('0', 6 - strlen($goods_id)) . $goods_id;

    $sql = "SELECT goods_sn FROM " . $datab."goods".
            " WHERE goods_sn LIKE '" . mysql_like_quote($goods_sn) . "%' AND goods_id <> '$goods_id' " .
            " ORDER BY LENGTH(goods_sn) DESC";
	//echo $sql;
    $sn_list = DB::fetch_first($sql);
	//var_dump($sn_list);
    if (in_array($goods_sn, $sn_list))
    {
        $max = pow(10, strlen($sn_list[0]) - strlen($goods_sn) + 1) - 1;
        $new_sn = $goods_sn . mt_rand(0, $max);
        while (in_array($new_sn, $sn_list))
        {
            $new_sn = $goods_sn . mt_rand(0, $max);
        }
        $goods_sn = $new_sn;
    }

    return $goods_sn;
}

/**
 * 对 MYSQL LIKE 的内容进行转义
 *
 * @access      public
 * @param       string      string  内容
 * @return      string
 */
function mysql_like_quote($str)
{
    return strtr($str, array("\\\\" => "\\\\\\\\", '_' => '\_', '%' => '\%', "\'" => "\\\\\'"));
}

?>