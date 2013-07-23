<?php
class smtp
{
var $smtp_port;
var $time_out;
var $host_name;
var $log_file;
var $relay_host;
var $debug;
var $auth;
var $user;
var $pass;
var $sock;
function smtp($relay_host = "", $smtp_port = 25,$auth = false,$user,$pass)
{
$this->debug = true;
$this->smtp_port = $smtp_port;
$this->relay_host = $relay_host;
$this->time_out = 30;
$this->auth = $auth;
$this->user = $user;
$this->pass = $pass;
$this->host_name = "localhost";
$this->log_file ="";
$this->sock = FALSE;
}
function sendmail($to, $from, $subject = "", $body = "", $mailtype, $cc = "", $bcc = "", $additional_headers = "")
{
$mail_from = $this->get_address($this->strip_comment($from));
$body = ereg_replace("(^|(\r\n))(\\.)", "\\1.\\3", $body);
$header .= "MIME-Version:1.0\r\n";
if($mailtype=="HTML")
{
$header .= "Content-Type:text/html\r\n";
}
 $header .= "To: ".$to."\r\n";
if ($cc != "")
{
$header .= "Cc: ".$cc."\r\n";
}
$header .= "From: $from<".$from.">\r\n";
$header .= "Subject: ".$subject."\r\n";
$header .= $additional_headers;
$header .= "Date: ".date("r")."\r\n";
$header .= "X-Mailer:By Redhat (PHP/".phpversion().")\r\n";
list($msec, $sec) = explode(" ", microtime());
$header .= "Message-ID: <".date("YmdHis", $sec).".".($msec*1000000).".".$mail_from.">\r\n";
$TO = explode(",", $this->strip_comment($to));
if ($cc != "") {
$TO = array_merge($TO, explode(",", $this->strip_comment($cc)));
}
if ($bcc != "") {
$TO = array_merge($TO, explode(",", $this->strip_comment($bcc)));
}
$sent = TRUE;
foreach ($TO as $rcpt_to) {
$rcpt_to = $this->get_address($rcpt_to);
if (!$this->smtp_sockopen($rcpt_to)) {
$this->log_write("Error: Cannot send email to ".$rcpt_to."\n");
$sent = FALSE;
continue;
}
if ($this->smtp_send($this->host_name, $mail_from, $rcpt_to, $header, $body)) {
$this->log_write("E-mail has been sent to <".$rcpt_to.">\n");
} else {
$this->log_write("Error: Cannot send email to <".$rcpt_to.">\n");
$sent = FALSE;
}
fclose($this->sock);
$this->log_write("Disconnected from remote host\n");
}
//echo "<br>";
//echo $header;
return $sent;
}
/* Private Functions */
function smtp_send($helo, $from, $to, $header, $body = "")
{
if (!$this->smtp_putcmd("HELO", $helo)) {
return $this->smtp_error("sending HELO command");
}
#auth
if($this->auth){
if (!$this->smtp_putcmd("AUTH LOGIN", base64_encode($this->user))) {
return $this->smtp_error("sending HELO command");
}
if (!$this->smtp_putcmd("", base64_encode($this->pass))) {
return $this->smtp_error("sending HELO command");
}
}
#
if (!$this->smtp_putcmd("MAIL", "FROM:<".$from.">")) {
return $this->smtp_error("sending MAIL FROM command");
}
if (!$this->smtp_putcmd("RCPT", "TO:<".$to.">")) {
return $this->smtp_error("sending RCPT TO command");
}
if (!$this->smtp_putcmd("DATA")) {
return $this->smtp_error("sending DATA command");
}
if (!$this->smtp_message($header, $body)) {
return $this->smtp_error("sending message");
}
if (!$this->smtp_eom()) {
return $this->smtp_error("sending <CR><LF>.<CR><LF> [EOM]");
}
if (!$this->smtp_putcmd("QUIT")) {
return $this->smtp_error("sending QUIT command");
}
return TRUE;
}
function smtp_sockopen($address)
{
if ($this->relay_host == "") {
return $this->smtp_sockopen_mx($address);
} else {
return $this->smtp_sockopen_relay();
}
}
function smtp_sockopen_relay()
{
$this->log_write("Trying to ".$this->relay_host.":".$this->smtp_port."\n");
$this->sock = @fsockopen($this->relay_host, $this->smtp_port, $errno, $errstr, $this->time_out);
if (!($this->sock && $this->smtp_ok())) {
$this->log_write("Error: Cannot connenct to relay host ".$this->relay_host."\n");
$this->log_write("Error: ".$errstr." (".$errno.")\n");
return FALSE;
}
$this->log_write("Connected to relay host ".$this->relay_host."\n");
return TRUE;;
}
function smtp_sockopen_mx($address)
{
$domain = ereg_replace("^.+@([^@]+)$", "\\1", $address);
if (!@getmxrr($domain, $MXHOSTS)) {
$this->log_write("Error: Cannot resolve MX \"".$domain."\"\n");
return FALSE;
}
foreach ($MXHOSTS as $host) {
$this->log_write("Trying to ".$host.":".$this->smtp_port."\n");
$this->sock = @fsockopen($host, $this->smtp_port, $errno, $errstr, $this->time_out);
if (!($this->sock && $this->smtp_ok())) {
$this->log_write("Warning: Cannot connect to mx host ".$host."\n");
$this->log_write("Error: ".$errstr." (".$errno.")\n");
continue;
}
$this->log_write("Connected to mx host ".$host."\n");
return TRUE;
}
$this->log_write("Error: Cannot connect to any mx hosts (".implode(", ", $MXHOSTS).")\n");
return FALSE;
}
function smtp_message($header, $body)
{
fputs($this->sock, $header."\r\n".$body);
$this->smtp_debug("> ".str_replace("\r\n", "\n"."> ", $header."\n> ".$body."\n> "));
return TRUE;
}
function smtp_eom()
{
fputs($this->sock, "\r\n.\r\n");
$this->smtp_debug(". [EOM]\n");
return $this->smtp_ok();
}
function smtp_ok()
{
$response = str_replace("\r\n", "", fgets($this->sock, 512));
$this->smtp_debug($response."\n");
if (!ereg("^[23]", $response)) {
fputs($this->sock, "QUIT\r\n");
fgets($this->sock, 512);
$this->log_write("Error: Remote host returned \"".$response."\"\n");
return FALSE;
}
return TRUE;
}
function smtp_putcmd($cmd, $arg = "")
{
if ($arg != "") {
if($cmd=="") $cmd = $arg;
else $cmd = $cmd." ".$arg;
}
fputs($this->sock, $cmd."\r\n");
$this->smtp_debug("> ".$cmd."\n");
return $this->smtp_ok();
}
function smtp_error($string)
{
$this->log_write("Error: Error occurred while ".$string.".\n");
return FALSE;
}
function log_write($message)
{
$this->smtp_debug($message);
if ($this->log_file == "") {
return TRUE;
}
$message = date("M d H:i:s ").get_current_user()."[".getmypid()."]: ".$message;
if (!@file_exists($this->log_file) || !($fp = @fopen($this->log_file, "a"))) {
$this->smtp_debug("Warning: Cannot open log file \"".$this->log_file."\"\n");
return FALSE;
}
flock($fp, LOCK_EX);
fputs($fp, $message);
fclose($fp);
return TRUE;
}
function strip_comment($address)
{
$comment = "\\([^()]*\\)";
while (ereg($comment, $address)) {
$address = ereg_replace($comment, "", $address);
}
return $address;
}
function get_address($address)
{
$address = ereg_replace("([ \t\r\n])+", "", $address);
$address = ereg_replace("^.*<(.+)>.*$", "\\1", $address);
return $address;
}
function smtp_debug($message)
{
if ($this->debug) {
//echo $message."<br>";
}
}
function get_attach_type($image_tag) { //
$filedata = array();
$img_file_con=fopen($image_tag,"r");
unset($image_data);
while ($tem_buffer=AddSlashes(fread($img_file_con,filesize($image_tag))))
$image_data.=$tem_buffer;
fclose($img_file_con);
$filedata['context'] = $image_data;
$filedata['filename']= basename($image_tag);
$extension=substr($image_tag,strrpos($image_tag,"."),strlen($image_tag)-strrpos($image_tag,"."));
switch($extension){
case ".gif":
$filedata['type'] = "image/gif";
break;
case ".gz":
$filedata['type'] = "application/x-gzip";
break;
case ".htm":
$filedata['type'] = "text/html";
break;
case ".html":
$filedata['type'] = "text/html";
break;
case ".jpg":
$filedata['type'] = "image/jpeg";
break;
case ".tar":
$filedata['type'] = "application/x-tar";
break;
case ".txt":
$filedata['type'] = "text/plain";
break;
case ".zip":
$filedata['type'] = "application/zip";
break;
default:
$filedata['type'] = "application/octet-stream";
break;
}
return $filedata;
}
 }
?>
<?php
/*    $smtpserver = "smtp.bwvip.com:3000";            //SMTP服务器
    $smtpserverport =25;                            //SMTP服务器端口
    $smtpusermail = "liubin@bwvip.com";             //SMTP服务器的用户邮箱
    $smtpuser = "liubin@bwvip.com";                 //SMTP服务器的用户帐号
    $smtppass = "liubin";                           //SMTP服务器的用户密码
    $smtpemailto = "272761906@qq.com";              //发送给谁

    $mailsubject = "PHP100测试邮件系统";//邮件主题
    $mailbody = "<h1>你的用户名是张三，密码是11111 </h1>";//邮件内容
    $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
    $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
    $smtp->debug = true;//是否显示发送的调试信息
    $smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
*/









	$content = <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<STYLE type="text/css">  <!--@import url(scrollbar_7136.css); -->BODY { font-size: 14px; line-height: 1.5  } body,td,th,a {
  font-size: 9pt;
  color: #333;
  text-decoration:none;
}
</STYLE>
<STYLE type="text/css">
  a:hover{ text-decoration:underline;}
}
</STYLE>
<html><body><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<table width="700" border="0" cellpadding="0" cellspacing="0" style="background: #f0f0f0 ">
  <tr>
    <td height="237" valign="top">
      <a href="http://www.bwvip.com/" target="_parent"><img src="http://www.bwvip.com/qunyou/images/zhuce_flash.jpg" border="0"></a>
    </td>
    <td  height="237" valign="top"><a href="http://www.bwvip.com/member.php?mod=register" target="_blank"><img src="http://www.bwvip.com/qunyou/images/zhuce_flash_1.jpg" border="0"></a></td>
  </tr>
    <tr>
    <td colspan="2" align="center">
            <table width="575" cellpadding="0" cellspacing="0" border="0">
             <tr>
                <td width="180"><img src="http://www.bwvip.com/qunyou/images/zhuce_1.jpg" border="0" usemap="#Map"></td>
              <td>&nbsp;</td>
              <td  width="180"><img src="http://www.bwvip.com/qunyou/images/zhuce_2.jpg" border="0" usemap="#Map2"></td>
              <td>&nbsp;</td>
              <td  width="180"><img src="http://www.bwvip.com/qunyou/images/zhuce_3.jpg" border="0" usemap="#Map3"></td>
          </tr>
        </table>
    </td>
  </tr>
  <tr><td colspan="2" height="5">&nbsp;</td></tr>
     <tr>
    <td colspan="2" align="center">
            <table width="575" cellpadding="0" cellspacing="0" border="0" style="line-height:22px;" >
             <tr>
               <td width="155"   style="border:1px solid #ddd;padding:8px  6px 8px 14px; background:url(http://www.bwvip.com/qunyou/images/zhuce_5.jpg) repeat-x;" align="left">大正社区是一个真实的实名社区。我们的工作人员会和每个注册用户进行电话联系进行确认。完善您的个人资料将有助您通过实名认证。</td>
             <td>&nbsp;</td>
              <td width="160" style="border:1px solid #ddd; padding:0 6px 0 14px; background:url(http://www.bwvip.com/qunyou/images/zhuce_5.jpg) repeat-x;" align="left">大正社区已经帮您推荐了很多社区红人，您也可以点击寻找朋友按钮继续查找。</td>
              <td>&nbsp;</td>
               <td width="155" style="border:1px solid #ddd; padding:8px 6px 8px 14px; background:url(http://www.bwvip.com/qunyou/images/zhuce_5.jpg) repeat-x;" align="left">您是不是刚刚打了一场漂亮的高尔夫球，正渴望和球友炫耀一番？赶快把您的成绩卡传上来吧，和我们一起分享！</td>

           </tr>
      </table>
    </td>
  </tr>
   <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>

     <tr>
    <td colspan="2" align="center">
          <table width="575" cellpadding="0" cellspacing="0" border="0">
             <tr>
               <td width="356" style="border:1px solid #ddd; padding:10px; background:url(http://www.bwvip.com/qunyou/images/zhuce_5.jpg) repeat-x;" align="left">在大正社区您可以创建自己的高尔夫个性空间，上传您的打球成
绩卡和挥杆视频、寻找同城球友、加入属于自己的高尔夫圈子，
参与高尔夫大赛的有奖竞猜。</td>
              <td>&nbsp;</td>
              <td align="right"><img src="http://www.bwvip.com/qunyou/images/zhuce_4.jpg" border="0"></td>
           </tr>
           <tr><td colspan="3">&nbsp;</td></tr>
           <tr><td  style="border:1px solid #ddd; padding:10px;" colspan="3" align="left">各位大正网注册用户：大正网是一个实名制的高尔夫社区，网站客服会电话联系用户进行个人资料审核，特此公布大正网个人资料审核电话：13301159966 ，请各位注意，以保证个人资料信息安全。
</td></tr>
      </table>
    </td>
  </tr>

  <tr>
    <td colspan="2" >&nbsp;
    </td>
  </tr>
     <tr>
    <td colspan="2" bgcolor="#999999" align="center" style="height:100px; vertical-align:middle;">
            <table width="575" cellpadding="0" cellspacing="0" border="0" >
            <tr>
                   <td align="center" style="color:#fff;">
                              <A href="http://www.bwvip.com/about.php" style="color:#fff; text-decoration:none;" target="_blank">关于大正</A>|
                <A class=f9  href="http://www.bwvip.com/about.php" style="color:#fff; text-decoration:none;" target="_blank">商务合作</A>|
                <A class=f9  href="http://www.bwvip.com/zhaopin.php" style="color:#fff; text-decoration:none;" target="_blank">招贤纳士</A> |
                <A class=f9  href="http://www.bwvip.com/huoban.php"  style="color:#fff; text-decoration:none;" target="_blank">合作伙伴</A> |
                <A class=f9 href="http://www.bwvip.com/lianxi.php" style="color:#fff; text-decoration:none;" target="_blank">联系我们</A> |
                <A class=f9 href="http://www.bwvip.com/shengming.php" style="color:#fff; text-decoration:none;" target="_blank" >免责声明</A>|
                <A class=f9 href="http://www.bwvip.com/sitemap.php" style="color:#fff; text-decoration:none;"  target="_blank">网站地图</A>
                      </td>
          </tr>
                     <tr>
                          <td align="center" height="30" style="color:#fff;">大正网版权归北京大正承平文化传播有限公司</td>
           </tr>

                        <tr>
                          <td align="center" style="color:#fff;">
                             京ICP证110339号 &nbsp;&nbsp; 京公网安备110108008116号
                          </td>
          </tr>
      </table>

    </td>
  </tr>
</table>

<map name="Map" id="Map">
<area shape="rect" coords="25,186,153,224" href="http://www.bwvip.com/home.php?mod=spacecp&ac=profile&id=#maoprofile
" target="_blank" />
</map>
<map name="Map2" id="Map2"><area shape="rect" coords="19,185,154,223" href="http://www.bwvip.com/search.php?mod=weibo
"  target="_blank"/></map>
<map name="Map3" id="Map3"><area shape="rect" coords="20,184,155,224" href="http://www.bwvip.com/home.php?mod=spacecp&ac=myscore&op=scoreadd" target="_blank" /></map>
</body>
</html>

EOT;














    $smtpserver     = "smtp.bwvip.com";               //SMTP服务器
    $smtpserverport = 25;                             //SMTP服务器端口
    $smtpusermail   = "bwvip@bwvip.com";              //SMTP服务器的用户邮箱
    $smtpuser       = "bwvip@bwvip.com";              //SMTP服务器的用户帐号
    $smtppass       = "dzbwvip";                      //SMTP服务器的用户密码
    $smtpemailto    = $_G['member']['username'];      //发送给谁
    $mailsubject    = "欢迎你注册大正网 教学视频 商品促销 品牌俱乐部 更多精彩尽在大正网";            //邮件主题
    $mailbody       = $content;                        //邮件内容
    $mailtype       = "HTML";                          //邮件格式（HTML/TXT）,TXT为文本邮件
    $smtp           = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
    $smtp->debug    = false;                            //是否显示发送的调试信息
    $smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);



/*    $smtpserver     = "smtp.qq.com";                //SMTP服务器
    $smtpserverport = 25;                            //SMTP服务器端口
    $smtpusermail   = "272761906@qq.com";              //SMTP服务器的用户邮箱
    $smtpuser       = "272761906@qq.com";              //SMTP服务器的用户帐号
    $smtppass       = "4792156801liu";                        //SMTP服务器的用户密码
    $smtpemailto    = "272761906@qq.com";              //发送给谁
    $mailsubject    = "欢迎你注册大正网 教学视频 商品促销 品牌俱乐部 更多精彩尽在大正网";            //邮件主题
    $mailbody       = $content;                        //邮件内容
    $mailtype       = "HTML";                          //邮件格式（HTML/TXT）,TXT为文本邮件
    $smtp           = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
    $smtp->debug    = true;                            //是否显示发送的调试信息
    $smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);*/
?>