<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer;

use Themer\Data;
use Themer\Theme\Parser\BaseParser;
use Themer\Theme\Parser\BlockParser;
use Themer\Theme\Block;
use Themer\Theme\Variable;

/**
 * An base theme class for handling Tumblr template files.
 */
class Theme extends BlockParser {

  /**
   * @access  protected
   * @var     Themer\Data
   */
  protected $data = NULL;

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
  public function __construct($theme, Data $data = NULL)
  {    
    if (substr_count($theme, "\n") == 0)
    {
      if (FALSE == ($contents = @file_get_contents($theme)))
      {
        throw new \InvalidArgumentException('Theme file is invalid: '.$theme);
      }

      $theme = $contents;
    }
    
    $this->data = (is_null($data)) ? new Data() : $data;

    parent::__construct($theme);
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
    return $this->getBlock();
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
    $this->setBlock($theme);
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

    $this->block = Block::cleanup($this->block);

    return $this->block;
  }
}
/* End of file Theme.php */
/* Location: ./Themer/Theme.php */