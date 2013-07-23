<?php

/**
 * 文件名： jishigouapi.class.php
 * 版本号： 1.0.0
 * 作  者：　狐狸 <foxis@qq.com>
 * 修改时间： 2011年3月1日
 * 功能描述： api for JishiGou
 * 版权所有： Powered by JishiGou API 1.0.0 (a) 2005 - 2099 Cenwor Inc.
 * 公司网站： http://cenwor.com
 * 产品网站： http://jishigou.net
 */

/**
 * JishiGouAPI client
 * 
 * @package   
 * @author www.jishigou.com
 * @copyright foxis
 * @version 2011
 * @access public
 */
class JishiGouAPI
{
    
    var $Http = '';
    
    var $SiteUrl = '';
    
    var $AppKey = '';
    
    var $AppSecret = '';
    
    var $ApiUsername = '';
    
    var $ApiPassword = '';
    
    var $ApiOutput = '';
    
    var $ApiCharset = '';
    
    
    /**
     * JishiGouAPI::JishiGouAPI()
     * 
     * @param string $site_url like http://t.jishigou.net/api.php
     * @param string $app_key
     * @param string $app_secret
     * @param string $api_username
     * @param string $api_password
     * @param string $api_output json/xml/serialize_base64
     * @param string $api_charset utf-8
     * @return
     */
    function JishiGouAPI($site_url,$app_key,$app_secret,$api_username='',$api_password='',$api_output='json',$api_charset='utf-8')
    {
        $this->Http = new JishiGouAPI_Http_Client();
        
        $this->SetSiteUrl($site_url);
        
        $this->SetAppKey($app_key);
        
        $this->SetAppSecret($app_secret);
        
        $this->SetApiUsername($api_username);
        
        $this->SetApiPassword($api_password);
        
        $this->SetApiOutput($api_output);
        
        $this->SetApiCharset($api_charset);
    }

	/**
     * JishiGouAPI::GetUserInfo()
     * 
     * 获取用户信息
     *
	 * @param integer $uid 用户ID
     * @return
     */
    function GetUserInfo($uid = null)
    {
        $params = array(
            'mod' => 'user',
            'code' => 'show',
        );
        
        return $this->Request($params);
    }
    
    /**
     * JishiGouAPI::GetAllTopic()
     * 
     * 获取最新的微博
     * 
     * @param mixed $count 每页显示数
     * @param mixed $page 页码
     * @param mixed $id_min 最小微博ID
     * @param mixed $id_max 最大微博ID
     * @return
     */
    function GetAllTopic($count = null, $page = null, $id_min = null, $id_max = null)
    {
        $params = array(
            'mod' => 'public',
            'code' => 'topic',
            'count' => $count,
            'page' => $page,
            'id_min' => $id_min,
            'id_max' => $id_max,
        );
        
        return $this->Request($params);
    }
    

	/**
     * JishiGouAPI::AddTopic()
     * 
     * （发布/评论/转发）微博
     * 
     * @param mixed $text 微博内容
     * @param integer $totid （评论/转发）微博的ID
     * @param string $type 微博类型 first 原创 / reply 评论 / forward 转发  / both 评论且转发 
     * @return
     */
	function AddTopic($text,$totid=0,$type='first',$pic_url='', $pic=null, $voice='',$voice_timelong='')
    {
        $params = array(
            'mod' => 'topic',
            'code' => 'add',
            'content' => $text,
            'totid' => $totid,
            'type' => $type,
        
        	'pic_url' => $pic_url,
        	'pic' => $pic,
        	'voice' => $voice,
        	'voice_timelong' => $voice_timelong,
        );
        
        return $this->Request($params);
    }


	 /*
    function AddTopic($text,$totid=0,$type='first')
    {
        $params = array(
            'mod' => 'topic',
            'code' => 'add',
            'content' => $text,
            'totid' => $totid,
            'type' => $type,
        );
        
        return $this->Request($params);
    }
    */


    /**
     * JishiGouAPI::DeleteTopic()
     * 
     * 删除微博 
     * 
     * @param mixed $id 微博ID
     * @return
     */
    function DeleteTopic($id)
    {
        $params = array(
            'mod' => 'topic',
            'code' => 'delete',
            'id' => $id,
        );
        
        return $this->Request($params);
    }
    
    /**
     * JishiGouAPI::GetTopicById()
     * 
     * 获取一条微博信息
     * 
     * @param mixed $id 微博ID
     * @return
     */
    function GetTopicById($id)
    {
        $params = array(
            'mod' => 'topic',
            'code' => 'show',
            'id' => $id,
        );
        
        return $this->Request($params);
    }
    
    /**
     * JishiGouAPI::GetMyFans()
     * 
     * 获取我的粉丝（关注我的）
     * 
     * @return
     */
    function GetMyFans()
    {
        $params = array(
            'mod' => 'user',
            'code' => 'fans',
        );
        
        return $this->Request($params);
    }
    
    /**
     * JishiGouAPI::GetMyFollow()
     * 
     * 获取我关注的
     * 
     * @return
     */
    function GetMyFollow()
    {
        $params = array(
            'mod' => 'user',
            'code' => 'follow',
        );
        
        return $this->Request($params);
    }
    
    /**
     * JishiGouAPI::AddFollow()
     * 
     * 关注
     * 
     * @param mixed $uid 用户ID
     * @return
     */
    function AddFollow($uid)
    {
        $params = array(
            'mod' => 'user',
            'code' => 'follownew',
            'uid' => $uid,
        );
        
        return $this->Request($params);
    }
    
    /**
     * JishiGouAPI::DeleteFollow()
     * 
     * 取消关注
     * 
     * @param mixed $uid 用户ID
     * @return
     */
    function DeleteFollow($uid)
    {
        $params = array(
            'mod' => 'user',
            'code' => 'followdel',
            'uid' => $uid,
        );
        
        return $this->Request($params);
    }
    
    /**
     * JishiGouAPI::ShowFollow()
     * 
     * 粉丝关系
     * 
     * @param mixed $target_id 用户ID
     * @param string $source_id 用户ID
     * @return
     */
    function ShowFollow($target_id,$source_id='')
    {
        $params = array(
            'mod' => 'user',
            'code' => 'followshow',
            'source_id' => $source_id,
            'target_id' => $target_id,
        );
        
        return $this->Request($params);
    }
    
    /**
     * JishiGouAPI::GetMyTopic()
     * 
     * 获取我的微博
     * 
     * @param mixed $count
     * @param mixed $page
     * @param mixed $id_min
     * @param mixed $id_max
     * @return
     */
    function GetMyTopic($count = null, $page = null, $id_min = null, $id_max = null)
    {
        $params = array(
            'mod' => 'user',
            'code' => 'topic',
            'count' => $count,
            'page' => $page,
            'id_min' => $id_min,
            'id_max' => $id_max,
        );
        
        return $this->Request($params);
    } 
    
    /**
     * JishiGouAPI::GetMyFriendTopic()
     * 
     * 获取我好友的最新微博
     * 
     * @param mixed $count
     * @param mixed $page
     * @return
     */
    function GetMyFriendTopic($count = null, $page = null)
    {
        $params = array(
            'mod' => 'user',
            'code' => 'myfriendtopic',
            'count' => $count,
            'page' => $page,
        );
        
        return $this->Request($params);
    } 
    
    /**
     * JishiGouAPI::GetMyFavorite()
     * 
     * 获取我收藏的微博
     * 
     * @param mixed $count
     * @param mixed $page
     * @return
     */
    function GetMyFavorite($count = null, $page = null)
    {
        $params = array(
            'mod' => 'user',
            'code' => 'myfavorite',
            'count' => $count,
            'page' => $page,
        );
        
        return $this->Request($params);
    }
    
    /**
     * JishiGouAPI::GetFavoriteMy()
     * 
     * 获取收藏我的微博
     * 
     * @param mixed $count
     * @param mixed $page
     * @return
     */
    function GetFavoriteMy($count = null, $page = null)
    {
        $params = array(
            'mod' => 'user',
            'code' => 'favoritemy',
            'count' => $count,
            'page' => $page,
        );
        
        return $this->Request($params);
    }
    
    /**
     * JishiGouAPI::GetMyComment()
     * 
     * 获取我评论的微博
     * 
     * @param mixed $count
     * @param mixed $page
     * @return
     */
    function GetMyComment($count = null, $page = null)
    {
        $params = array(
            'mod' => 'user',
            'code' => 'mycomment',
            'count' => $count,
            'page' => $page,
        );
        
        return $this->Request($params);
    }
    
    /**
     * JishiGouAPI::GetCommentMy()
     * 
     * 获取评论我的微博
     * 
     * @param mixed $count
     * @param mixed $page
     * @return
     */
    function GetCommentMy($count = null, $page = null)
    {
        $params = array(
            'mod' => 'user',
            'code' => 'commentmy',
            'count' => $count,
            'page' => $page,
        );
        
        return $this->Request($params);
    } 
    
    /**
     * JishiGouAPI::GetAtMy()
     * 
     * 获取AT我的微博
     * 
     * @param mixed $count
     * @param mixed $page
     * @return
     */
    function GetAtMy($count = null, $page = null)
    {
        $params = array(
            'mod' => 'user',
            'code' => 'atmy',
            'count' => $count,
            'page' => $page,
        );
        
        return $this->Request($params);
    }   
    
    /**
     * JishiGouAPI::GetMyPm()
     * 
     * 获取我的私信列表
     * 
     * @param mixed $count
     * @param mixed $page
     * @return
     */
    function GetMyPm($count = null, $page = null)
    {
        $params = array(
            'mod' => 'user',
            'code' => 'pm',
            'count' => $count,
            'page' => $page,
        );
        
        return $this->Request($params);
    }  
    
    /**
     * JishiGouAPI::GetMySentPm()
     * 
     * 获取我发送的私信列表
     * 
     * @param mixed $count
     * @param mixed $page
     * @return
     */
    function GetMySentPm($count = null, $page = null)
    {
        $params = array(
            'mod' => 'user',
            'code' => 'pmsent',
            'count' => $count,
            'page' => $page,
        );
        
        return $this->Request($params);
    }
    
    /**
     * JishiGouAPI::SendPm()
     * 
     * 发送私信
     * 
     * @param mixed $to_user 私信接收者（用户昵称）
     * @param mixed $text 私信内容
     * @return
     */
    function SendPm($to_user,$text)
    {
        $params = array(
            'mod' => 'user',
            'code' => 'pmnew',
            'to_user' => $to_user,
            'text' => $text,
        );
        
        return $this->Request($params);
    }
    
    /**
     * JishiGouAPI::Request()
     * 
     * @param mixed $posts
     * @return
     */
    function Request($posts = array())
    {
        settype($posts,'array');
        $posts['__timestamp__'] = time();
        
        $posts['__API__'] = array(
            'charset' => $this->ApiCharset,
            'output' => $this->ApiOutput,
            'app_key' => $this->AppKey,
            'app_secret' => $this->AppSecret,
            'username' => $this->ApiUsername,
            'password' => $this->ApiPassword,
        );

		if(is_array($posts['pic'])) {
        	$this->Http->addPostFile('pic', $posts['pic']['name'], $posts['pic']['data']);        	
        	unset($posts['pic']);
        }

        foreach($posts as $k=>$v)
        {
            $this->Http->addPostField($k,$v);
        }

        $result = $this->Http->Post($this->SiteUrl,false);
        $result = $this->_decode_output($result);        

		
		if($result['error']) {
			echo('<pre>调试信息来自：'.__FILE__.'('.__LINE__.')<br>');
			echo '输入值：'; print_r($posts);
			echo '返回值：'; print_r($result);
			echo '</pre>';
			exit;
		}


        return $result;
    }
    
    /**
     * JishiGouAPI::SetSiteUrl()
     * 
     * @param mixed $site_url
     * @return
     */
    function SetSiteUrl($site_url)
    {
        $this->SiteUrl = $site_url;
    }
    
    /**
     * JishiGouAPI::SetAppKey()
     * 
     * @param mixed $app_key
     * @return
     */
    function SetAppKey($app_key)
    {
        $this->AppKey = $app_key;
    }
    
    /**
     * JishiGouAPI::SetAppSecret()
     * 
     * @param mixed $app_secret
     * @return
     */
    function SetAppSecret($app_secret)
    {
        $this->AppSecret = $app_secret;
    }
    
    /**
     * JishiGouAPI::SetApiUsername()
     * 
     * @param mixed $api_username
     * @return
     */
    function SetApiUsername($api_username)
    {
        $this->ApiUsername = $api_username;
    }
    
    /**
     * JishiGouAPI::SetApiPassword()
     * 
     * @param mixed $api_password
     * @return
     */
    function SetApiPassword($api_password)
    {
        $this->ApiPassword = $api_password;
    }
    
    /**
     * JishiGouAPI::SetApiOutput()
     * 
     * @param mixed $api_output
     * @return
     */
    function SetApiOutput($api_output)
    {
        $this->ApiOutput = $api_output;
    }
    
    /**
     * JishiGouAPI::SetApiCharset()
     * 
     * @param mixed $api_charset
     * @return
     */
    function SetApiCharset($api_charset)
    {
        $this->ApiCharset = $api_charset;
    }
    
    /**
     * JishiGouAPI::_decode_output()
     * 
     * 解码从服务端获取的数据
     * 
     * @param mixed $result
     * @return
     */
    function _decode_output($result)
    {
        switch(strtolower($this->ApiOutput))
        {
            case 'json':
                {
                    //json_decode在php5.2.0到5.2.3问题兼容性修正(float问题)
            		if( version_compare(PHP_VERSION, '5.2.0', '>=') && version_compare(PHP_VERSION, '5.2.3', '<=') )
                    {
            			$result = json_decode(preg_replace('#(?<=[,\{\[])\s*("\w+"):(\d{6,})(?=\s*[,\]\}])#si', '${1}:"${2}"', $result), true);
            		}
                    else
                    {
            			$result = json_decode ( $result, true );
            		}
                }
                break;
                
            case 'xml':
                {
                    $xml_parser = new JishiGouAPI_XML(false);
                	$result = $xml_parser->parse($result);
                	$xml_parser->destruct();
                }
                break;
                
            case 'serialize_base64':
                {
                    $result = unserialize(base64_decode($result));
                }
                break;

            default :
                {
                    ;
                }
                break;                           
        }
        
        return $result;
    }
    
}



/**
 * Full featured Http Client class in pure PHP (4.1+)
 *
 * API list:
 * Object  $http = new Http_Client([bool $verbose = false]);
 * integer $http->getStatus();
 * string  $http->getTitle();
 * string  $http->getUrl();
 * void    $http->setHeader(string $key[, string $value = null]);
 * mixed   $http->getHeader([string $key = null]);
 * void    $http->setCookie(string $key, string $value);
 * mixed   $http->getCookie([string $key = null[, string $host = null]]);
 * bool    $http->saveCookie(string $filepath);
 * bool    $http->loadCookie(string $filepath);
 * void    $http->addPostField(string $key, mixed $value);
 * void    $http->addPostFile(string $key, string $filename[, string $content = null]);
 * string  $http->Get(string $url[, bool $redirect = true]);
 * mixed   $http->Head(string $url[, bool $redirect = true]);
 * string  $http->Post(string $url[, bool redirect = true]);
 * bool    $http->Download(string $url[, string $filepath = null[, bool overwrite = false]);
 *
 * @author hightman <hightman@twomice.net>
 * @link http://www.hightman.cn/
 * @copyright Copyright &copy; 2008-2010 Twomice Studio
 * @version $Id: http_client.class.php,v 1.22 2010/10/16 16:42:47 hightman Exp $
 */

/**
 * Defines the package name.
 */
define ('HC_PACKAGENAME',	'HttpClient');
/**
 * Defines the package version.
 */
define ('HC_VERSION',		'2.0-beta');
/**
 * This constant defines how many times should be tried on I/O failure (timeout and error).
 * Defaults to 3, it should be greater than 0.
 */
define ('HC_MAX_RETRIES',	3);

/**
 * Http_Client is a full featured client class for the HTTP protocol.
 *
 * It currently implements some HTTP/1.x protocols, including request method HEAD, GET, POST,
 * and automatic handling of authorization, redirection request, and cookies.
 *
 * Features include:
 * 1) Pure PHP code, none of extensions is required, PHP version just from 4.1.0;
 * 2) Ability to set/get any HTTP request headers, such as user-agent, referal page, etc;
 * 3) Includes full featured cookie support, automatic sent cookie header if required on next request;
 * 4) Handle redirected requests automatically (such as HTTP/301, HTTP/302);
 * 5) Support real Keep-Alive connections, used for multiple requests;
 * 6) Can resume getting a partially-downloaded file use special download() method;
 * 7) Support multiple files upload via post method, support array named request variable (arr[]=...)
 * 8) SSL support
 *
 * The whole library code is open and free, you can use it for any purposes.
 *
 * @author hightman <hightman@twomice.net>
 * @version 2.0-beta $
 */
/**
 * JishiGouAPI_Http_Client
 * 
 * @package   
 * @author www.jishigou.com
 * @copyright foxis
 * @version 2011
 * @access public
 */
class JishiGouAPI_Http_Client
{
	/**
	 * local private variables
	 * @access private
	 */
	var $headers, $status, $title, $cookies, $socks, $url, $filepath, $verbose;
	var $post_files, $post_fields;
	
	/** 
	 * Constructor (PHP4-style).
	 * @param boolean wheather to display verbose execute messages
	 */
	/**
	 * JishiGouAPI_Http_Client::JishiGouAPI_Http_Client()
	 * 
	 * @param bool $verbose
	 * @return
	 */
	function JishiGouAPI_Http_Client($verbose = false)
	{
		$this->__construct($verbose);
	}
	
	/** 
	 * Constructor (PHP5).
	 * @param boolean wheather to display verbose execute messages
	 */
	/**
	 * JishiGouAPI_Http_Client::__construct()
	 * 
	 * @param bool $verbose
	 * @return
	 */
	function __construct($verbose = false)
	{
		$this->verbose = $verbose;
		$this->cookies = array();
		$this->socks = array();	
		$this->_reset();
	}

	/** 
	 * Destructor (PHP5 only).
	 * Close all opened socket connections.
	 */
	/**
	 * JishiGouAPI_Http_Client::__destruct()
	 * 
	 * @return
	 */
	function __destruct()
	{
		foreach ($this->socks as $host => $sock) { @fclose($sock); }
		$this->socks = array();
	}

	/** 
	 * Get HTTP respond status code of the last HTTP request.
	 * @return integer http respond status code
	 */
	/**
	 * JishiGouAPI_Http_Client::getStatus()
	 * 
	 * @return
	 */
	function getStatus()
	{
		return $this->status;
	}

	/** 
	 * Get HTTP respond short title of the last HTTP request.
	 * @return string http respond short title
	 */
	/**
	 * JishiGouAPI_Http_Client::getTitle()
	 * 
	 * @return
	 */
	function getTitle()
	{
		return $this->title;
	}
	
	/** 
	 * Get the real URL of the last HTTP request.
	 * @return string real URL of the last http request after redirecting
	 */	
	/**
	 * JishiGouAPI_Http_Client::getUrl()
	 * 
	 * @return
	 */
	function getUrl()
	{
		return $this->url;
	}

	/** 
	 * Get the downloaded file path after calling Download() method.
	 * @return string filepath saved on local disk
	 */	
	/**
	 * JishiGouAPI_Http_Client::getFilepath()
	 * 
	 * @return
	 */
	function getFilepath()
	{
		return $this->filepath;
	}

	/** 
	 * Set a HTTP header for the next request.
	 * @param string the name of the request header
	 * @param string the value of the request header
	 * If the value is NULL, the header will be dropped.
	 * Note: special key 'x-server-addr' will force to use instead of gethostbyname(host)
	 */
	/**
	 * JishiGouAPI_Http_Client::setHeader()
	 * 
	 * @param mixed $key
	 * @param mixed $value
	 * @return
	 */
	function setHeader($key, $value = null)
	{
		$this->_reset();
		$key = strtolower($key);
		if (is_null($value)) unset($this->headers[$key]);
		else $this->headers[$key] = strval($value);
	}
	
	/** 
	 * Get one or more HTTP headers of the last request.
	 * @param string the name of the header to be fetched.
	 * If is NULL, return the all headers of the last request.
	 * @return mixed fetched header value or headersas key-value array.
	 * If the header dose not exists, NULL is returned.
	 */	
	/**
	 * JishiGouAPI_Http_Client::getHeader()
	 * 
	 * @param mixed $key
	 * @return
	 */
	function getHeader($key = null)
	{
		if (is_null($key)) return $this->headers;
		$key = strtolower($key);
		if (!isset($this->headers[$key])) return null;
		return $this->headers[$key];
	}

	/** 
	 * Add a HTTP cookie sent for the next request.
	 * @param string the name of the cookie to be added
	 * @param string the value of the cookie to be added
	 */
	/**
	 * JishiGouAPI_Http_Client::setCookie()
	 * 
	 * @param mixed $key
	 * @param mixed $value
	 * @return
	 */
	function setCookie($key, $value)
	{
		$this->_reset();
		if (!isset($this->headers['cookie'])) $this->headers['cookie'] = array();
		$this->headers['cookie'][$key] = $value;
	}

	/** 
	 * Get a HTTP cookie item by name
	 * @param string the name of the cookie to be fetched
	 * If the name is NULL, all matched cookies are returned as key-value array.  
	 * @param string host of all saved cookies (include expired)
	 * If the host is NULL, fetch the cookie from last request.
	 * @return mixed fetched cookie item or cookies as key-value array.
	 * Every cookie item is a assoc array, keys include: value, expires, path, host
	 * If the cookie dose not exists, NULL is returned.	
	 */
	/**
	 * JishiGouAPI_Http_Client::getCookie()
	 * 
	 * @param mixed $key
	 * @param mixed $host
	 * @return
	 */
	function getCookie($key = null, $host = null)
	{
		// fetch from last request
		if (!is_null($key)) $key = strtolower($key);
		if (is_null($host))
		{
			if (!isset($this->headers['cookie'])) return null;
			if (is_null($key)) return $this->headers['cookie'];
			if (!isset($this->headers['cookie'][$key])) return null;
			return $this->headers['cookie'][$key];
		}
		// fetch from all saved cookies.
		$host = strtolower($host);
		while (true)
		{
			if (isset($this->cookies[$host]))
			{
				if (is_null($key)) return $this->cookies[$host];
				if (isset($this->cookies[$host][$key])) return $this->cookies[$host][$key];
			}
			// search for next sub-domain
			$pos = strpos($host, '.', 1);
			if ($pos === false) break;
			$host = substr($host, $pos);
		}
		return null;
	}

	/** 
	 * Save all cookies to a file.
	 * @param string the file path that cookies will be saved to.
	 * @return boolean save result, return true on success and false on faiulre.
	 * Note: all cookies are serialized before saving.
	 */
	/**
	 * JishiGouAPI_Http_Client::saveCookie()
	 * 
	 * @param mixed $fpath
	 * @return
	 */
	function saveCookie($fpath)
	{
		if (false === ($fd = @fopen($fpath, 'w')))
			return false;
		$data = serialize($this->cookies);
		fwrite($fd, $data);
		fclose($fd);
		return true;
	}

	/** 
	 * Load cookies from a file
	 * @param string the file path that cookies has been saved to.
	 * The cookie file should be created by saveCookie() method.
	 */
	/**
	 * JishiGouAPI_Http_Client::loadCookie()
	 * 
	 * @param mixed $fpath
	 * @return
	 */
	function loadCookie($fpath)
	{
		if (file_exists($fpath) && ($cookies = @unserialzie(file_get_contents($fpath))))
			$this->cookies = $cookies;
	}

	/** 
	 * Add a post field for the next request
	 * @param string the name of the field.
	 * @param mixed the value of the field, can be array or string.
	 * If the value is an array, converted to arr[key][key2] fields automatically.
	 */
	/**
	 * JishiGouAPI_Http_Client::addPostField()
	 * 
	 * @param mixed $key
	 * @param mixed $value
	 * @return
	 */
	function addPostField($key, $value)
	{
		$this->_reset();
		if (!is_array($value))
			$this->post_fields[$key] = strval($value);
		else
		{
			$value = $this->_format_array_field($value);
			foreach ($value as $tmpk => $tmpv)
			{
				$tmpk = $key . '[' . $tmpk . ']';
				$this->post_fields[$tmpk] = strval($tmpv);
			}
		}
	}

	/**
	 * Add a multipart post file for the next request
	 * @param string the name of the field
	 * @param string the filename or filepath to be uploaded
	 * @param string content the file content
	 * If the content is null and fname is a valid filepath, 
	 * content will be set to the file content.
	 */
	/**
	 * JishiGouAPI_Http_Client::addPostFile()
	 * 
	 * @param mixed $key
	 * @param mixed $fname
	 * @param string $content
	 * @return
	 */
	function addPostFile($key, $fname, $content = '')
	{
		$this->_reset();
		if ($content === '' && is_file($fname)) $content = @file_get_contents($fname);
		$this->post_files[$key] = array(basename($fname), $content);
	}

	/**
	 * Do a http request via get method
	 * @param string the absolute URL
	 * @param boolean handle redirected requests automatically or not
	 * @return string respond body data or false on failure before server respond.
	 */
	/**
	 * JishiGouAPI_Http_Client::Get()
	 * 
	 * @param mixed $url
	 * @param bool $redir
	 * @return
	 */
	function Get($url, $redir = true)
	{
		return $this->_do_url($url, 'get', null, $redir);
	}

	/**
	 * Do a http request via head method
	 * @param string the absolute URL
	 * @param boolean handle redirected requests automatically or not	 
	 * @return mixed all respond HTTP header or false on failure before server respond.
	 */
	/**
	 * JishiGouAPI_Http_Client::Head()
	 * 
	 * @param mixed $url
	 * @param bool $redir
	 * @return
	 */
	function Head($url, $redir = false)
	{
		if ($this->_do_url($url, 'head', null, $redir) !== false)
			return $this->getHeader(null);
		return false;
	}

	/**
	 * Do a http request via post method
	 * @param string the absolute URL
	 * @param boolean handle redirected requests automatically or not
	 * @return string respond body data or false on failure before server respond.
	 * Note: post request variable should be set by ::addPostField() and ::addPostFile()
	 */
	/**
	 * JishiGouAPI_Http_Client::Post()
	 * 
	 * @param mixed $url
	 * @param bool $redir
	 * @return
	 */
	function Post($url, $redir = true)
	{
		$data = '';
		if (count($this->post_files) > 0)
		{
			$boundary = md5($url . microtime());
			foreach ($this->post_fields as $tmpk => $tmpv)
			{
				$data .= "--{$boundary}\r\nContent-Disposition: form-data; name=\"{$tmpk}\"\r\n\r\n{$tmpv}\r\n";
			}
			foreach ($this->post_files as $tmpk => $tmpv)
			{
				$type = 'application/octet-stream';
				$ext = strtolower(substr($tmpv[0], strrpos($tmpv[0],'.')+1));
				if (isset($GLOBALS['___HC_MIMES___'][$ext])) $type = $GLOBALS['___HC_MIMES___'][$ext];
				$data .= "--{$boundary}\r\nContent-Disposition: form-data; name=\"{$tmpk}\"; filename=\"{$tmpv[0]}\"\r\nContent-Type: $type\r\nContent-Transfer-Encoding: binary\r\n\r\n";
				$data .= $tmpv[1] . "\r\n";
			}
			$data .= "--{$boundary}--\r\n";
			$this->setHeader('content-type', 'multipart/form-data; boundary=' . $boundary);
		}
		else
		{
			foreach ($this->post_fields as $tmpk => $tmpv)
			{
				$data .= '&' . rawurlencode($tmpk) . '=' . rawurlencode($tmpv);
			}
			$data = substr($data, 1);
			$this->setHeader('content-type', 'application/x-www-form-urlencoded');
		}
		$this->setHeader('content-length', strlen($data));
		return $this->_do_url($url, 'post', $data, $redir);
	}

	/**
	 * Download a file to local via get method with range support
	 * @param string the absolute URL
	 * @param string local filepath to saved, default is the same filename on current working directory.
	 * @param boolean weather to overwrite the exists file 
	 * when filepath exists and not a valid partially-downloaded file.
	 * @return boolean true on success and false on failure.
	 * Note: this method can be used to resume getting a partially-downloaded file.
	 */
	/**
	 * JishiGouAPI_Http_Client::Download()
	 * 
	 * @param mixed $url
	 * @param mixed $filepath
	 * @param bool $overwrite
	 * @return
	 */
	function Download($url, $filepath = null, $overwrite = false)
	{
		// get filepath & head
		if ($filepath === true)
		{
			$overwrite = true; 
			$filepath = null;
		}
		if (is_null($filepath) || empty($filepath)) $filepath = '.';
		// get normal headers first
		$savehead = $this->getHeader(null);
		if (!$this->Head($url, true))
		{
			if ($this->verbose) echo "[ERROR] failed to get headers for downloading file.\n";
			return false;
		}
		else if ($this->getStatus() != 200)
		{
			if ($this->verbose) echo "[ERROR] can not get a valid 200 HTTP respond status.\n";
			return false;
		}
		// get filename & filesize
		$url = $this->getUrl();
		if ($this->verbose) echo "[INFO] real download url is: $url\n";
		if (is_dir($filepath))
		{
			if (substr($filepath, -1, 1) != DIRECTORY_SEPARATOR) $filepath .= DIRECTORY_SEPARATOR;		
			if (($disposition = $this->getHeader('content-disposition')) 
				&& preg_match('/filename=[\'"]?([^;\'" ]+)/', $disposition, $match))
			{
				$filename = $match[1];
				if ($this->verbose) echo "[INFO] fetch filename from disposition header: $filename\n";
			}
			else
			{
				$tmpstr = ($pos = strpos($url, '?')) ? substr($url, 0, $pos) : $url;
				$pos = strrpos($tmpstr, '/');
				$filename = substr($tmpstr, $pos + 1);
				if ($filename == '') $filename = 'index.html';
				if ($this->verbose) echo "[INFO] fetch filename from URL: $filename\n";
			}
			while (true)
			{
				$filepath .= $filename;
				if (!is_dir($filepath)) break;
				$filepath .= DIRECTORY_SEPARATOR . $filename;
			}
		}
		// check filepath
		if (!file_exists($filepath) || !($fsize = @filesize($filepath)))
		{
			$savefd = @fopen($filepath, 'w');
			if ($this->verbose) echo "[INFO] save file directly to: $filepath\n";
		}
		else
		{
			$length = $this->getHeader('content-length');
			$accept = $this->getHeader('accept-ranges');
			if ($length && $fsize < $length && stristr($accept, 'bytes'))
			{
				// range request used
				$this->setHeader('range', 'bytes=' . $fsize . '-');
				$savefd = @fopen($filepath, 'a');
				if ($this->verbose) echo "[INFO] range download used, range: {$fsize}-\n";
			}
			else if ($overwrite)
			{
				$savefd = @fopen($filepath, 'w');
				if ($this->verbose) echo "[INFO] overwrite the exists file: $filepath\n";
			}
			else
			{
				// auto append filename '.1, .2, ...'
				for ($i = 1; @file_exists($filepath . '.' . $i); $i++);
				$filepath .= '.' . $i;
				$savefd = @fopen($filepath, 'w');
				if ($this->verbose) echo "[INFO] auto skip exists file, last save to: $filepath\n";
			}
		}
		// check the savefd
		if (!$savefd)
		{
			if ($this->verbose) echo "[ERROR] can not open the file to save data: $filename\n";
			return false;
		}
		// do real download via get method
		foreach ($savehead as $hk => $hv) $this->setHeader($hk, $hv);
		if ($this->_do_url($url, 'get', null, false, $savefd) !== false)
		{
			$this->filepath = $filepath;
			fclose($savefd);
			if ($this->verbose) echo "[INFO] downloaded file saved in: $filepath\n";
			return true;
		}
		else
		{
			if ($this->verbose) echo "[ERROR] can not download the URL: $url\n";
			return false;
		}
	}
	
	// -------------------------------------------------
	// private functions
	// -------------------------------------------------
	// read data from socket
	/**
	 * JishiGouAPI_Http_Client::_sock_read()
	 * 
	 * @param mixed $fd
	 * @param integer $maxlen
	 * @param bool $wfd
	 * @return
	 */
	function _sock_read($fd, $maxlen = 4096, $wfd = false)
	{
		$rlen = 0;
		$data = '';
		$ntry = HC_MAX_RETRIES;
		while (!feof($fd))
		{
			$part = fread($fd, $maxlen - $rlen);
			if ($part === false || $part === '') $ntry--;
			else $data .= $part;
			$rlen = strlen($data);
			if ($rlen == $maxlen || $ntry == 0) break;
		}
		if ($ntry == 0 || feof($fd)) @fclose($fd);
		if (is_resource($wfd))
		{
			fwrite($wfd, $data);
			$data = '';
		}
		return $data;
	}

	// write data to socket
	/**
	 * JishiGouAPI_Http_Client::_sock_write()
	 * 
	 * @param mixed $fd
	 * @param mixed $buf
	 * @return
	 */
	function _sock_write($fd, $buf)
	{
		$wlen = 0;
		$tlen = strlen($buf);
		$ntry = HC_MAX_RETRIES;
		while ($wlen < $tlen)
		{
			$nlen = fwrite($fd, substr($buf, $wlen), $tlen - $wlen);
			if (!$nlen) { if (--$ntry == 0) return false; }
			else $wlen += $nlen;
		}
		return true;
	}

	// reset some request data (status)
	/**
	 * JishiGouAPI_Http_Client::_reset()
	 * 
	 * @return
	 */
	function _reset()
	{
		if ($this->status !== 0) 
		{
			$this->status = 0;
			$this->url = $this->title = $this->filepath = null;
			$this->headers = $this->post_files = $this->post_fields = array();
		}
	}
	
	// check is a host belong a domain
	/**
	 * JishiGouAPI_Http_Client::_belong_domain()
	 * 
	 * @param mixed $host
	 * @param mixed $domain
	 * @return
	 */
	function _belong_domain($host, $domain)
	{
		if (!strcasecmp($domain, $host)) return true;
		if (substr($domain, 0, 1) == '.')
		{
			if (!strcasecmp($host, substr($domain, 1))) return true;
			$hlen = strlen($host);
			$dlen = strlen($domain);
			if ($hlen > $dlen && !strcasecmp(substr($host, $hlen - $dlen), $domain))
				return true;
		}
		return false;
	}

	// format array field (convert N-DIM(n>=2) array => 2-DIM array)
	/**
	 * JishiGouAPI_Http_Client::_format_array_field()
	 * 
	 * @param mixed $value
	 * @param mixed $pk
	 * @return
	 */
	function _format_array_field($value, $pk = NULL)
	{
		$ret = array();
		foreach ($value as $k => $v)
		{
			$k = (is_null($pk) ? $k : $pk . $k);
			if (is_array($v)) $ret += $this->_format_array_field($v, $k . '][');
			else $ret[$k] = $v;
		}
		return $ret;
	}

	// do a url method
	/**
	 * JishiGouAPI_Http_Client::_do_url()
	 * 
	 * @param mixed $url
	 * @param mixed $method
	 * @param mixed $data
	 * @param bool $redir
	 * @param bool $savefd
	 * @return
	 */
	function _do_url($url, $method, $data = null, $redir = true, $savefd = false)
	{
		// check the url
		if (strncasecmp($url, 'http://', 7) && strncasecmp($url, 'https:/'.'/', 8) && isset($_SERVER['HTTP_HOST']))
		{
			$base = 'http://' . $_SERVER['HTTP_HOST'];
			if (substr($url, 0, 1) != '/')
				$url = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/')+1) . $url;			
			$url = $base . $url;
		}

		// parse the url
		$url = str_replace('&amp;', '&', $url);
		$pa = @parse_url($url);
		if ($pa['scheme'] && $pa['scheme'] != 'http' && $pa['scheme'] != 'https')
		{
			trigger_error("Invalid scheme `{$pa['scheme']}`", E_USER_WARNING);
			return false;
		}
		if (!isset($pa['host']))
		{
			trigger_error("Invalid request url, host required", E_USER_WARNING);
			return false;
		}
		if (!isset($pa['port'])) $pa['port'] = ($pa['scheme'] == 'https' ? 443 : 80);
		if (!isset($pa['path']))
		{
			$pa['path'] = '/';
			$url .= '/';
		}
		$host = strtolower($pa['host']);
		if (isset($this->headers['x-server-addr'])) $addr = $this->headers['x-server-addr'];
		else $addr = gethostbyname($pa['host']);
		$port = intval($pa['port']);
		$skey = $addr . ':' . $port;
		if ($pa['scheme'] && $pa['scheme'] == 'https') $host_conn = 'ssl://' . $addr;
		else $host_conn = 'tcp://' . $addr;

		// make the query buffer
		$method = strtoupper($method);
		$buf = $method . ' ' . $pa['path'];
		if (isset($pa['query'])) $buf .= '?' . $pa['query'];
		$buf .= " HTTP/1.1\r\nHost: {$host}\r\n";
		
		// basic auth support
		if (isset($pa['user']) && isset($pa['pass']))
			$this->headers['authorization'] = 'Basic ' . base64_encode($pa['user'] . ':' . $pa['pass']);

		// set default HTTP/headers
		$savehead = $this->headers;
		$this->_reset();
		if (!isset($this->headers['user-agent'])) 
		{
			$buf .= "User-Agent: Mozilla/5.0 (Compatible; " . HC_PACKAGENAME . "/" . HC_VERSION . "; +Hightman) ";
			$buf .= "php-" . php_sapi_name() . "/" . phpversion() . " ";
			$buf .= php_uname("s") . "/" . php_uname("r") . "\r\n";
		}
		if (!isset($this->headers['accept'])) $buf .= "Accept: */*\r\n";
		if (!isset($this->headers['accept-language'])) $buf .= "Accept-Language: zh-cn,zh\r\n";
		if (!isset($this->headers['connection'])) $buf .= "Connection: Keep-Alive\r\n";
		if (isset($this->headers['accept-encoding'])) unset($this->headers['accept-encoding']);
		if (isset($this->headers['host'])) unset($this->headers['host']);

		// saved cookies (session data)
		$now = time();
		$ck_str = '';
		foreach ($this->cookies as $ck_host => $ck_list)
		{
			if (!$this->_belong_domain($host, $ck_host)) continue;
			foreach ($ck_list as $ck => $cv)
			{
				if (isset($this->headers['cookie'][$ck])) continue;
				if ($cv['expires'] > 0 && $cv['expires'] < $now) continue;
				if (strncmp($pa['path'], $cv['path'], strlen($cv['path']))) continue;
				$ck_str .= '; ' . $cv['rawdata'];
			}
		}
		foreach ($this->headers as $k => $v)
		{
			if ($k != 'cookie')
				$buf .= ucfirst($k) . ": " . $v . "\r\n";
			else
			{
				foreach ($v as $ck => $cv) $ck_str .= '; ' . rawurlencode($ck) . '=' . rawurlencode($cv);
			}
		}
		// TODO: check cookie length?
		if ($ck_str != '') $buf .= 'Cookie:' . substr($ck_str, 1) . "\r\n";
		$buf .= "\r\n";
		if ($method == 'POST') $buf .= $data . "\r\n";

		// force reset status for next query even if failed this time.
		$this->status = -1;
		$this->url = $url;

		// show the header buf
		if ($this->verbose)
		{
			echo "[INFO] request url: $url\r\n";
			echo "[SEND] request buffer\r\n----\r\n";
			echo $buf;
			echo "----\r\n";
		}

		// create the sock & send the header
		$ntry = HC_MAX_RETRIES;
		$sock = isset($this->socks[$skey]) ? $this->socks[$skey] : false;
		do
		{
			if (is_resource($sock) && $this->_sock_write($sock, $buf)) break;
			if ($sock) @fclose($sock);
			$sock = fsockopen($host_conn, $port, $errno, $error, 3);
			if ($sock)
			{
				stream_set_blocking($sock, 1);
				stream_set_timeout($sock, 10);
			}			
		}
		while (--$ntry);
		if (!$sock)
		{
			if (isset($this->socks[$skey])) unset($this->socks[$skey]);
			trigger_error("Cann't connect to `$host:$port'", E_USER_WARNING);
			return false;
		}
		$this->socks[$skey] = $sock;
		if ($this->verbose)
		{
			echo "[SEND] using socket = {$sock}\r\n";
			echo "[RECV] http respond header\r\n----\r\n";
		}

		// read the respond header
		$with_range = isset($this->headers['range']);
		$this->headers = array();
		while ($line = fgets($sock, 2048))
		{
			if ($this->verbose) echo $line;
			$line = trim($line);
			if ($line === '') break;
			if (!strncasecmp('HTTP/', $line, 5))
			{
				$line = trim(substr($line, strpos($line, ' ')));
				list($this->status, $this->title) = explode(' ', $line, 2);
				$this->status = intval($this->status);
			}
			else if (!strncasecmp('Set-Cookie: ', $line, 12))
			{
				// ignore the cookie options: Httponly
				$ck_key = '';
				$ck_val = array('value' => '', 'expires' => 0, 'path' => '/', 'domain' => $host);
				$tmpa = explode(';', substr($line, 12));
				foreach ($tmpa as $tmp)
				{
					$tmp = trim($tmp);
					if (empty($tmp)) continue;
					list($tmpk, $tmpv) = explode('=', $tmp, 2);
					$tmpk2 = strtolower($tmpk);
					if ($ck_key == '')
					{
						$ck_key = rawurldecode($tmpk);
						$ck_val['value'] = rawurldecode($tmpv);
						$ck_val['rawdata'] = $tmpk . '=' . $tmpv;
					}
					else if ($tmpk2 == 'expires')
					{
						$ck_val['expires'] = strtotime($tmpv);
						if ($ck_val['expires'] < $now)
						{
							$ck_val['value'] = '';
							break;
						}
					}
					else if (isset($ck_val[$tmpk2]) && $tmpv != '')
					{
						$ck_val[$tmpk2] = $tmpv;
						// drop invalid-domain cookies?
						if ($tmpk2 == 'domain' && !$this->_belong_domain($host, $tmpv)) $ck_key = '';
					}
				}

				// delete cookie?
				if ($ck_key == '') continue;
				if ($ck_val['value'] == '') unset($this->cookies[$ck_val['domain']][$ck_key]);
				else $this->cookies[$ck_val['domain']][$ck_key] = $ck_val;

				// headers.
				$this->headers['cookie'][$ck_key] = $ck_val;
			}
			else 
			{
				list($k, $v) = explode(':', $line, 2);
				$k = strtolower(trim($k));
				$v = trim($v);
				$this->headers[$k] = $v;
			}
		}
		if ($this->verbose) echo "----\r\n";
		
		// check savefd
		if ($savefd && $with_range)
		{
			if ($this->status == 200)
			{
				ftruncate($savefd, 0);
				fseek($savefd, 0, SEEK_SET);
			}
			else if ($this->status != 206) $savefd = false;
		}

		// get body
		$connection = $this->getHeader('connection');
		$encoding = $this->getHeader('transfer-encoding');
		$length = $this->getHeader('content-length');
		if ($method == 'HEAD') 
		{
			// nothing to do
			$body = '';
		}
		else if ($encoding && !strcasecmp($encoding, 'chunked'))
		{
			$body = '';
			while (is_resource($sock))
			{
				if (!($line = fgets($sock, 1024))) break;
				if ($this->verbose) echo "[RECV] Chunk Line: " . $line;
				if ($p1 = strpos($line, ';')) $line = substr($line, 0, $pos);
				$chunk_len = hexdec(trim($line));
				if ($chunk_len <= 0) break;	// end the chunk
				$body .= $this->_sock_read($sock, $chunk_len, $savefd);
				fread($sock, 2);			// eat the CRLF
			}

			// trailer header
			if ($this->verbose) echo "[RECV] chunk tailer\r\n----\r\n";
			while ($line = fgets($sock, 2048))
			{
				if ($this->verbose) echo $line;
				$line = trim($line);
				if ($line === '') break;
				list($k, $v) = explode(':', $line, 2);
				$k = strtolower(trim($k));
				$v = trim($v);
				$this->headers[$k] = $v;
			}		
			if ($this->verbose) echo "----\r\n";
		}
		else if ($length)
		{
			$body = '';
			$length = intval($length);
			while ($length > 0 && is_resource($sock))
			{
				$body .= $this->_sock_read($sock, ($length > 8192 ? 8192 : $length), $savefd);
				$length -= 8192;
			}
		}
		else
		{
			$body = '';
			while (is_resource($sock) && !feof($sock)) $body .= $this->_sock_read($sock, 8192, $savefd);
			$connection = 'close';
		}		

		// check close connection?
		if ($connection && !strcasecmp($connection, 'close'))
		{
			@fclose($sock);
			unset($this->socks[$skey]);
		}
			
		// check redirect
		if ($redir && $this->status != 200 && ($location = $this->getHeader('location')))
		{
			if (!is_int($redir)) $redir = HC_MAX_RETRIES;
			if (!preg_match('/^http[s]?:\/\/'.'/i', $location))
			{
				$url2 = $pa['scheme'] . ':/'.'/' . $pa['host'];
				if (strpos($url, ':', 8)) $url2 .= ':' . $pa['port'];
				if (substr($location, 0, 1) == '/') $url2 .= $location;
				else $url2 .= substr($pa['path'], 0, strrpos($pa['path'], '/') + 1) . $location;
				$location = $url2;
			}
			if (!isset($savehead['referer'])) $savehead['referer'] = $url;
			foreach ($savehead as $hk => $hv) $this->setHeader($hk, $hv);
			return $this->_do_url($location, ($method == 'HEAD' ? 'head' : 'get'), null, $redir - 1);
		}

		// return the body buf
		return $body;
	}
}

// mimetypes used on http_client
$GLOBALS['___HC_MIMES___'] = array(
	'gif' => 'image/gif',
	'png' => 'image/png',
	'bmp' => 'image/bmp',
	'jpeg' => 'image/jpeg',
	'pjpg' => 'image/pjpg',
	'jpg' => 'image/jpeg',
	'tif' => 'image/tiff',
	'htm' => 'text/html',
	'css' => 'text/css',
	'html' => 'text/html',
	'txt' => 'text/plain',
	'gz' => 'application/x-gzip',
	'tgz' => 'application/x-gzip',
	'tar' => 'application/x-tar',
	'zip' => 'application/zip',
	'hqx' => 'application/mac-binhex40',
	'doc' => 'application/msword',
	'pdf' => 'application/pdf',
	'ps' => 'application/postcript',
	'rtf' => 'application/rtf',
	'dvi' => 'application/x-dvi',
	'latex' => 'application/x-latex',
	'swf' => 'application/x-shockwave-flash',
	'tex' => 'application/x-tex',
	'mid' => 'audio/midi',
	'au' => 'audio/basic',
	'mp3' => 'audio/mpeg',
	'ram' => 'audio/x-pn-realaudio',
	'ra' => 'audio/x-realaudio',
	'rm' => 'audio/x-pn-realaudio',
	'wav' => 'audio/x-wav',
	'wma' => 'audio/x-ms-media',
	'wmv' => 'video/x-ms-media',
	'mpg' => 'video/mpeg',
	'mpga' => 'video/mpeg',
	'wrl' => 'model/vrml',
	'mov' => 'video/quicktime',
	'avi' => 'video/x-msvideo'
);


/**
 * JishiGouAPI_XML
 * 
 * @package   
 * @author www.jishigou.com
 * @copyright foxis
 * @version 2011
 * @access public
 */
class JishiGouAPI_XML {

	var $parser;
	var $document;
	var $stack;
	var $data;
	var $last_opened_tag;
	var $isnormal;
	var $attrs = array();
	var $failed = FALSE;

	/**
	 * JishiGouAPI_XML::__construct()
	 * 
	 * @param mixed $isnormal
	 * @return
	 */
	function __construct($isnormal) {
		$this->JishiGouAPI_XML($isnormal);
	}

	/**
	 * JishiGouAPI_XML::JishiGouAPI_XML()
	 * 
	 * @param mixed $isnormal
	 * @return
	 */
	function JishiGouAPI_XML($isnormal) {
		$this->isnormal = $isnormal;
		$this->parser = xml_parser_create('UTF-8');
		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);
		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, 'open','close');
		xml_set_character_data_handler($this->parser, 'data');
	}

	/**
	 * JishiGouAPI_XML::destruct()
	 * 
	 * @return
	 */
	function destruct() {
		xml_parser_free($this->parser);
	}

	/**
	 * JishiGouAPI_XML::parse()
	 * 
	 * @param mixed $data
	 * @return
	 */
	function parse(&$data) {
		$this->document = array();
		$this->stack	= array();
		return xml_parse($this->parser, $data, true) && !$this->failed ? $this->document : '';
	}

	/**
	 * JishiGouAPI_XML::open()
	 * 
	 * @param mixed $parser
	 * @param mixed $tag
	 * @param mixed $attributes
	 * @return
	 */
	function open(&$parser, $tag, $attributes) {
		$this->data = '';
		$this->failed = FALSE;
		if(!$this->isnormal) {
			if(isset($attributes['id']) && !is_string($this->document[$attributes['id']])) {
				$this->document  = &$this->document[$attributes['id']];
			} else {
				$this->failed = TRUE;
			}
		} else {
			if(!isset($this->document[$tag]) || !is_string($this->document[$tag])) {
				$this->document  = &$this->document[$tag];
			} else {
				$this->failed = TRUE;
			}
		}
		$this->stack[] = &$this->document;
		$this->last_opened_tag = $tag;
		$this->attrs = $attributes;
	}

	/**
	 * JishiGouAPI_XML::data()
	 * 
	 * @param mixed $parser
	 * @param mixed $data
	 * @return
	 */
	function data(&$parser, $data) {
		if($this->last_opened_tag != NULL) {
			$this->data .= $data;
		}
	}

	/**
	 * JishiGouAPI_XML::close()
	 * 
	 * @param mixed $parser
	 * @param mixed $tag
	 * @return
	 */
	function close(&$parser, $tag) {
		if($this->last_opened_tag == $tag) {
			$this->document = $this->data;
			$this->last_opened_tag = NULL;
		}
		array_pop($this->stack);
		if($this->stack) {
			$this->document = &$this->stack[count($this->stack)-1];
		}
	}

}

/**
 * Converts to and from JSON format.
 *
 * JSON (JavaScript Object Notation) is a lightweight data-interchange
 * format. It is easy for humans to read and write. It is easy for machines
 * to parse and generate. It is based on a subset of the JavaScript
 * Programming Language, Standard ECMA-262 3rd Edition - December 1999.
 * This feature can also be found in  Python. JSON is a text format that is
 * completely language independent but uses conventions that are familiar
 * to programmers of the C-family of languages, including C, C++, C#, Java,
 * JavaScript, Perl, TCL, and many others. These properties make JSON an
 * ideal data-interchange language.
 *
 * This package provides a simple encoder and decoder for JSON notation. It
 * is intended for use with client-side Javascript applications that make
 * use of HTTPRequest to perform server communication functions - data can
 * be encoded into JSON notation for use in a client-side javascript, or
 * decoded from incoming Javascript requests. JSON format is native to
 * Javascript, and can be directly eval()'ed with no further parsing
 * overhead
 *
 * All strings should be in ASCII or UTF-8 format!
 *
 * LICENSE: Redistribution and use in source and binary forms, with or
 * without modification, are permitted provided that the following
 * conditions are met: Redistributions of source code must retain the
 * above copyright notice, this list of conditions and the following
 * disclaimer. Redistributions in binary form must reproduce the above
 * copyright notice, this list of conditions and the following disclaimer
 * in the documentation and/or other materials provided with the
 * distribution.
 *
 * THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN
 * NO EVENT SHALL CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS
 * OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR
 * TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE
 * USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
 * DAMAGE.
 *
 * @category
 * @package     Services_JSON
 * @author      Michal Migurski <mike-json@teczno.com>
 * @author      Matt Knapp <mdknapp[at]gmail[dot]com>
 * @author      Brett Stimmerman <brettstimmerman[at]gmail[dot]com>
 * @copyright   2005 Michal Migurski
 * @version     CVS ID: JSON.php,v 1.31 2006/06/28 05:54:17 migurski Exp $ / SVN: $Id: JishiGouAPI_servicesJSON.han.php 15 2011-02-11 foxis $
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @link        http://pear.php.net/pepr/pepr-proposal-show.php?id=198
 */

/**
 * Marker constant for JishiGouAPI_servicesJSON::decode(), used to flag stack state
 */
define('SERVICES_JSON_SLICE',   1);

/**
 * Marker constant for JishiGouAPI_servicesJSON::decode(), used to flag stack state
 */
define('SERVICES_JSON_IN_STR',  2);

/**
 * Marker constant for JishiGouAPI_servicesJSON::decode(), used to flag stack state
 */
define('SERVICES_JSON_IN_ARR',  3);

/**
 * Marker constant for JishiGouAPI_servicesJSON::decode(), used to flag stack state
 */
define('SERVICES_JSON_IN_OBJ',  4);

/**
 * Marker constant for JishiGouAPI_servicesJSON::decode(), used to flag stack state
 */
define('SERVICES_JSON_IN_CMT', 5);

/**
 * Behavior switch for JishiGouAPI_servicesJSON::decode()
 */
define('SERVICES_JSON_LOOSE_TYPE', 16);

/**
 * Behavior switch for JishiGouAPI_servicesJSON::decode()
 */
define('SERVICES_JSON_SUPPRESS_ERRORS', 32);

/**
 * Converts to and from JSON format.
 *
 * Brief example of use:
 *
 * <code>
 * // create a new instance of Services_JSON
 * $json = new Services_JSON();
 *
 * // convert a complexe value to JSON notation, and send it to the browser
 * $value = array('foo', 'bar', array(1, 2, 'baz'), array(3, array(4)));
 * $output = $json->encode($value);
 *
 * print($output);
 * // prints: ["foo","bar",[1,2,"baz"],[3,[4]]]
 *
 * // accept incoming POST data, assumed to be in JSON notation
 * $input = file_get_contents('php://input', 1000000);
 * $value = $json->decode($input);
 * </code>
 */
class JishiGouAPI_servicesJSON
{
   /**
    * constructs a new JSON instance
    *
    * @param    int     $use    object behavior flags; combine with boolean-OR
    *
    *                           possible values:
    *                           - SERVICES_JSON_LOOSE_TYPE:  loose typing.
    *                                   "{...}" syntax creates associative arrays
    *                                   instead of objects in decode().
    *                           - SERVICES_JSON_SUPPRESS_ERRORS:  error suppression.
    *                                   Values which can't be encoded (e.g. resources)
    *                                   appear as NULL instead of throwing errors.
    *                                   By default, a deeply-nested resource will
    *                                   bubble up with an error, so all return values
    *                                   from encode() should be checked with isError()
    */
    function JishiGouAPI_servicesJSON($use = 0)
    {
        $this->use = $use;
    }

   /**
    * convert a string from one UTF-16 char to one UTF-8 char
    *
    * Normally should be handled by mb_convert_encoding, but
    * provides a slower PHP-only method for installations
    * that lack the multibye string extension.
    *
    * @param    string  $utf16  UTF-16 character
    * @return   string  UTF-8 character
    * @access   private
    */
    function utf162utf8($utf16)
    {
        // oh please oh please oh please oh please oh please
        if(function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($utf16, 'UTF-8', 'UTF-16');
        }

        $bytes = (ord($utf16{0}) << 8) | ord($utf16{1});

        switch(true) {
            case ((0x7F & $bytes) == $bytes):
                // this case should never be reached, because we are in ASCII range
                // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                return chr(0x7F & $bytes);

            case (0x07FF & $bytes) == $bytes:
                // return a 2-byte UTF-8 character
                // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                return chr(0xC0 | (($bytes >> 6) & 0x1F))
                     . chr(0x80 | ($bytes & 0x3F));

            case (0xFFFF & $bytes) == $bytes:
                // return a 3-byte UTF-8 character
                // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                return chr(0xE0 | (($bytes >> 12) & 0x0F))
                     . chr(0x80 | (($bytes >> 6) & 0x3F))
                     . chr(0x80 | ($bytes & 0x3F));
        }

        // ignoring UTF-32 for now, sorry
        return '';
    }

   /**
    * convert a string from one UTF-8 char to one UTF-16 char
    *
    * Normally should be handled by mb_convert_encoding, but
    * provides a slower PHP-only method for installations
    * that lack the multibye string extension.
    *
    * @param    string  $utf8   UTF-8 character
    * @return   string  UTF-16 character
    * @access   private
    */
    function utf82utf16($utf8)
    {
        // oh please oh please oh please oh please oh please
        if(function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($utf8, 'UTF-16', 'UTF-8');
        }

        switch(strlen($utf8)) {
            case 1:
                // this case should never be reached, because we are in ASCII range
                // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                return $utf8;

            case 2:
                // return a UTF-16 character from a 2-byte UTF-8 char
                // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                return chr(0x07 & (ord($utf8{0}) >> 2))
                     . chr((0xC0 & (ord($utf8{0}) << 6))
                         | (0x3F & ord($utf8{1})));

            case 3:
                // return a UTF-16 character from a 3-byte UTF-8 char
                // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                return chr((0xF0 & (ord($utf8{0}) << 4))
                         | (0x0F & (ord($utf8{1}) >> 2)))
                     . chr((0xC0 & (ord($utf8{1}) << 6))
                         | (0x7F & ord($utf8{2})));
        }

        // ignoring UTF-32 for now, sorry
        return '';
    }

   /**
    * encodes an arbitrary variable into JSON format
    *
    * @param    mixed   $var    any number, boolean, string, array, or object to be encoded.
    *                           see argument 1 to Services_JSON() above for array-parsing behavior.
    *                           if var is a strng, note that encode() always expects it
    *                           to be in ASCII or UTF-8 format!
    *
    * @return   mixed   JSON string representation of input var or an error if a problem occurs
    * @access   public
    */
    function encode($var)
    {
        switch (gettype($var)) {
            case 'boolean':
                return $var ? 'true' : 'false';

            case 'NULL':
                return 'null';

            case 'integer':
                return (int) $var;

            case 'double':
            case 'float':
                return (float) $var;

            case 'string':
                // STRINGS ARE EXPECTED TO BE IN ASCII OR UTF-8 FORMAT
                $ascii = '';
                $strlen_var = strlen($var);

               /*
                * Iterate over every character in the string,
                * escaping with a slash or encoding to UTF-8 where necessary
                */
                for ($c = 0; $c < $strlen_var; ++$c) {

                    $ord_var_c = ord($var{$c});

                    switch (true) {
                        case $ord_var_c == 0x08:
                            $ascii .= '\b';
                            break;
                        case $ord_var_c == 0x09:
                            $ascii .= '\t';
                            break;
                        case $ord_var_c == 0x0A:
                            $ascii .= '\n';
                            break;
                        case $ord_var_c == 0x0C:
                            $ascii .= '\f';
                            break;
                        case $ord_var_c == 0x0D:
                            $ascii .= '\r';
                            break;

                        case $ord_var_c == 0x22:
                        case $ord_var_c == 0x2F:
                        case $ord_var_c == 0x5C:
                            // double quote, slash, slosh
                            $ascii .= '\\'.$var{$c};
                            break;

                        case (($ord_var_c >= 0x20) && ($ord_var_c <= 0x7F)):
                            // characters U-00000000 - U-0000007F (same as ASCII)
                            $ascii .= $var{$c};
                            break;

                        case (($ord_var_c & 0xE0) == 0xC0):
                            // characters U-00000080 - U-000007FF, mask 110XXXXX
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                            $char = pack('C*', $ord_var_c, ord($var{$c + 1}));
                            $c += 1;
                            $utf16 = $this->utf82utf16($char);
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;

                        case (($ord_var_c & 0xF0) == 0xE0):
                            // characters U-00000800 - U-0000FFFF, mask 1110XXXX
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                            $char = pack('C*', $ord_var_c,
                                         ord($var{$c + 1}),
                                         ord($var{$c + 2}));
                            $c += 2;
                            $utf16 = $this->utf82utf16($char);
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;

                        case (($ord_var_c & 0xF8) == 0xF0):
                            // characters U-00010000 - U-001FFFFF, mask 11110XXX
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                            $char = pack('C*', $ord_var_c,
                                         ord($var{$c + 1}),
                                         ord($var{$c + 2}),
                                         ord($var{$c + 3}));
                            $c += 3;
                            $utf16 = $this->utf82utf16($char);
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;

                        case (($ord_var_c & 0xFC) == 0xF8):
                            // characters U-00200000 - U-03FFFFFF, mask 111110XX
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                            $char = pack('C*', $ord_var_c,
                                         ord($var{$c + 1}),
                                         ord($var{$c + 2}),
                                         ord($var{$c + 3}),
                                         ord($var{$c + 4}));
                            $c += 4;
                            $utf16 = $this->utf82utf16($char);
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;

                        case (($ord_var_c & 0xFE) == 0xFC):
                            // characters U-04000000 - U-7FFFFFFF, mask 1111110X
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                            $char = pack('C*', $ord_var_c,
                                         ord($var{$c + 1}),
                                         ord($var{$c + 2}),
                                         ord($var{$c + 3}),
                                         ord($var{$c + 4}),
                                         ord($var{$c + 5}));
                            $c += 5;
                            $utf16 = $this->utf82utf16($char);
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;
                    }
                }

                return '"'.$ascii.'"';

            case 'array':
               /*
                * As per JSON spec if any array key is not an integer
                * we must treat the the whole array as an object. We
                * also try to catch a sparsely populated associative
                * array with numeric keys here because some JS engines
                * will create an array with empty indexes up to
                * max_index which can cause memory issues and because
                * the keys, which may be relevant, will be remapped
                * otherwise.
                *
                * As per the ECMA and JSON specification an object may
                * have any string as a property. Unfortunately due to
                * a hole in the ECMA specification if the key is a
                * ECMA reserved word or starts with a digit the
                * parameter is only accessible using ECMAScript's
                * bracket notation.
                */

                // treat as a JSON object
                if (is_array($var) && count($var) && (array_keys($var) !== range(0, sizeof($var) - 1))) {
                    $properties = array_map(array($this, 'name_value'),
                                            array_keys($var),
                                            array_values($var));

                    foreach($properties as $property) {
                        if(JishiGouAPI_servicesJSON::isError($property)) {
                            return $property;
                        }
                    }

                    return '{' . join(',', $properties) . '}';
                }

                // treat it like a regular array
                $elements = array_map(array($this, 'encode'), $var);

                foreach($elements as $element) {
                    if(JishiGouAPI_servicesJSON::isError($element)) {
                        return $element;
                    }
                }

                return '[' . join(',', $elements) . ']';

            case 'object':
                $vars = get_object_vars($var);

                $properties = array_map(array($this, 'name_value'),
                                        array_keys($vars),
                                        array_values($vars));

                foreach($properties as $property) {
                    if(JishiGouAPI_servicesJSON::isError($property)) {
                        return $property;
                    }
                }

                return '{' . join(',', $properties) . '}';

            default:
                return ($this->use & SERVICES_JSON_SUPPRESS_ERRORS)
                    ? 'null'
                    : new JishiGouAPI_Services_JSON_Error(gettype($var)." can not be encoded as JSON string");
        }
    }

   /**
    * array-walking function for use in generating JSON-formatted name-value pairs
    *
    * @param    string  $name   name of key to use
    * @param    mixed   $value  reference to an array element to be encoded
    *
    * @return   string  JSON-formatted name-value pair, like '"name":value'
    * @access   private
    */
    function name_value($name, $value)
    {
        $encoded_value = $this->encode($value);

        if(JishiGouAPI_servicesJSON::isError($encoded_value)) {
            return $encoded_value;
        }

        return $this->encode(strval($name)) . ':' . $encoded_value;
    }

   /**
    * reduce a string by removing leading and trailing comments and whitespace
    *
    * @param    $str    string      string value to strip of comments and whitespace
    *
    * @return   string  string value stripped of comments and whitespace
    * @access   private
    */
    function reduce_string($str)
    {
        $str = preg_replace(array(

                // eliminate single line comments in '// ...' form
                '#^\s*'.'/'.'/(.+)$#m',

                // eliminate multi-line comments in '/* ... */' form, at start of string
                '#^\s*'.'/\*(.+)\*'.'/#Us',

                // eliminate multi-line comments in '/* ... */' form, at end of string
                '#/\*(.+)\*'.'/\s*$#Us'

            ), '', $str);

        // eliminate extraneous space
        return trim($str);
    }

   /**
    * decodes a JSON string into appropriate variable
    *
    * @param    string  $str    JSON-formatted string
    *
    * @return   mixed   number, boolean, string, array, or object
    *                   corresponding to given JSON input string.
    *                   See argument 1 to Services_JSON() above for object-output behavior.
    *                   Note that decode() always returns strings
    *                   in ASCII or UTF-8 format!
    * @access   public
    */
    function decode($str)
    {
        $str = $this->reduce_string($str);

        switch (strtolower($str)) {
            case 'true':
                return true;

            case 'false':
                return false;

            case 'null':
                return null;

            default:
                $m = array();

                if (is_numeric($str)) {
                    // Lookie-loo, it's a number

                    // This would work on its own, but I'm trying to be
                    // good about returning integers where appropriate:
                    // return (float)$str;

                    // Return float or int, as appropriate
                    return ((float)$str == (integer)$str)
                        ? (integer)$str
                        : (float)$str;

                } elseif (preg_match('/^("|\').*(\1)$/s', $str, $m) && $m[1] == $m[2]) {
                    // STRINGS RETURNED IN UTF-8 FORMAT
                    $delim = substr($str, 0, 1);
                    $chrs = substr($str, 1, -1);
                    $utf8 = '';
                    $strlen_chrs = strlen($chrs);

                    for ($c = 0; $c < $strlen_chrs; ++$c) {

                        $substr_chrs_c_2 = substr($chrs, $c, 2);
                        $ord_chrs_c = ord($chrs{$c});

                        switch (true) {
                            case $substr_chrs_c_2 == '\b':
                                $utf8 .= chr(0x08);
                                ++$c;
                                break;
                            case $substr_chrs_c_2 == '\t':
                                $utf8 .= chr(0x09);
                                ++$c;
                                break;
                            case $substr_chrs_c_2 == '\n':
                                $utf8 .= chr(0x0A);
                                ++$c;
                                break;
                            case $substr_chrs_c_2 == '\f':
                                $utf8 .= chr(0x0C);
                                ++$c;
                                break;
                            case $substr_chrs_c_2 == '\r':
                                $utf8 .= chr(0x0D);
                                ++$c;
                                break;

                            case $substr_chrs_c_2 == '\\"':
                            case $substr_chrs_c_2 == '\\\'':
                            case $substr_chrs_c_2 == '\\\\':
                            case $substr_chrs_c_2 == '\\/':
                                if (($delim == '"' && $substr_chrs_c_2 != '\\\'') ||
                                   ($delim == "'" && $substr_chrs_c_2 != '\\"')) {
                                    $utf8 .= $chrs{++$c};
                                }
                                break;

                            case preg_match('/\\\u[0-9A-F]{4}/i', substr($chrs, $c, 6)):
                                // single, escaped unicode character
                                $utf16 = chr(hexdec(substr($chrs, ($c + 2), 2)))
                                       . chr(hexdec(substr($chrs, ($c + 4), 2)));
                                $utf8 .= $this->utf162utf8($utf16);
                                $c += 5;
                                break;

                            case ($ord_chrs_c >= 0x20) && ($ord_chrs_c <= 0x7F):
                                $utf8 .= $chrs{$c};
                                break;

                            case ($ord_chrs_c & 0xE0) == 0xC0:
                                // characters U-00000080 - U-000007FF, mask 110XXXXX
                                //see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                                $utf8 .= substr($chrs, $c, 2);
                                ++$c;
                                break;

                            case ($ord_chrs_c & 0xF0) == 0xE0:
                                // characters U-00000800 - U-0000FFFF, mask 1110XXXX
                                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                                $utf8 .= substr($chrs, $c, 3);
                                $c += 2;
                                break;

                            case ($ord_chrs_c & 0xF8) == 0xF0:
                                // characters U-00010000 - U-001FFFFF, mask 11110XXX
                                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                                $utf8 .= substr($chrs, $c, 4);
                                $c += 3;
                                break;

                            case ($ord_chrs_c & 0xFC) == 0xF8:
                                // characters U-00200000 - U-03FFFFFF, mask 111110XX
                                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                                $utf8 .= substr($chrs, $c, 5);
                                $c += 4;
                                break;

                            case ($ord_chrs_c & 0xFE) == 0xFC:
                                // characters U-04000000 - U-7FFFFFFF, mask 1111110X
                                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                                $utf8 .= substr($chrs, $c, 6);
                                $c += 5;
                                break;

                        }

                    }

                    return $utf8;

                } elseif (preg_match('/^\[.*\]$/s', $str) || preg_match('/^\{.*\}$/s', $str)) {
                    // array, or object notation

                    if ($str{0} == '[') {
                        $stk = array(SERVICES_JSON_IN_ARR);
                        $arr = array();
                    } else {
                        if ($this->use & SERVICES_JSON_LOOSE_TYPE) {
                            $stk = array(SERVICES_JSON_IN_OBJ);
                            $obj = array();
                        } else {
                            $stk = array(SERVICES_JSON_IN_OBJ);
                            $obj = new stdClass();
                        }
                    }

                    array_push($stk, array('what'  => SERVICES_JSON_SLICE,
                                           'where' => 0,
                                           'delim' => false));

                    $chrs = substr($str, 1, -1);
                    $chrs = $this->reduce_string($chrs);

                    if ($chrs == '') {
                        if (reset($stk) == SERVICES_JSON_IN_ARR) {
                            return $arr;

                        } else {
                            return $obj;

                        }
                    }

                    //print("\nparsing {$chrs}\n");

                    $strlen_chrs = strlen($chrs);

                    for ($c = 0; $c <= $strlen_chrs; ++$c) {

                        $top = end($stk);
                        $substr_chrs_c_2 = substr($chrs, $c, 2);

                        if (($c == $strlen_chrs) || (($chrs{$c} == ',') && ($top['what'] == SERVICES_JSON_SLICE))) {
                            // found a comma that is not inside a string, array, etc.,
                            // OR we've reached the end of the character list
                            $slice = substr($chrs, $top['where'], ($c - $top['where']));
                            array_push($stk, array('what' => SERVICES_JSON_SLICE, 'where' => ($c + 1), 'delim' => false));
                            //print("Found split at {$c}: ".substr($chrs, $top['where'], (1 + $c - $top['where']))."\n");

                            if (reset($stk) == SERVICES_JSON_IN_ARR) {
                                // we are in an array, so just push an element onto the stack
                                array_push($arr, $this->decode($slice));

                            } elseif (reset($stk) == SERVICES_JSON_IN_OBJ) {
                                // we are in an object, so figure
                                // out the property name and set an
                                // element in an associative array,
                                // for now
                                $parts = array();
                                
                                if (preg_match('/^\s*(["\'].*[^\\\]["\'])\s*:\s*(\S.*),?$/Uis', $slice, $parts)) {
                                    // "name":value pair
                                    $key = $this->decode($parts[1]);
                                    $val = $this->decode($parts[2]);

                                    if ($this->use & SERVICES_JSON_LOOSE_TYPE) {
                                        $obj[$key] = $val;
                                    } else {
                                        $obj->$key = $val;
                                    }
                                } elseif (preg_match('/^\s*(\w+)\s*:\s*(\S.*),?$/Uis', $slice, $parts)) {
                                    // name:value pair, where name is unquoted
                                    $key = $parts[1];
                                    $val = $this->decode($parts[2]);

                                    if ($this->use & SERVICES_JSON_LOOSE_TYPE) {
                                        $obj[$key] = $val;
                                    } else {
                                        $obj->$key = $val;
                                    }
                                }

                            }

                        } elseif ((($chrs{$c} == '"') || ($chrs{$c} == "'")) && ($top['what'] != SERVICES_JSON_IN_STR)) {
                            // found a quote, and we are not inside a string
                            array_push($stk, array('what' => SERVICES_JSON_IN_STR, 'where' => $c, 'delim' => $chrs{$c}));
                            //print("Found start of string at {$c}\n");

                        } elseif (($chrs{$c} == $top['delim']) &&
                                 ($top['what'] == SERVICES_JSON_IN_STR) &&
                                 ((strlen(substr($chrs, 0, $c)) - strlen(rtrim(substr($chrs, 0, $c), '\\'))) % 2 != 1)) {
                            // found a quote, we're in a string, and it's not escaped
                            // we know that it's not escaped becase there is _not_ an
                            // odd number of backslashes at the end of the string so far
                            array_pop($stk);
                            //print("Found end of string at {$c}: ".substr($chrs, $top['where'], (1 + 1 + $c - $top['where']))."\n");

                        } elseif (($chrs{$c} == '[') &&
                                 in_array($top['what'], array(SERVICES_JSON_SLICE, SERVICES_JSON_IN_ARR, SERVICES_JSON_IN_OBJ))) {
                            // found a left-bracket, and we are in an array, object, or slice
                            array_push($stk, array('what' => SERVICES_JSON_IN_ARR, 'where' => $c, 'delim' => false));
                            //print("Found start of array at {$c}\n");

                        } elseif (($chrs{$c} == ']') && ($top['what'] == SERVICES_JSON_IN_ARR)) {
                            // found a right-bracket, and we're in an array
                            array_pop($stk);
                            //print("Found end of array at {$c}: ".substr($chrs, $top['where'], (1 + $c - $top['where']))."\n");

                        } elseif (($chrs{$c} == '{') &&
                                 in_array($top['what'], array(SERVICES_JSON_SLICE, SERVICES_JSON_IN_ARR, SERVICES_JSON_IN_OBJ))) {
                            // found a left-brace, and we are in an array, object, or slice
                            array_push($stk, array('what' => SERVICES_JSON_IN_OBJ, 'where' => $c, 'delim' => false));
                            //print("Found start of object at {$c}\n");

                        } elseif (($chrs{$c} == '}') && ($top['what'] == SERVICES_JSON_IN_OBJ)) {
                            // found a right-brace, and we're in an object
                            array_pop($stk);
                            //print("Found end of object at {$c}: ".substr($chrs, $top['where'], (1 + $c - $top['where']))."\n");

                        } elseif (($substr_chrs_c_2 == '/'.'*') &&
                                 in_array($top['what'], array(SERVICES_JSON_SLICE, SERVICES_JSON_IN_ARR, SERVICES_JSON_IN_OBJ))) {
                            // found a comment start, and we are in an array, object, or slice
                            array_push($stk, array('what' => SERVICES_JSON_IN_CMT, 'where' => $c, 'delim' => false));
                            $c++;
                            //print("Found start of comment at {$c}\n");

                        } elseif (($substr_chrs_c_2 == '*'.'/') && ($top['what'] == SERVICES_JSON_IN_CMT)) {
                            // found a comment end, and we're in one now
                            array_pop($stk);
                            $c++;

                            for ($i = $top['where']; $i <= $c; ++$i)
                                $chrs = substr_replace($chrs, ' ', $i, 1);

                            //print("Found end of comment at {$c}: ".substr($chrs, $top['where'], (1 + $c - $top['where']))."\n");

                        }

                    }

                    if (reset($stk) == SERVICES_JSON_IN_ARR) {
                        return $arr;

                    } elseif (reset($stk) == SERVICES_JSON_IN_OBJ) {
                        return $obj;

                    }

                }
        }
    }

    /**
     * @todo Ultimately, this should just call PEAR::isError()
     */
    function isError($data, $code = null)
    {
        if (class_exists('pear')) {
            return PEAR::isError($data, $code);
        } elseif (is_object($data) && (get_class($data) == 'JishiGouAPI_Services_JSON_Error' ||
                                 is_subclass_of($data, 'JishiGouAPI_Services_JSON_Error'))) {
            return true;
        }

        return false;
    }
}

if (class_exists('PEAR_Error')) {

    class JishiGouAPI_Services_JSON_Error extends PEAR_Error
    {
        function JishiGouAPI_Services_JSON_Error($message = 'unknown error', $code = null,
                                     $mode = null, $options = null, $userinfo = null)
        {
            parent::PEAR_Error($message, $code, $mode, $options, $userinfo);
        }
    }

} else {

    /**
     * @todo Ultimately, this class shall be descended from PEAR_Error
     */
    class JishiGouAPI_Services_JSON_Error
    {
        function JishiGouAPI_Services_JSON_Error($message = 'unknown error', $code = null,
                                     $mode = null, $options = null, $userinfo = null)
        {

        }
    }

}

if(!function_exists('json_encode'))
{
    function json_encode($value)
    {
        $json = new JishiGouAPI_servicesJSON();
        return $json->encode($value);
    }
}
if(!function_exists('json_decode'))
{
    function json_decode($json_value,$bool = false)
    {
        $json = new JishiGouAPI_servicesJSON();
        return $json->decode($json_value,$bool);
    }
}

?>
