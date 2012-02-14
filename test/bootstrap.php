<?php

// Error reporting to 11
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);

// Easy path constant
if ( ! defined('THEMER_BASEPATH'))
{
  define('THEMER_BASEPATH', realpath(__DIR__.'/../'));
}

// Template loading helper function.
function template($template)
{
  $file = THEMER_BASEPATH . '/test/_files/templates/' . $template . '.txt';
  if (file_exists($file))
  {
    $data = explode('----', file_get_contents($file));
    $data[0] = Symfony\Component\Yaml\Yaml::parse($data[0]);
    return $data;
  }

  throw new \Exception("Invalid test template: $template");
}

// Load up the autoloader.
require_once THEMER_BASEPATH.'/lib/Themer/Utils/Autoloader.php';
Themer\Utils\Autoloader::register();

/* End of file bootstrap.php */
/* Location: ./test/bootstrap.php */