<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer;

/**
 * Themer variable parser.Renders Tumblr tag variables 
 */
class Variable {
  
  /**
   * A variable tag matcher for Tumblr themes.
   */
  const MATCHER  = '/{([A-Za-z][A-Za-z0-9\-]*)}/i';

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
  public static function render($block, $search, $replace = '', $transformable = TRUE)
  {
    $block = self::_simple($block, $search, $replace);
    
    if($transformable === TRUE)
    {
      $block = self::_plaintext($block, $search, $replace);
      $block = self::_js($block, $search, $replace);    
      $block = self::_jsplaintext($block, $search, $replace);
      $block = self::_urlencoded($block, $search, $replace);
    }
    
    return $block;
  }
  
  /**
   * Simply replace the tag with the value.
   *
   * @static
   * @access  private
   * @param   string  the block to parse
   * @param   string  the tag name to replace
   * @param   string  the replacement value
   * @return  string  the formatted value
   */
  private static function _simple($block, $search, $replace = '')
  {
    return preg_replace('/{'.$search.'}/', $replace, $block);
  }
  
  /**
   * Replace a Plaintext tagged variable with the plaintext value.
   *
   * @static
   * @access  private
   * @param   string  the block to parse
   * @param   string  the tag name to replace
   * @param   string  the replacement value
   * @return  string  the formatted value
   */
  private static function _plaintext($block, $search, $replace = '')
  {
    
    return preg_replace('/{Plaintext'.$search.'}/i', htmlspecialchars($replace), $block);
  }
  
  /**
   * Replace a JS tagged variable with the JSON encoded value.
   * 
   * @static
   * @access  private
   * @param   string  the block to parse
   * @param   string  the tag name to replace
   * @param   string  the replacement value
   * @return  string  the formatted value
   */
  private static function _js($block, $search, $replace = '')
  {
    return preg_replace('/{JS'.$search.'}/i', json_encode($replace), $block);
  }
  
  /**
   * Replace a JSPlaintext tagged variable with the plaintext, JSON
   * encoded value.
   * 
   * @static
   * @access  private
   * @param   string  the block to parse
   * @param   string  the tag name to replace
   * @param   string  the replacement value
   * @return  string  the formatted value
   */
  private static function _jsplaintext($block, $search, $replace = '')
  {
    $replace = json_encode(htmlspecialchars($replace));
    return preg_replace('/{JSPlaintext'.$search.'}/i', $replace, $block);
  }
  
  /**
   * Replace a URLEncoded tagged variable with a url encoded value.
   *
   * @static
   * @access  private
   * @param   string  the block to parse
   * @param   string  the tag name to replace
   * @param   string  the replacement value
   * @return  string  the formatted value
   */
  private static function _urlencoded($block, $search, $replace = '')
  {
    return preg_replace('/{URLEncoded'.$search.'}/i', urlencode($replace), $block);
  }
}

/* End of file Variable.php */
/* Location: ./Themer/Variable.php */