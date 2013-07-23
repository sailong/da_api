<?php

/*******************************************************************

 * [JishiGou] (C)2005 - 2099 Cenwor Inc.

 *

 * This is NOT a freeware, use is subject to license terms

 *

 * @Package JishiGou $

 *

 * @Filename qqoauth.php $

 *

 * @Author http://www.jishigou.net $

 *

 * @Date 2011-09-28 19:16:47 655855001 1958379005 12455 $

 *******************************************************************/







require_once(ROOT_PATH . 'include/qqwb/oauth.php');



 
class QQOAuth {     
    public $http_code; 
     
    public $url; 
     
    public $host = "https:/'.'/open.t.qq.com/cgi-bin/"; 
     
    public $timeout = 30; 
     
    public $connecttimeout = 30;  
     
    public $ssl_verifypeer = FALSE; 
     
    public $format = 'json'; 
     
    public $decode_json = TRUE; 
     
    public $http_info; 
     
    public $useragent = 'JishiGou OAuth v0.2'; 
     
        



     
     
    function accessTokenURL()  { return 'https:/'.'/open.t.qq.com/cgi-bin/access_token'; } 
     
    function authenticateURL() { return 'https:/'.'/open.t.qq.com/cgi-bin/authenticate'; } 
     
    function authorizeURL()    { return 'https:/'.'/open.t.qq.com/cgi-bin/authorize'; } 
     
    function requestTokenURL() { return 'https:/'.'/open.t.qq.com/cgi-bin/request_token'; } 


     
     
    function lastStatusCode() { return $this->http_status; } 
     
    function lastAPICall() { return $this->last_api_call; } 

     
    function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) { 
        $this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1(); 
        $this->consumer = new OAuthConsumer($consumer_key, $consumer_secret); 
        if (!empty($oauth_token) && !empty($oauth_token_secret)) { 
            $this->token = new OAuthConsumer($oauth_token, $oauth_token_secret); 
        } else { 
            $this->token = NULL; 
        } 
    } 


     
    function getRequestToken($oauth_callback = NULL) { 
        $parameters = array(); 
        if (!empty($oauth_callback)) { 
            $parameters['oauth_callback'] = $oauth_callback; 
        }  

        $request = $this->oAuthRequest($this->requestTokenURL(), 'GET', $parameters); 
        $token = OAuthUtil::parse_parameters($request); 
        $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']); 
        return $token; 
    } 

     
    function getAuthorizeURL($token, $url) { 
        if (is_array($token)) { 
            $token = $token['oauth_token']; 
        } 
        
            return $this->authorizeURL() . "?oauth_token={$token}&oauth_callback=" . urlencode($url); 
        
    } 

     
    function getAccessToken($oauth_verifier = FALSE, $oauth_token = false) { 
        $parameters = array(); 
        if (!empty($oauth_verifier)) { 
            $parameters['oauth_verifier'] = $oauth_verifier; 
        } 


        $request = $this->oAuthRequest($this->accessTokenURL(), 'GET', $parameters); 
        $token = OAuthUtil::parse_parameters($request); 
        $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']); 
        return $token; 
    } 

     
    function get($url, $parameters = array()) { 
        $response = $this->oAuthRequest($url, 'GET', $parameters); 
        if ($response && $this->format === 'json' && $this->decode_json) { 
            return json_decode($response, true); 
        } 
        return $response; 
    } 

     
    function post($url, $parameters = array() , $multi = false) { 
        
        $response = $this->oAuthRequest($url, 'POST', $parameters , $multi ); 
        if ($response && $this->format === 'json' && $this->decode_json) { 
            return json_decode($response, true); 
        } 
        return $response; 
    } 

     
    function delete($url, $parameters = array()) { 
        $response = $this->oAuthRequest($url, 'DELETE', $parameters); 
        if ($response && $this->format === 'json' && $this->decode_json) { 
            return json_decode($response, true); 
        } 
        return $response; 
    } 

     
    function oAuthRequest($url, $method, $parameters , $multi = false) { 

       

                $request = QQOAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters); 
        $request->sign_request($this->sha1_method, $this->consumer, $this->token); 
        switch ($method) { 
        case 'GET': 
                        return $this->http($request->to_url(), 'GET'); 
        default: 
            return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata($multi) , $multi ); 
        } 
    } 

     
    function http($url, $method, $postfields = NULL , $multi = false) { 
        $this->http_info = array(); 
        $ci = curl_init(); 
         
        curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent); 
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout); 
        curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout); 
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE); 

        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer); 

        curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader')); 

        curl_setopt($ci, CURLOPT_HEADER, FALSE); 

        switch ($method) { 
        case 'POST': 
            curl_setopt($ci, CURLOPT_POST, TRUE); 
            if (!empty($postfields)) { 
                curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields); 
                                            } 
            break; 
        case 'DELETE': 
            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE'); 
            if (!empty($postfields)) { 
                $url = "{$url}?{$postfields}"; 
            } 
        } 

        $header_array = array(); 
        

        
                $header_array2=array(); 
        if( $multi ) 
        	$header_array2 = array("Content-Type: multipart/form-data; boundary=" . OAuthUtil::$boundary , "Expect: ");
        foreach($header_array as $k => $v) 
            array_push($header_array2,$k.': '.$v); 

        curl_setopt($ci, CURLOPT_HTTPHEADER, $header_array2 ); 
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE ); 

        
        curl_setopt($ci, CURLOPT_URL, $url); 

        $response = curl_exec($ci); 
        $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE); 
        $this->http_info = array_merge($this->http_info, curl_getinfo($ci)); 
        $this->url = $url; 

                        
                
        curl_close ($ci); 
        return $response; 
    } 

     
    function getHeader($ch, $header) { 
        $i = strpos($header, ':'); 
        if (!empty($i)) { 
            $key = str_replace('-', '_', strtolower(substr($header, 0, $i))); 
            $value = trim(substr($header, $i + 2)); 
            $this->http_header[$key] = $value; 
        } 
        return strlen($header); 
    } 
    
    
    function userInfo()
    {
        $url = 'http:/'.'/open.t.qq.com/api/user/info?format='.$this->format.'&clientip='.$this->clientip();
        
        return $this->get($url);
    }
    
    function tAdd($content = '')
    {
        $url = 'http:/'.'/open.t.qq.com/api/t/add';
        
        $params = array();
        $params['format'] = $this->format;
        $params['content'] = $content;
        $params['clientip'] = $this->clientip();
                
        
        return $this->post($url,$params);
    }
    
    function tAddPic($content = '',$pic=array())
    {
        $url = 'http:/'.'/open.t.qq.com/api/t/add_pic';
        
        $params = array();
        $params['format'] = $this->format;
        $params['content'] = $content;
        $params['clientip'] = $this->clientip();
        $params['pic'] = $pic;
        
        return $this->post($url,$params,true);
    } 
    
    function tReply($reid,$content)
    {
        $url = 'http:/'.'/open.t.qq.com/api/t/reply';
        
        $params = array(
            'format' => $this->format,
            'reid' => $reid,
            'content' => $content,
            'clientip' => $this->clientip(),
        );
        
        
        return $this->post($url,$params);
    }

	function clientip()
	{
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
			$onlineip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
			$onlineip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
			$onlineip = getenv('REMOTE_ADDR');
		} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
			$onlineip = $_SERVER['REMOTE_ADDR'];
		}

		preg_match('/[\d\.]{7,15}/', $onlineip, $onlineipmatches);
		$onlineip = ($onlineipmatches[0] ? $onlineipmatches[0] : 'unknown');

		return $onlineip;
	}
} 