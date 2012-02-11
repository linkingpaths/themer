<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer\Theme;

/**
 * Themer block parser. Renders Tumblr block tags. 
 */
class Block {

  /**
   * Matches all blocks in a given string
   *
   * Taken from Thimble by Mark Wunsch -- github.com/mwunsch/thimble
   *
   * @access  public
   * @var     string
   */
  const MATCHER = '/{block:([A-Za-z][A-Za-z0-9]*)}(.*?){\/block:\\1}/is';

  /**
   * Strips the open and close tags from a matched block.
   *
   * Taken from Thimble by Mark Wunsch -- github.com/mwunsch/thimble
   *
   * @static
   * @access  public
   * @param   string  the block to search in
   * @param   string  the block tag to strip
   * @return  string  the stripped block
   */
  static public function render($block, $tag)
  {
    return preg_replace_callback(
      self::getMatcher($tag),
      create_function('$matches', 'return $matches[2];'),
      $block
    );
  }

  /**
   * Renders if blocks
   *
   * @static
   * @access  public
   * @param   string  the block to search in
   * @param   string  the block tag name
   * @param   bool    whether or not we should render the block
   * @return  string  the stripped block
   */
  static public function renderIf($block, $tag, $render = FALSE)
  {
    $tag = self::formatIfBlockTag($tag);

    if ($render === TRUE)
    {
      $block = self::remove($block, 'IfNot'.$tag);
      $block = self::render($block, 'If'.$tag);
    }
    else
    {
      $block = self::remove($block, 'If'.$tag);
      $block = self::render($block, 'IfNot'.$tag);
    }

    return $block;
  }

  /**
   * Removes all other block tags from a given block
   *
   * Taken from Thimble by Mark Wunsch -- github.com/mwunsch/thimble
   *
   * @static
   * @access  public
   * @param   string  the given block
   * @return  string  the cleaned up block
   */
  static public function cleanup($block)
  {
    return preg_replace(self::MATCHER, '', $block);
  }

  /**
   * Remove a block completely.
   *
   * @static
   * @access  public
   * @param   string  the block
   * @param   string  the block tag to remove
   * @return  string  the cleaned up block
   */
  static public function remove($block, $tag)
  {
    foreach (self::find($block, $tag) as $b)
    {
      $block = str_replace($b, "", $block);
    }

    return $block;
  }

  /**
   * Attempt to match a set of block open/close tags within a given
   * block.
   *
   * @static
   * @access  public
   * @param   string  the parent block
   * @param   string  the tag to search for
   * @return  array   empty array for no matches, else the matches
   */
  static public function find($block, $tag)
  {
    if ( ! preg_match_all(self::getMatcher($tag), $block, $matches))
    {
      return array();
    }

    return $matches[0];
  }

  /**
   * Returns a formatted block matcher.
   *
   * Taken from Thimble by Mark Wunsch -- github.com/mwunsch/thimble
   *
   * @static
   * @access  public
   * @param   string  the tag name to use
   * @return  string  the formated block pattern matcher
   */
  static public function getMatcher($tag = '')
  {
    return '/{block:('.$tag.')}(.*?){\/block:\\1}/is';
  }

  /**
   * Formats a tag name for If and IfNot blocks. We have to reformat the
   * tag for the blocks (they can't be spaced and the _'s comes from Themer
   * setting form input names with _'s in the keys)
   *
   * @access  public
   * @param   string  the tag name to format
   * @return  string  the formatted tag
   */
  static public function formatIfBlockTag($tag = '')
  {
    return str_replace(array(" ", "_"), "", $tag);
  }
}
/* End of file Block.php */
/* Location: ./Themer/Theme/Block.php */