<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer\Theme\Parser;

use Themer\Data;
use Themer\Theme;

/**
 * Themer's Language Parser.
 *
 * Parsers Tumblr template {lang:...} variables.
 */
class LanguageParser extends BaseParser {

  /**
   * @access  protected
   * @var     string  the locale
   */
  protected $locale = 'en';

  /**
   * Constructor
   *
   * @access  public
   * @param   string  the optional locale to use (if different than the default)
   * @return  void
   */
  public function __construct($locale = '')
  {
    if ( ! empty($locale))
    {
      $this->setLocale($locale);
    }
  }

  /**
   * Set's the locale to use.
   *
   * @access  public
   * @param   string  the locale to use
   * @return  void
   */
  public function setLocale($locale)
  {
    $this->locale = $locale;
  }

  /**
   * Returns the current locale.
   * 
   * @access  public
   * @return  string  the current locale
   */
  public function getLocale()
  {
    return $this->locale;
  }

  /**
   * {@inheritDoc}
   */
  public function preload(Data $data)
  {
    $data->loadLang($this->locale);
  }

  /**
   * {@inheritDoc}
   */
  public function render(Theme $theme, Data $data)
  {
    foreach ($data['lang'] as $key => $value)
    {
      $theme->renderVariable("lang:$key", $value, FALSE);
    }
  }
}
/* End of file LanguageParser.php */
/* Location: ./Themer/Theme/Parser/LanguageParser.php */