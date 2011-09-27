<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer;

require_once 'Symfony/Component/ClassLoader/UniversalClassLoader.php';
use Symfony\Component\ClassLoader\UniversalClassLoader;

/**
 * Themer Autoloader.
 */
class Autoloader {
  
  /**
   * @static
   * @access  protected
   * @var     Symfony\Component\ClassLoader\UniversalClassLoader
   */
  static private $_loader = NULL;
  
  // --------------------------------------------------------------------
  
  /**
   * Register the autoload method.
   *
   * @static
   * @access  public
   * @return  void
   */
  static public function register()
  {
    if (is_null(static::$_loader))
    {
      static::$_loader = new UniversalClassLoader;
      static::$_loader->registerNamespace('Themer', realpath(__DIR__.'/../'));
      spl_autoload_register(array(__CLASS__, 'load'), TRUE, FALSE);
    }
  }
  
  /**
   * Loads the given class.
   *
   * @static
   * @access  public
   * @param   string  the class name to load
   * @return  void
   */
  static public function load($class)
  {
    if ($file = static::$_loader->findFile($class))
    {
      require $file;
    }
    elseif ($file = stream_resolve_include_path(str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php'))
    {
      require $file;
    }
  }
  
  /**
   * Magic method for allowing a static implementation of Symfony's
   * UniversalClassLoader.
   * 
   * @static
   * @access  public
   * @param   string  the desired method name
   * @param   array   the arguments for the desired method
   * @return  void
   */
  static public function __callStatic($method, $args = array())
  {
    if ( ! method_exists(static::$_loader, $method))
    {
      throw new \LogicException(sprintf(
        "The method '%s' is not a method of %s.",
        $method, get_class(static::$_loader)
      ));
    }
    
    call_user_func_array(array(static::$_loader, $method), $args);
  }
}
/* End of file Autoloader.php */
/* Location: ./Themer/Autoloader.php */