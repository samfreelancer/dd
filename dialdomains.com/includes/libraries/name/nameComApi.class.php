<?php
 
class nameComApi
{
  public function __call($name, $arguments)
  {
    $class = "NameCom_$name";
    if(class_exists($class))
    {
      $reflection = new ReflectionClass($class);
      $object = $reflection->newInstanceArgs($arguments);
      return $object->submit();
    }
    else
      throw new Exception('Call to undefined method ' . get_class($this) . "::$name()");
  }

  public function sessionToken($session_token = NULL)
  {
    NameComRequest::sessionToken($session_token);
  }

  public function username($username = NULL)
  {
    NameComRequest::username($username);
  }

  public function apiToken($api_token = NULL)
  {
    NameComRequest::apiToken($api_token);
  }  
}

abstract class NameComRequest
{
  static $DEBUG = LIB_DEBUG;
  static $BASE_URL = NAME_URL;
  static $SESSION_TOKEN = NULL;
  static $USERNAME = NAME_USERNAME;
  static $API_TOKEN = NAME_API_TOKEN;

  public $method = NULL;
  public $path = NULL;
  public $parameters = array();
  public $query_string = NULL;
  public $post = NULL;
  public $TIMEOUT = 15;

  public $response = NULL;

    public function __construct()
    {
    }

  function submit()
  {
    $ch = curl_init();
    $headers = array();

    $path = $this->path;
    //log_me("Path ".$path);
    //log_me(self::$SESSION_TOKEN);
    if($this->parameters) {
      $path .= '/' . implode('/', $this->parameters);
    }
    curl_setopt($ch, CURLOPT_URL, $request = self::$BASE_URL . $path . (($this->query_string)?'?' . $this->query_string:''));
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->TIMEOUT);
    curl_setopt($ch, CURLOPT_TIMEOUT, $this->TIMEOUT);

    //log_me("#DEBUG REQUEST   : $this->method $request\n");

    if(isset(self::$SESSION_TOKEN))
    {
	    //log_me("#DEBUG SESSION   : " . self::$SESSION_TOKEN . "\n");
	    $headers[] = 'Api-Session-Token: ' . self::$SESSION_TOKEN;
    }

    if(isset(self::$USERNAME) && isset(self::$API_TOKEN))
    {
        $headers[] = 'Api-Username: ' . self::$USERNAME;
        $headers[] = 'Api-Token: ' . self::$API_TOKEN;
        //log_me("#DEBUG USERNAME  : " . self::$USERNAME . "\n");
	    //log_me("#DEBUG API_TOKEN : " . self::$API_TOKEN . "\n");
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if('POST' == $this->method)
    {
        curl_setopt($ch, CURLOPT_POST, 1);
        if(isset($this->post))
        {
            $post = json_encode($this->post);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            //log_me("#DEBUG POST      : $post\n");
        }
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $this->raw = curl_exec($ch);
    //log_me("#DEBUG RESPONSE  : $this->raw\n\n");
    curl_close($ch);
    return $this->response = !empty($this->raw) ? json_decode($this->raw, true) : '';
  }

  static function sessionToken($session_token = NULL)
  {
    if($session_token !== NULL)
      self::$SESSION_TOKEN = $session_token;
    else
      return self::$SESSION_TOKEN;
  }

  static function username($username = NULL)
  {
    if($username !== NULL)
      self::$USERNAME = $username;
    else
      return self::$USERNAME;
  }

  static function apiToken($api_token = NULL)
  {
    if($api_token !== NULL)
      self::$API_TOKEN = $api_token;
    else
      return self::$API_TOKEN;
  }
}

class NameCom_login extends NameComRequest
{
  public $method = 'POST';
  public $path = '/login';

  function __construct($username = NAME_USERNAME, $api_token = NAME_API_TOKEN)
  {
    $this->post = array('username' => $username,
			'api_token' => $api_token);
  }

  function submit()
  {
    parent::submit();
    if(!empty($this->response) && $this->response['result']['code'] == 100) {
      self::$SESSION_TOKEN = $this->response['session_token'];
    }
    return $this->response;
  }
}

class NameCom_hello extends NameComRequest
{
  public $method = 'GET';
  public $path = '/hello';
}

class NameCom_logout extends NameComRequest
{
  public $method = 'GET';
  public $path = '/logout';
}

class NameCom_get_account extends NameComRequest
{
  public $method = 'GET';
  public $path = '/account/get';
}

class NameCom_list_domains extends NameComRequest
{
  public $method = 'GET';
  public $path = '/domain/list';

  function __construct($username = NULL)
  {
    if(isset($username))
      $this->parameters = array($username);
  }
}

class NameCom_update_domain_nameservers extends NameComRequest
{
  public $method = 'POST';
  public $path = '/domain/update_nameservers';

  function __construct($domain_name, $nameservers)
  {
    $this->parameters = array($domain_name);
    $this->post = array('nameservers' => $nameservers);
  }
}

class NameCom_update_domain_contacts extends NameComRequest
{
  public $method = 'POST';
  public $path = '/domain/update_contacts';

  function __construct($domain_name, $contacts)
  {
    $this->parameters = array($domain_name);
    $this->post = array('contacts' => $contacts);
  }
}

class NameCom_lock_domain extends NameComRequest
{
  public $method = 'GET';
  public $path = '/domain/lock';

  function __construct($domain_name)
  {
    $this->parameters = array($domain_name);
  }
}

class NameCom_unlock_domain extends NameComRequest
{
  public $method = 'GET';
  public $path = '/domain/unlock';

  function __construct($domain_name)
  {
    $this->parameters = array($domain_name);
  }
}

class NameCom_create_dns_record extends NameComRequest
{
  public $method = 'POST';
  public $path = '/dns/create';

  function __construct($domain_name, $hostname, $type, $content, $ttl, $priority = NULL)
  {
    $this->parameters = array($domain_name);

    $this->post = array('hostname' => $hostname,
                        'type' => $type,
                        'content' => $content,
                        'ttl' => $ttl,
                        );

    if(isset($priority))
      $this->post['priority'] = $priority;
  }
}
class NameCom_add_dns_record extends NameCom_create_dns_record { }

class NameCom_list_dns_records extends NameComRequest
{
  public $method = 'GET';
  public $path = '/dns/list';

  function __construct($domain_name)
  {
    $this->parameters = array($domain_name);
  }
}

class NameCom_delete_dns_record extends NameComRequest
{
  public $method = 'POST';
  public $path = '/dns/delete';

  function __construct($domain_name, $record_id)
  {
    $this->parameters = array($domain_name);
    $this->post = array('record_id' => $record_id);
  }
}
class NameCom_remove_dns_record extends NameCom_delete_dns_record { }

class NameCom_check_domain extends NameComRequest
{
  public $method = 'POST';
  public $path = '/domain/power_check';

  function __construct($keyword, $tlds = NULL, $services = NULL)
  {
    /*
    $this->path = "/domain/power_check/$keyword";

    if(isset($tlds))
    {
      $this->path .= '/' . (implode(',', $tlds) ?: 'null');

      if(isset($services))
	$this->path .= '/' . implode(',', $services);
    }
    */

    $this->post = array('keyword' => $keyword);
    if(isset($tlds))
      $this->post['tlds'] = $tlds;
    if(isset($services))
      $this->post['services'] = $services;
  }
}

class NameCom_create_domain extends NameComRequest
{
  public $method = 'POST';
  public $path = '/domain/create';

  function __construct($domain_name, $period, $nameservers, $contacts, $username = null)
  {
    // If a single contact array is passed to this function,
    // this will place that into an array (as expected by API).
    if(isset($contacts['type']))
      $contacts = array($contacts);

    $this->post = array('domain_name' => $domain_name,
			'period' => $period,
			'nameservers' => $nameservers,
			'contacts' => $contacts,
			);

    if(isset($username))
      $this->post['username'] = $username;
  }
}

class NameCom_order extends NameComRequest
{
    public $method = 'POST';
    public $path = '/order';
    
    function __construct($items)
    {    
        $this->post = $items;
    }
}

class NameCom_get_domain extends NameComRequest
{
  public $method = 'GET';
  public $path = '/domain/get';

  function __construct($domain_name)
  {
    $this->parameters = array($domain_name);
  }
}

class NameCom_get_nameserver extends NameComRequest
{
    public $method = 'GET';
    public $path = '/host/list';
    function __construct($domain_name)
    {
        $this->parameters = array($domain_name);
    }
}

class NameCom_add_nameserver extends NameComRequest
{
    public $method = 'POST';
    public $path = '/host/create';
    function __construct($domain_name, $nameserver, $ip = '127.0.0.1')
    {
        $this->parameters = array($domain_name);
        if(!empty($nameserver)) {
            $this->post = array(
            	'hostname' => $nameserver,
                'ip' => $ip
            );
        }
    }
}

class NameCom_delete_nameserver extends NameComRequest
{
    public $method = 'POST';
    public $path = '/host/delete';
    function __construct($domain_name, $nameserver)
    {
        $this->parameters = array($domain_name);
        if(!empty($nameserver)) {
            $this->post = array(
            	'hostname' => $nameserver,
            );
        }
    }
}

class NameCom_get_dns extends NameComRequest
{
    public $method = 'GET';
    public $path = '/dns/list';
    function __construct($domain_name)
    {
        $this->parameters = array($domain_name);
    }
}

class NameCom_delete_dns extends NameComRequest
{
    public $method = 'GET';
    public $path = '/dns/delete';
    function __construct($domain_name, $record_id)
    {
        $this->parameters = array($domain_name);
        if(!empty($record_id)) {
            $this->post = array(
            	'record_id' => $record_id,
            );
        }
    }
}

class NameCom_add_dns extends NameComRequest
{
    public $method = 'GET';
    public $path = '/dns/create';
    function __construct($domain_name, $dns)
    {
        $this->parameters = array($domain_name);
        if(!empty($dns)) {
            $this->post = array(
            	'hostname' => $dns['hostname'],
                'type' => $dns['type'],
                'content' => $dns['content'],
                'ttl' => $dns['ttl'],
                'priority' => $dns['priority'],
            );
        }
    }
}
class NameCom_renew_domain extends NameComRequest
{
  public $method = 'POST';
  public $path = '/domain/renew';

  function __construct($domain_name, $period)
  {
    // If a single contact array is passed to this function,
    // this will place that into an array (as expected by API).
    if(isset($contacts['type']))
      $contacts = array($contacts);

    $this->post = array('domain_name' => $domain_name,
			'period' => $period,
			'nameservers' => $nameservers,
			'contacts' => $contacts,
			);

    if(isset($username))
      $this->post['username'] = $username;
  }
}