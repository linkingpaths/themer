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
use Themer\Theme\Parser\BlockParser;

/**
 * Post parser for Tumblr template {block:Posts} blocks.
 */
class PostParser extends BaseParser {

  /**
   * {@inheritDoc}
   */
  public function render(Theme $theme, Data $data)
  {
    $self = $this;

    $theme->renderTemplate('Posts', function ($template) use ($data, $self)
    {
      foreach ($data['Posts'] as $i => $post)
      {
        $self->renderPostIndex($template, $i + 1);
        $self->renderPost($template, $post);
        $self->renderTags($template, $post['Tags']);

        $template->next();
      }
    });
  }

  /**
   * Renders a post index blocks like {block:Post1} or {block:Odd}
   *
   * @access  public
   * @param   Themer\Parser\BlockParser   the post block to render
   * @param   int                         the post index
   * @return  void
   */
  public function renderPostIndex(BlockParser $block, $index)
  {
    $block->renderBlock("Post{$index}");
    $block->renderBlock(($index % 2) ? 'Odd' : 'Even');
  }

  /**
   * Renders a single {block:Posts} block using data for a single post.
   *
   * @access  public
   * @return  void
   */
  public function renderPost(BlockParser $block, $post)
  {
    $block->renderBlock($post['PostType']);

    foreach ($post as $k => $v)
    {
      if (is_array($v))
      {
        $block->renderEach($k, $v);
      }
      elseif ( ! empty($v)) 
      {
        $block->renderBlock($k);
        $block->renderVariable($k, $v);
      }
    }
  }

  /**
   * Renders single post {block:HasTags} & {block:Tags} blocks.
   * 
   * @access  public
   * @param   Themer\Parser\BlockParser
   * @param   array   the tag data
   * @return  void
   */
  public function renderTags(BlockParser $block, $tags)
  {
    if (empty($tags)) return;

    $data = array();

    foreach ($tags as $tag)
    { 
      $tmp['Tag']          = $tag;
      $tmp['URLSafeTag']   = urlencode($tag);
      $tmp['TagURL']       = '/tagged/'.str_replace(' ', '+', $tmp['URLSafeTag']);
      $tmp['TagURLChrono'] = $tmp['TagURL'] . '/chrono';

      array_push($data, array($tmp));
    }

    $block->renderBlock('HasTags');
    $block->renderEach('Tags', $data);
  }
}
/* End of file PostParser.php */
/* Location: ./Themer/Theme/Parser/PostParser.php */