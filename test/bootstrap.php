<?php

// Error reporting to 11
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);

// Load up the autoloader.
require_once realpath(__DIR__.'/../lib/Themer/Autoloader.php');
Themer\Autoloader::register();


PHP_CodeCoverage_Filter::getInstance()->addDirectoryToBlacklist(PEAR_INSTALL_DIR);
PHP_CodeCoverage_Filter::getInstance()->addDirectoryToBlacklist(PHP_LIBDIR);	

/* End of file bootstrap.php */
/* Location: ./test/bootstrap.php */