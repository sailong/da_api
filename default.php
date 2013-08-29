<?
//header("Content-type: text/html; charset=utf-8"); 

/* 应用名称*/
define('APP_NAME', 'app');
/*项目根目录*/
define('WEB_ROOT_PATH',dirname(__FILE__));
/* 应用目录*/
define('APP_PATH', './dazheng/');
/* 数据目录*/
define('PIN_DATA_PATH', './tp_data/');
/* 扩展目录*/
define('EXTEND_PATH', APP_PATH . 'Extend/');
/* 配置文件目录*/
define('CONF_PATH', PIN_DATA_PATH . 'config/');
/* 数据目录*/
define('RUNTIME_PATH', './_runtime/');
/* HTML静态文件目录*/
//define('HTML_PATH', PIN_DATA_PATH . 'html/');
/* DEBUG开关*/
define('APP_DEBUG', true);
require("./core/ThinkPHP/ThinkPHP.php");

?>