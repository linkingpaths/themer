<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer;

use Themer\Data;
use Themer\Parser\BaseParser;
use Themer\Variable;

/**
 * An base theme class for handling Tumblr template files.
 */
class Theme {

  /**
   * @access  protected
   * @var     string  the parsed theme contents
   */
  protected $theme = '';

  /**
   * @access  protected
   * @var     string  the original theme
   */
  protected $original = '';

  /**
   * @access  protected
   * @var     Themer\Data
   */
  protected $data = '';

  /**
   * @access  protected
   * @var     array     registered parsers
   */
  protected $parsers = array();

  /**
   * Constructor.
   *
   * @access  public
   * @param   string  the theme file  path or theme contents
   * @return  void
   *
   * @throws  InvalidArgumentException if given a non-existent theme file
   * @throws  InvalidArgumentException if given an invalid theme file
   */
  public function __construct($theme)
  {    
    if (substr_count($theme, "\n") > 0)
    {
      $this->theme = $theme;
    }
    else
    {
      if ( ! file_exists($theme))
      {
        throw new \InvalidArgumentException('Theme file does not exist: '.$theme);
      }

      if (FALSE == ($contents = @file_get_contents($theme)))
      {
        throw new \InvalidArgumentException('Theme file is invalid: '.$theme);
      }

      $this->theme = $contents;
    }

    $this->original = $this->theme;
    $this->data = new Data();
  }

  /**
   * Returns the template contents
   *
   * @access  public
   * @return  string  the theme contents
   */
  public function __toString()
  {
    return $this->getTheme();
  }

  /**
   * Registers an array of parsers.
   *
   * @access  public
   * @param   array   the parsers to register
   * @return  void
   */
  public function registerParsers(array $parsers)
  {
    foreach ($parsers as $parser)
    {
      $this->registerParser($parser);
    }
  }

  /**
   * Registers Themer template parsers.
   *
   * Parsers are utilized in the order they are registered, and any one parser
   * can be registered multiple times.
   *
   * @access  public
   * @param   Themer\Parser\BaseParser  the parser to register
   * @return  void
   */
  public function registerParser(BaseParser $parser)
  {
    $parser->preload($this->data);
    $this->parsers[] = $parser;
  }

  /**
   * Returns the theme's contents.
   *
   * @access  public
   * @return  string  the theme's content
   */
  public function getTheme()
  {
    return $this->theme;
  }

  /**
   * Sets the theme's contents.
   *
   * @access  public
   * @param   string  the new theme content
   * @return  void
   */
  public function setTheme($theme)
  {
    $this->theme = $theme;
  }

  /**
   * Returns the theme's original contents.
   *
   * @access  public
   * @return  string  the theme's original contents
   */
  public function getOriginal()
  {
    return $this->original;
  }

  /**
   * Returns the current Themer data object.
   * 
   * @access  public
   * @return  Themer\Data
   */
  public function getData()
  {
    return $this->data;
  }

  /**
   * Renders the theme.
   *
   * @access  public
   * @return  string  the rendered theme
   */
  public function render()
  {
    foreach ($this->parsers as $parser)
    {
      $parser->render($this, $this->data);
    }

    return $this->getTheme();
  }

  /**
   * Renders Tumblr template variables for the current theme.
   * 
   * @access  public
   * @param   string  the variable key to render
   * @param   string  the value of the variable
   * @param   bool    whether the variable is transformable or not
   * @return  void
   */
  public function renderVariable($key, $value, $transform = TRUE)
  {
    $this->theme = Variable::render($this->theme, $key, $value, $transform);
  }
}
/* End of file Theme.php */
/* Location: ./Themer/Theme.php */