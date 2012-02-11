<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer\Theme\Parser;

use Closure;

use Themer\Theme;
use Themer\Theme\Helper\Block;
use Themer\Theme\Helper\Variable;
use Themer\Theme\Parser\TemplateParser;

/**
 * Parses block tags relevant to a specific theme.
 */
class BlockParser {

  /**
   * @access  protected
   * @var     string  the current block
   */
  protected $block = '';

  /**
   * @access  protected
   * @var     string  the original block
   */
  protected $original = '';

  /**
   * Constructor.
   *
   * @access  public
   * @param   Themer\Theme
   * @param   string        the block to parse
   * @return  void
   */
  public function __construct($block = '')
  {
    $this->block    = $block;
    $this->original = $block;
  }

  /**
   * Returns the current block.
   *
   * @access  public
   * @return  string  the current block
   */
  public function __toString()
  {
    return $this->block;
  }

  /**
   * Returns the current block.
   *
   * @access  public
   * @return  string  the current block
   */
  public function getBlock()
  {
    return $this->block;
  }

  /**
   * Sets the current block with the given contents.
   *
   * @access  public
   * @param   string  the new block contents
   * @return  void
   */
  public function setBlock($block)
  {
    $this->block = $block;
  }

  /**
   * Returns the original block.
   *
   * @access  public
   * @return  string  the original block
   */
  public function getOriginal()
  {
    return $this->original;
  }

  /**
   * Renders an array of template variables with indexes as the variable {tag}.
   *
   * @access  public
   * @param   array   the data to use
   * @param   bool    whether the variable is transformable or not
   * @return  void
   */
  function renderVariables(array $data, $transform = TRUE)
  {
    $this->block = Variable::renderArray($this->block, $data, $transform);
  }

  /**
   * Renders a Tumblr template variable for the current block.
   *
   * @access  public
   * @param   string  the variable key to render
   * @param   string  the value of the variable
   * @param   bool    whether the variable is transformable or not
   * @return  void
   */
  public function renderVariable($key, $value, $transform = TRUE)
  {
    $this->block = Variable::render($this->block, $key, $value, $transform);
  }

  /**
   * Renders a given Tumblr template block for the current block.
   *
   * If a Closure is given as the second parameter, it creates a new 
   * Themer\Parser\BlockParser object for each block found and calls
   * the closure with that block as it's only argument (BlockParser
   * objects are Themer\Theme "aware").
   * 
   * If no Closure is given, the block is simply rendered out.
   *
   * @access  public
   * @param   string    the block tag to render
   * @param   Closure   optional callback for manipulating the block
   * @return  void
   */
  public function renderBlock($tag, Closure $callback = NULL)
  {
    if (is_null($callback))
    {
      $this->block = Block::render($this->block, $tag);
    }
    else
    {
      foreach (Block::find($this->block, $tag) as $block)
      {
        $parser = new BlockParser(Block::render($block, $tag));
        call_user_func($callback, $parser);
        $this->replace($block, $parser->getBlock(), $this->block);
      }
    }
  }

  /**
   * Renders each found block of a given block using a callback.
   *
   * @access  public
   * @param   string  the block tag to render
   * @param   array   the callback
   * @return  void
   */
  public function renderEach($tag, array $data)
  {
    foreach (Block::find($this->block, $tag) as $cache)
    {
      $rendered = '';

      foreach ($data as $v)
      {
        $block = new BlockParser(Block::render($cache, $tag));
        $block->renderVariables($v);
        $rendered .= $block->getBlock();
      }

      $this->replace($cache, $rendered);
    }
  }

  /**
   * Renders a template block, with the original block being replaced by
   * multiple renderings of that block.
   *
   * @access  public
   * @return  void
   */
  public function renderTemplate($tag, Closure $callback)
  {
    foreach (Block::find($this->block, $tag) as $block)
    {
      $template = new TemplateParser(Block::render($block, $tag));
      call_user_func($callback, $template);
      $this->replace($block, $template->getTemplate());
    }
  }

  /**
   * Replaces a given string with a new value within the current block.
   * 
   * @access  public
   * @param   string  the old value
   * @param   string  the new value
   * @return  void
   */
  public function replace($old, $new)
  {
    $this->block = str_replace($old, $new, $this->block);
  }
}
/* End of file BlockParser.php */
/* Location: ./Themer/Theme/Parser/BlockParser.php */