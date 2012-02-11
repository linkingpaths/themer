<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer\Theme\Helper;

/**
 * Themer variable parser.Renders Tumblr tag variables 
 */
class Variable {
  
  /**
   * A variable tag matcher for Tumblr themes.
   */
  const MATCHER  = '/{([A-Za-z][A-Za-z0-9\-]*)}/i';

  /**
   * Renders an array of variables with indexes used as template tags.
   *
   * @static
   * @access  public
   * @param   string  the block to parse
   * @param   array   the data to use
   * @param   bool    whether the variables are transformable
   * @return  string  the parsed block
   */
  static public function renderArray($block, array $data, $transform = TRUE)
  {
    foreach ($data as $k => $v)
    {
      if (is_array($v))
      {
        $block = static::renderArray($block, $v, $transform);
      }
      else
      {
        $block = static::render($block, $k, $v, $transform);
      }
    }

    return $block;
  }

  /**
   * Renders a specific Tumblr tag variable.
   *
   * @static
   * @access  public
   * @param   string  the block to parse
   * @param   string  the tag name to replace
   * @param   string  the replacement value
   * @param   bool    whether the variable is transformable or not
   * @return  string  the parsed block
   */
  static public function render($block, $search, $replace = '', $transformable = TRUE)
  {
    $block = self::renderSimple($block, $search, $replace);

    if ($transformable === TRUE)
    {
      $block = self::renderPlaintext($block, $search, $replace);
      $block = self::renderJS($block, $search, $replace);    
      $block = self::renderJSPlaintext($block, $search, $replace);
      $block = self::renderURLEncoded($block, $search, $replace);
    }

    return $block;
  }

  /**
   * Simply replace the tag with the value.
   *
   * @static
   * @access  public
   * @param   string  the block to parse
   * @param   string  the tag name to replace
   * @param   string  the replacement value
   * @return  string  the formatted value
   */
  public static function renderSimple($block, $search, $replace = '')
  {
    return preg_replace('/{'.$search.'}/', $replace, $block);
  }

  /**
   * Replace a Plaintext tagged variable with the plaintext value.
   *
   * @static
   * @access  public
   * @param   string  the block to parse
   * @param   string  the tag name to replace
   * @param   string  the replacement value
   * @return  string  the formatted value
   */
  public static function renderPlaintext($block, $search, $replace = '')
  {
    return preg_replace('/{Plaintext'.$search.'}/i', htmlspecialchars($replace), $block);
  }

  /**
   * Replace a JS tagged variable with the JSON encoded value.
   *
   * @static
   * @access  public
   * @param   string  the block to parse
   * @param   string  the tag name to replace
   * @param   string  the replacement value
   * @return  string  the formatted value
   */
  public static function renderJS($block, $search, $replace = '')
  {
    return preg_replace('/{JS'.$search.'}/i', json_encode($replace), $block);
  }

  /**
   * Replace a JSPlaintext tagged variable with the plaintext, JSON
   * encoded value.
   *
   * @static
   * @access  public
   * @param   string  the block to parse
   * @param   string  the tag name to replace
   * @param   string  the replacement value
   * @return  string  the formatted value
   */
  public static function renderJSPlaintext($block, $search, $replace = '')
  {
    $replace = json_encode(htmlspecialchars($replace));
    return preg_replace('/{JSPlaintext'.$search.'}/i', $replace, $block);
  }

  /**
   * Replace a URLEncoded tagged variable with a url encoded value.
   *
   * @static
   * @access  public
   * @param   string  the block to parse
   * @param   string  the tag name to replace
   * @param   string  the replacement value
   * @return  string  the formatted value
   */
  public static function renderURLEncoded($block, $search, $replace = '')
  {
    return preg_replace('/{URLEncoded'.$search.'}/i', urlencode($replace), $block);
  }
}
/* End of file Variable.php */
/* Location: ./Themer/Theme/Helper/Variable.php */