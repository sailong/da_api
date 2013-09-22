<?php

$ac = $_GET ['ac'];

echo $_SERVER['HTTP_HOST']."<br>"; 
//获取网页地址 
echo $_SERVER['PHP_SELF']."<br>"; 
//获取网址参数 
echo $_SERVER["QUERY_STRING"]."<br>"; 
//来源网页的详细地址 
echo $_SERVER['HTTP_REFERER']."<br>"; 
 
// 设定 cookie
if($ac=='create'){
 setcookie("cookie['three']", "第三个",time()+3600);
 setcookie("cookie['two']", "第二个",time()+3600);
 setcookie("cookie['one']", "第一个",time()+3600);
 if (isset($_COOKIE['cookie'])) 
{
 echo $_COOKIE['cookie']['\'two\'']."<br/>";
    foreach ($_COOKIE['cookie'] as $name => $value) 
    {
        echo "$name : $value <br />\n";
    }
}
}
// 读取cookie

if($ac=='read'){
if (isset($_COOKIE['cookie'])) 
{
 echo $_COOKIE['cookie']['\'two\'']."<br/>";
    foreach ($_COOKIE['cookie'] as $name => $value) 
    {
        echo "$name : $value <br />\n";
    }
}
}
if($ac=='del'){
if (isset($_COOKIE['cookie'])) 
{
 setcookie("cookie['three']", "",time()-3600);
 setcookie("cookie['two']", "",time()-3600);
 setcookie("cookie['one']", "",time()-3600);
}
}

?>
<a href="?ac=create">创建</a>
<br><br><br><br><br>
<a href="?ac=read">读取</a>
<br><br><br><br><br>
<a href="?ac=del">删除</a>