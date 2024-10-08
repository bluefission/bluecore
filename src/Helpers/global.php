<?php
use App\Business\Managers\CommunicationManager;
use BlueFission\Utils\Util;
use BlueFission\Services\Application as App;
use BlueFission\Str;


if(!function_exists('import_env_vars')) {
	function import_env_vars( $file ) {
		$variables = file($file);
		foreach ($variables as $var) {
			putenv(trim($var));
			list($name, $value) = explode("=", $var);
			$_ENV[$name] = $value;
		}
	}
}

if(!function_exists('env')) {
  function env($key, $default = null)
  {
      $value = getenv($key);

      if ($value === false) {
          return $default;
      }
      return $value;
  }
}

if (!function_exists( 'get_site_url' )) {
	function get_site_url( $app_id = null, $path = '', $scheme = null ) {
	    if ( empty( $app_id ) && isset($_SERVER['HTTPS']) && isset($_SERVER['HTTP_HOST']) ) {
	        // $url = 'http://leads.local:8080';
	        $url = ( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] )  ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
	    } else {
	        $url = '/';
	    }
	 
	    if ( $path && is_string( $path ) ) {
	        $url .= '/' . ltrim( $path, '/' );
	    }
	 
	    return $url;
	}
}

if (!function_exists( 'instance' )) {
	function instance($serviceName = '')
	{
		if ( $serviceName == '' ) {
			return App::instance();
		}
		$service = App::instance()->service($serviceName);
		return $service;
	}
}

if (!function_exists('listen')) {
	function listen($event, $callback) {
		$app = instance();
		$app->behavior($event, $callback);
	}
}


if (!defined("ROOT_URL") ){
	define('ROOT_URL', get_site_url(null, '/' . env('DASHBOARD_DIRECTORY', 'dashboard/')));
}

function prompt_silent($prompt = "Enter Password:") {
  if (preg_match('/^win/i', PHP_OS)) {
    $vbscript = sys_get_temp_dir() . 'prompt_password.vbs';
    file_put_contents(
      $vbscript, 'wscript.echo(InputBox("'
      . addslashes($prompt)
      . '", "", "password here"))');
    $command = "cscript //nologo " . escapeshellarg($vbscript);
    $password = rtrim(shell_exec($command));
    unlink($vbscript);
    return $password;
  } else {
    $command = "/usr/bin/env bash -c 'echo OK'";
    if (rtrim(shell_exec($command)) !== 'OK') {
      trigger_error("Can't invoke bash");
      return;
    }
    $command = "/usr/bin/env bash -c 'read -s -p \""
      . addslashes($prompt)
      . "\" mypassword && echo \$mypassword'";
    $password = rtrim(shell_exec($command));
    echo "\n";
    return $password;
  }
}

if (!function_exists('tell')) {
    function tell(string $content, string $channel = null, int $userId = null, array $attachments = [], array $parameters = [])
    {
        return CommunicationManager::send($content, $channel, $userId, false, $attachments, $parameters);
    }
}

if (!function_exists('ask')) {
    function ask(string $content, string $channel = null, int $userId = null, array $attachments = [], array $parameters = [])
    {
        return CommunicationManager::send($content, $channel, $userId, true, $attachments, $parameters);
    }
}

if (!function_exists('whisper')) {
	function whisper(string $content, int $userId = null, array $attachments = [], array $parameters = [])
	{
	    return CommunicationManager::send($content, null, $userId, $attachments, $parameters, true);
	}
}

if (!function_exists('store')) {
	function store(string $var, mixed $value = null): mixed 
	{
		return Util::store($var, $value);
	}
}

if (!function_exists('csrf_token')) {
	function csrf_token() {
		return Util::csrfToken();
	}
}

if (!function_exists('slugify')) {
	function slugify(string $string): string {
		return Str::slugify($string);
	}
}

if (!function_exists('pluralize')) {
	function pluralize(string $string): string {
		return Str::pluralize($string);
	}
}