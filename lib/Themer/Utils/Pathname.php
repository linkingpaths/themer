<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer\Utils;

/**
 * Pathname.
 * 
 * A very minimal implementation of Ruby's Pathname class.
 * 
 * It's important to note that, experimentally, each method which
 * returns an altered path actually returns a new Pathname instance.
 */
class Pathname {

  /**
   * Shortcut to PHP's DIRECTORY_SEPARATOR constant.
   */
  const DS = DIRECTORY_SEPARATOR;

  /**
   * @access  protected
   * @var     DirectoryIterator
   */
  protected $path = NULL;

  /**
   * @access  protected
   * @var     array   an array of path parts
   */
  protected $parts = array();

  /**
   * @access  protected
   * @var     string    the base path indicator
   */
  protected $root = '';

  // --------------------------------------------------------------------

  /**
   * Builds a path from the given parts.
   *
   * @access  public
   * @param   string
   * @return  void
   */
  static public function build(array $parts)
  {
    $ds = '/^'. preg_quote(self::DS, '/') .'+$/';
    $root = self::extractRoot($parts[0]);

    for ($i = 0, $c = count($parts); $i < $c; $i++)
    {
      if ( ! strlen($parts[$i]) || preg_match($ds, $parts[$i]))
      {
        unset($parts[$i]);
        continue;
      }

      $parts[$i] = trim($parts[$i], self::DS);
    }

    return new self($root . implode(self::DS, $parts));
  }

  // --------------------------------------------------------------------

  /**
   * Constructor.
   *
   * For the purposes of Themer, the path is required to exists, either
   * as a file or a directory.
   *
   * @access  public
   * @param   string  the path
   * @return  void
   */
  public function __construct($path)
  {
    $this->path  = (strlen($path) > 1) ? rtrim($path, self::DS) : $path;
    $this->parts = explode(self::DS, trim($this->path, self::DS));
    $this->root  = static::extractRoot($this->path);
  }

  /**
   * Returns the absolute path string.
   *
   * @access  public
   * @return  string  the absolute path string
   */
  public function __toString()
  {
    return $this->path;
  }

  /**
   * Returns the initial path.
   *
   * @access  public
   * @return  array   the path 
   */
  public function getPath()
  {
    return $this->path;
  }

  /**
   * Returns the supplied path's root (if there is one).
   * 
   * @access  public
   * @return  string  the supplied path's root
   */
  public function getRoot()
  {
    return $this->root;
  }

  /**
   * Returns an array of Pathname objects traversing the original path
   * in reverse order.
   *
   * @access  public
   * @return  array   a set of Pathname objects   
   */
  public function ascend()
  {
    $paths = array($this);

    for ($i = count($this->parts) - 1; $i >= 0; $i--)
    {
      array_push($paths, $this->slice($i));
    }

    return $paths;
  }

  /**
   * Joins a variable number of path sub-path parts with the initial path.
   *
   * @access  public
   * @param   array     path parts
   * @return  Pathname  a Pathname object representing the joined path
   */
  public function join(array $parts)
  {
    return static::build(array_merge(array($this->getPath()), $parts));
  }

  /**
   * Slices the path by a supplied length.
   *
   * @access  public
   * @param   int       the length of the slice
   * @return  Pathname  a Pathname object representing the sliced path
   */
  public function slice($length = 0)
  {
    $parts = $this->parts;
    $root = $this->getRoot();

    if (strlen($root) && strpos($parts[0], $root) !== 0)
    {
      $parts[0] = $root.$parts[0];
    }

    if ($length == 0)
    {
      return new self((strlen($root)) ? $root : '');
    }

    return static::build(array_slice(
      $parts, 0, $length
    ));
  }

  // --------------------------------------------------------------------

  /**
   * Extracts the root level indicator from a given path string.
   *
   * @access  public
   * @param   string  the path
   * @return  string  the root level indicator or an empty string
   */
  static public function extractRoot($path = '')
  {
    if (self::DS === "\\")
    {
      if (preg_match('/^((\w:)?\\\\?)/', $path, $matches))
      {
        return $matches[1];
      }
    }
    elseif (strlen($path) && strpos($path, '/') === 0)
    {
      return '/';
    }

    return '';
  }
}
/* End of file Pathname.php */
/* Location: ./Themer/Utils/Pathname.php */