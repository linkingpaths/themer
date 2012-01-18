<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer;

use Symfony\Component\Yaml\Yaml;

/**
 * Themer's data handling class.
 */
class Data implements \ArrayAccess {

  /**
   * @access  protected
   * @var     array   theme parsing data
   */
  protected $data = array();

  /**
   * @access  protected
   * @var     array  available load paths to load Themer data from
   */
  protected $paths = array();

  /**
   * @access  protected
   * @var     array  cache of previously loaded file
   */
  protected $loaded = array();

  /**
   * Constructor.
   *
   * @access  public
   * @return  void
   */
  public function __construct()
  {
    $this->addPath(realpath(__DIR__ . DIRECTORY_SEPARATOR . 'data'));
  }

  /**
   * Adds a data loading path.
   *
   * @access  public
   * @param   string  a single path to register
   * @return  void
   */
  public function addPath($path)
  {
    $path = rtrim($path, DIRECTORY_SEPARATOR);

    if ( ! in_array($path, $this->paths))
    {
      $this->paths[] = $path;
    }
  }

  /**
   * Loads a theme data file.
   *
   * @access  public
   * @param   string  the file to load (relative to any Themer\Data::$load_paths)
   * @return  void
   */
  public function load($file)
  {
    $config = array();
    $found  = FALSE;
    
    foreach ($this->paths as $p)
    {
      if ( ! ($path = realpath($p . DIRECTORY_SEPARATOR . $file)))
      {
        continue;
      }

      $found = TRUE;

      if ( ! $this->isLoaded($path))
      {
        $config = array_merge($config, Yaml::parse($path));
        $this->loaded[] = $path;
      }
    }

    if ( ! $found)
    {
      throw new \InvalidArgumentException("Data file not found: $file");
    }

    $this->data = array_merge($this->data, $config);
  }

  /**
   * Loads a language file.
   *
   * Language files will always be set to the 'lang' index.
   *
   * @access  public
   * @param   string  the language to load (with no '.yml' file extension)
   * @return  void
   */
  public function loadLang($lang)
  {
    $path = implode(DIRECTORY_SEPARATOR, array(__DIR__, 'data', 'locales', $lang)) . '.yml';

    if ( ! file_exists($path))
    {
      throw new \InvalidArgumentException("Language is not supported: $lang");
    }

    $this->data['lang'] = Yaml::parse($path);
  }

  /**
   * Tests whether a file is loaded or not.
   *
   * @access  public
   * @param   string  the file to test
   * @return  bool    whether or not the file has been loaded.
   */
  public function isLoaded($file)
  {
    return (in_array($file, $this->loaded));
  }

  /**
   * Returns the currently loaded data.
   *
   * @access  public
   * @return  array   the currently loaded data
   */
  public function getData()
  {
    return $this->data;
  }

  // --------------------------------------------------------------------

  /**
   * Implemented for the ArrayAccess interface.
   *
   * @access  public
   * @param   string|int  the offset to set
   * @param   mixed       the value
   * @return  mixed       the value
   *
   * @throws  InvalidArgumentException if the offset is NULL
   */
  public function offsetSet($offset, $value)
  {
    if (is_null($offset))
    {
      throw new \InvalidArgumentException(
        'Themer does not allow pushing data to the Data class as an array.'
      );
    }

    return $this->data[$offset] = $value;
  }

  /**
   * Implemented for the ArrayAccess interface.
   *
   * @access  public
   * @param   string|int  the offset to retrieve
   * @return  mixed       the value of the offset or NULL
   */
  public function offsetGet($offset)
  {
    return ( ! isset($this->data[$offset])) ? NULL : $this->data[$offset];
  }

  /**
   * Implemented for the ArrayAccess interface.
   *
   * @access  public
   * @param   string|int  the offset to test
   * @return  bool        whether or not the offset isset
   */
  public function offsetExists($offset)
  {
    return isset($this->data[$offset]);
  }

  /**
   * Implemented for the ArrayAccess interface.
   *
   * @access  public
   * @param   string|int  the offset to unset
   * @return  void
   */
  public function offsetUnset($offset)
  {
    unset($this->data[$offset]);
  }
}
/* End of file Data.php */
/* Location: ./Themer/Data.php */