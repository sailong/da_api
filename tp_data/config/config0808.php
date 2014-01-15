<?php
return array(
    'APP_GROUP_LIST' => 'home,admin,field,wap', //分组
    'DEFAULT_GROUP' => 'home', //默认分组
    'DEFAULT_MODULE' => 'index', //默认控制器
    //'TAGLIB_PRE_LOAD' => 'pin', //自动加载标签
    'APP_AUTOLOAD_PATH' => '@.ORG', //自动加载项目类库
    'TMPL_ACTION_SUCCESS' => 'public:success',
    'TMPL_ACTION_ERROR' => 'public:error',
	'TMPL_EXCEPTION_FILE'=>'public:exception_error',		 // 定义公共错误模板，建议开发时启用
	//'ERROR_PAGE'=>'public:error_page',		 // 定义错误跳转页面URL地址，建议正式使用时启用

    //'DATA_CACHE_SUBDIR'=>true, //缓存文件夹
    'DATA_PATH_LEVEL'=>3, //缓存文件夹层级
    'LOAD_EXT_CONFIG' => 'url,db', //扩展配置
    
    'SHOW_PAGE_TRACE' => false,
	"TMPL_STRIP_SPACE" => false, //是否去除模板文件里面的html空格与换行

	'URL_MODEL' => 0, // URL访问模式,可选参数0、1、2、3,代表以下四种模式：// 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式，提供最好的用户体验和SEO支持
    
	'APP_DEBUG'			=> true,		// 是否开启调试模式 (开启AllInOne模式时该配置无效, 将自动置为false)
	'TMPL_CACHE_ON' => false,		//是否开启模板缓存(true 开启  false关闭)
	'NO_CACHE_RUNTIME'=>true,   //删除缓存  试一下
	'SHOW_ERROR_MSG' =>true,

	'URL_ROUTER_ON'   => true, //开启路由
	'URL_PATHINFO_DEPR'=>'/',
	'URL_HTML_SUFFIX'=>'html',
	'URL_ROUTE_RULES'=>array(
	/*
		'news/:year/:month/:day' => array('News/archive', 'status=1'),
		'news/:id'               => 'News/read',
		'news/read/:id'          => '/news/:1',
		*/
		'login'          => array('admin/public/login'),
		'company_login'          => array('company/public/login', 'company_id'),
		
		//'admin/public/login'   =>'admin/login',
	),

	'URL_ROUTE_REVERSE_RULES'=>array(
		'/admin\/public\/login/'          =>'login',
		'/company\/public\/login/'          =>'company_login',
	),


	'default_staff_name'			=>	'admin',			// 数据库类型
	'default_staff_password'		=>	md5('admin'),			// 数据库服务器地址
	'time_number_length'		=>	6,			// 数据库服务器地址



	//表单安全
	'TOKEN_ON'=>true,  // 是否开启令牌验证 默认关闭
	'TOKEN_NAME'=>'__hash__',    // 令牌验证的表单隐藏字段名称
	'TOKEN_TYPE'=>'md5',  //令牌哈希验证规则 默认为MD5
	'TOKEN_RESET'=>true,  //令牌验证出错后是否重置令牌 默认为true


	/* Cookie设置 */
    'COOKIE_EXPIRE' => 3600, // Coodie有效期
    'COOKIE_DOMAIN' => '', // Cookie有效域名
    'COOKIE_PATH' => '/', // Cookie路径
    'COOKIE_PREFIX' => 'kalatai_', // Cookie前缀 避免冲突	



);