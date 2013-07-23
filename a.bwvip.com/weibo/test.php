 <?php 
 
/*
 $path_parts=pathinfo($in); 
echo $path_parts["dirname"] . "<br>";
echo $path_parts["basename"] ."<br>";
echo $path_parts["extension"] . "<br>";*/

 flv_convert_get_thumb('/home/www/dzbwvip/01.rmvb','output.flv'); 
 
 //得到扩展名
// 1: 需转换文件路径 最好为绝对路径
// 2: 转换后文件路径
function flv_convert_get_thumb($in,  $out)
{ 
 if(!is_file($in)){
  return false;
}
$path_parts=pathinfo($in); 
echo $path_parts["dirname"] . "<br>";
echo $path_parts["basename"] ."<br>";
echo $path_parts["extension"] . "<br>";
 
  //视频转换
  $cmd = 'mencoder '.$in.' -o '.$out.' -af volume=10 -aspect 16:9 -of avi -noodml -ovc x264 -x264encopts bitrate=500:level_idc=41:bframes=3:frameref=2: nopsnr: nossim: pass=1: threads=auto -oac mp3lame';
  $res = shell_exec($cmd); 
  if($res){
 //得到mp4
    //$cmd = '/usr/local/bin/ffmpeg  -i '.$out.' -y  -ab 56 -ar 22050  -b 1500 -r 15   -qscale  10 -s 480x320 '.$out.'.mp4';
	 $cmd = 'mencoder '.$in.' -o '.$out.'.mp4 -af volume=10 -aspect 16:9 -of avi -noodml -ovc x264 -x264encopts bitrate=500:level_idc=41:bframes=3:frameref=2: nopsnr: nossim: pass=1: threads=auto -oac mp3lame';
  $res = shell_exec($cmd);
 //得到图片
    $cmd = '/usr/local/bin/ffmpeg  -i '.$out.' -y -f image2 -t 0.001 -s 352x240 '.$out.'.jpg';
  $res = shell_exec($cmd);
  }
}
 
 

?> 