<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer\Parser;

use Themer\Data;
use Themer\Theme;

/**
 * An abstract base class for Themer parsers.
 *
 * @abstract
 */
abstract class BaseParser {

  /**
   * Setup method that receives a Themer\Data object to enable
   * preloading data for a specific parser.
   *
   * @access  public
   * @param   Themer\Data
   * @return  void
   */
  public function preload(Data $data) {}

  /**
   * Renders the given template.
   *
   * @abstract
   * @access  public
   * @param   Themer\Theme  the template to parse
   * @return  void
   */
  abstract public function render(Theme $theme, Data $data);
}
/* End of file BaseParser.php */
/* Location: ./Themer/Parser/BaseParser.php */