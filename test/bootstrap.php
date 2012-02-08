<?php

// Error reporting to 11
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);

if ( ! defined('THEMER_BASEPATH'))
{
  define('THEMER_BASEPATH', realpath(__DIR__.'/../lib/Themer'));
}

// Load up the autoloader.
require_once THEMER_BASEPATH.'/Utils/Autoloader.php';
Themer\Utils\Autoloader::register();

/* End of file bootstrap.php */
/* Location: ./test/bootstrap.php */