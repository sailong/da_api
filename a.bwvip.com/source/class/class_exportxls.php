<?php

/**
 *      [angf!] (C)2001-2099 Comsenz Inc.
 *      $Id: class_exportexcel.php 25228 2012/5/15Z angf $
 */


class exportxls{

    var $name = 'xls-Excel文件';

    function export_begin($keys,$type,$count){

        $this->download($type.'-'.date('YmdHis').'('.$count.').xls');

        echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><style>td{vnd.ms-excel.numberformat:@}</style></head>';
        echo '<table width="100%" border="1">';
        echo '<tr><th filter=all>'.implode('</th><th filter=all>',$keys)."</th></tr>\r\n";
        flush();
    }

    function export_rows($rows){
        foreach($rows as $row){
            echo '<tr><td>'.implode('</td><td>',$row)."</td></tr>\r\n";
        }
        flush();
    }

    function export_finish(){
        echo '</table>';
        flush();
    }




	function download($fname='data',$data=null,$mimeType='application/force-download'){

		if(headers_sent($file,$line)){
			echo 'Header already sent @ '.$file.':'.$line;
			exit();
		}

		//header('Cache-Control: no-cache;must-revalidate'); //fix ie download bug
		header('Pragma: no-cache, no-store');
		header("Expires: Wed, 26 Feb 1997 08:21:57 GMT");

		if(strpos($_SERVER["HTTP_USER_AGENT"],'MSIE')){
			$fname = urlencode($fname);
			header('Content-type: '.$mimeType);
		}else{
			header('Content-type: '.$mimeType.';charset=utf-8');
		}
		header("Content-Disposition: attachment; filename=\"".$fname.'"');
		//header( "Content-Description: File Transfer");

		if($data){
			header('Content-Length: '.strlen($data));
			echo $data;
			exit();
		}
	}

}

?>
