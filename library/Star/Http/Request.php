<?php

/**
 * request 类
 *
 * @author zhangqy
 *
 */

class Star_Http_Request extends Star_Http_Abstract
{
    protected $params = array();

    protected $url_delimiter = '/';
    
    public function __construct()
	{
		$this->params = $_REQUEST;
		$uri = $this->getUri();
		$this->parseUrl($uri);
	}
	
    /**
     * 是否是POST请求
     * 
     * @return boolean 
     */
	public function isPost()
	{
		if ('POST' == $this->getMethod())
		{
			return true;
		}
		
		return false;
	}
	
	protected function getUri()
	{
		$uri = '';
		
		if (isset($_SERVER['REDIRECT_URL']) && $_SERVER['REDIRECT_URL'])
		{
			$uri = $_SERVER['REDIRECT_URL'];
		} else if ($_SERVER['REQUEST_URI'])
		{
            $url_info = parse_url($_SERVER['REQUEST_URI']);
			$uri = ltrim(str_replace($_SERVER['SCRIPT_NAME'], '', $url_info['path']), '\\/');
		}

		return $uri;
		
	}
    
	/**
     * 是否是GET请求
     * 
     * @return boolean 
     */
	public function isGet()
	{
		if ('GET' == $this->getMethod())
		{
			return true;
		}
		
		return false;
	}
	
    /**
     * 是否是ajax请求， 只针对jQuery框架有效
     * 
     * @return boolean 
     */
	public function isAjax()
	{
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']))
		{
			return true;
		}
		
		return false;
	}
	
    /**
     * 返回所有请求参数
     * 
     * @return type 
     */
	public function getParams()
	{
		return $this->params;
	}
	
    /**
     * 通过key返回参数
     * 
     * @param type $key
     * @return type 
     */
	public function getParam($key)
	{
		return isset($this->params[$key]) ? $this->params[$key] : '';
	}
	
    /**
     * 通过key设置参数
     * 
     * @param type $key
     * @param type $value 
     */
	public function setParam($key, $value)
	{
		$this->params[$key] = $value;
	}
	
    /**
     * 获取请求方法
     * 
     * @return type 
     */
	protected function getMethod()
	{
		return $_SERVER['REQUEST_METHOD'];
	}
	
    /**
     * 返回请求HOST
     * 
     * @return type 
     */
	public function getHost()
	{
		return $_SERVER['HTTP_HOST'];
	}
	
    /**
     * 解析访问URL
     * 
     * @param type $url 
     */
	protected function parseUrl($url)
	{
		if (!empty($url))
		{
			$url = str_replace('\\', '/', $url);
			
			$params = explode($this->url_delimiter, $url);

			if (!empty($params))
			{
				//剔除数组第一个值
				if (empty($params[0]))
				{
					array_shift($params);
				}
				
				$params = array_chunk($params, 2);
				$app_info = array_shift($params);
				
				$controller_name = isset($app_info[0]) ? $app_info[0] : '';
				$action_name = isset($app_info[1]) ? $app_info[1] : '';
				
				$this->setControllerName($controller_name);
				$this->setActionName($action_name);
				
				if (!empty($params))
				{
					foreach ((array) $params as $value)
					{
						$this->setParam($value[0], $value[1]);
					}
				}
			}
		}
	}
}

?>