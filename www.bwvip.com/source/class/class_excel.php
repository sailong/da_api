<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: class_xml.php 12943 2010-07-19 01:16:30Z monkey $
 *      2012年4月6日
 *      php导出类
 */

class io_xls{

    var $name = 'xls-Excel文件';

    function export_begin($keys,$type,$count){

        download($type.'-'.date('YmdHis').'('.$count.').xls');

        echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><style>td{vnd.ms-excel.numberformat:@}</style></head>';
        echo '<table width="100%" border="1">';
        echo '<tr><th filter=all>'.implode('</th><th filter=all>',$keys)."</th></tr>\r\n";
        flush();
    }

    function export_rows($rows,$yes_index){
        //$yes_index = array('realname');
        foreach($rows as $row){
            echo "<tr>";
                foreach($row as $k=>$v){
                    if(in_array($k,$yes_index)){
                         echo '<td>'.$v."</td>\r\n";
                    }
                }
                echo "</tr>";
               // echo '<tr><td>'.implode('</td><td>',$row)."</td></tr>\r\n";
            
        }
        flush();
    }

    function export_finish(){
        echo '</table>';
        flush();
    }
}

?>