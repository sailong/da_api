<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: home.php 22839 2011-05-25 08:05:18Z monkey $
 */
define('APPTYPEID', 1);
define('CURSCRIPT', 'home');

if (!empty($_GET['mod']) && ($_GET['mod'] == 'misc' || $_GET['mod'] == 'invite')) {
    define('ALLOWGUEST', 1);
}


require_once '../source/class/class_core.php';
require_once '../source/function/function_home.php';

$discuz = & discuz_core::instance();

$ac = $_GET['ac'];
$do = $_GET['do'];


$cachelist = array('magic', 'userapp', 'usergroups', 'diytemplatenamehome');
$discuz->cachelist = $cachelist;
$discuz->init();

if ($ac == 'tjbaofen') {
    $arra = getgpc('userdk');

    if ($arra) {
        foreach ($arra as $key => $value) {
            $eventid = $_COOKIE["eventid"];
            $fieldid = $_COOKIE["fieldid"];
            if ($eventid && $fieldid) {
                //echo "$key=>$value";
                //echo "<br>";
                //print_r(array_keys($value));

                foreach ($value as $k => $var) {
                    // echo "$k=>$var<br>";
                    $sql_sets['score' . $k] = "`score$k`='$var'";
                    $data['score' . $k] = $var;
                }
                //插入更新操作

                $baofen = DB::fetch_first("select  *  from " . DB::table('nd_score') . "  where uid='$key' and sais_id='$eventid' and fieldid='$fieldid' ");
                $data['uid'] = $key;
                $data['sais_id'] = $eventid;
                $data['fieldid'] = $fieldid;
                if ($baofen['id']) {
                    $sql = "update " . DB::table('nd_score') . " set " . (implode(" , ", $sql_sets)) . " where  uid='$key' and sais_id='$eventid' and fieldid='$fieldid' ";
                } else {
                    $sql = "insert into " . DB::table('nd_score') . " (`" . implode("`,`", array_keys($data)) . "`) values ('" . implode("','", $data) . "')";
                }
                $rs = DB::query($sql);
            }
        }
        //echo '提交成功';
    }
}
$username = $_GET['username'];
$password = $_GET['password'];
if ($ac == 'login' || $ac == '') {
    $lib = 'ndlogin';
}
if ($do == 'loggin') {
    $baofen = DB::fetch_first("select * from " . DB::table('nd_baofen_user') . "  where username='$username' and password='$password'");
    if ($baofen['id']) {
        $id = $baofen['id'];
        $hole = $baofen['hole'];
        $fenz_type = $baofen['fenz_type'];
        $eventid = $baofen['eventid'];
        $fieldid = $baofen['fieldid'];

        setcookie("bfid", $id, time() + 3600 * 24);
        setcookie("bfhole", $hole, time() + 3600 * 24);
        setcookie("fenz_type", $fenz_type, time() + 3600 * 24);
        setcookie("eventid", $eventid, time() + 3600 * 24);
        setcookie("fieldid", $fieldid, time() + 3600 * 24);
        header('Location: /wap/nd.php?ac=ndbaofen&fzt=1');
    }
}
if ($ac == 'ndbaofen') {
    $lib = 'ndbaofen';
    $bfhole = $_COOKIE["bfhole"];
    $fenz_type = $_COOKIE["fenz_type"];
    $eventid = $_COOKIE["eventid"];
    $fieldid = $_COOKIE["fieldid"];
    $arr = explode(',', $bfhole);


    $bf = DB::query("select  uid,realname,item_ident from pre_nd_quny_fz  GROUP BY item_ident");
    while ($row = DB::fetch($bf)) {
        $fz[] = $row;
    }


    $fzt = $_GET['fzt'];
    if ($fzt) {
        $sql = 'select uid,realname,qc_id from ' . DB::table('nd_quny_fz') . " where sais_id='$eventid' and fenz_type='$fenz_type' and qc_id='$fieldid' and item_ident='$fzt'";

        $query = DB::query($sql);
        while ($list = mysql_fetch_assoc($query)) {
            $bmlist[] = $list;
        }
		print_R($bmlist);exit;
    }
}

if ($ac == 'nlist') {

    $lib = 'ndbaolist';
    $bf = DB::query("select  uid,realname,item_ident from pre_nd_quny_fz  GROUP BY item_ident");
    while ($row = DB::fetch($bf)) {
        $fz[] = $row;
    }
}

define('CURMODULE', $mod);

runhooks();


require_once libfile('wap/' . $lib, 'include');
?>