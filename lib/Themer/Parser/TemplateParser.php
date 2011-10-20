<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer\Parser;

/**
 * Renders a block as a template.
 */
class TemplateParser extends BlockParser {

  /**
   * @access  protected
   * @var     string  the currently rendered template
   */
  protected $template = '';

  /**
   * Appends the current rendition of the block to the template, then
   * resets the block to it's original condition.
   *
   * @access  public
   * @return  void
   */
  public function next()
  {
    $this->template .= $this->block;
    $this->block = $this->original;
  }

  /**
   * Returns the currently parsed template.
   *
   * @access  public
   * @return  string  the current template
   */
  public function getTemplate()
  {
    return $this->template;
  }
}
/* End of file TemplateParser.php */
/* Location: ./Themer/Parser/TemplateParser.php */