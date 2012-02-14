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

class PostParserTest extends \PHPUnit_Framework_TestCase {

  /**
   * @test
   * @covers  Themer\Theme\Parser\PostParser::render
   */
  public function renders_post_blocks()
  {
    $block = <<<BLOCK
{block:Posts}
This is a post!{/block:Posts}
BLOCK;

    $expected = <<<BLOCK

This is a post!
This is a post!
BLOCK;

    $data = new Data;
    $post = array('PostType' => '', 'Tags' => array(), 'Time' => time());
    $data['Posts'] = array($post, $post);

    $theme = new Theme($block, $data);
    $theme->registerParser(new PostParser);

    $theme->render();

    $this->assertEquals(
      $expected, $theme->getTheme(),
      'PostParser::render did not render the {block:Posts} block correctly'
    );
  }

  /**
   * @test
   * @covers  Themer\Theme\Parser\PostParser::renderPostIndex
   */
  public function renders_post_index_blocks()
  {
    // Empty data will work fine here, we just need 2 "posts"
    $content = <<<BLOCK
{block:Post1}{/block:Post1}
{block:Post2}{/block:Post2}
{block:Odd}{/block:Odd}
{block:Even}{/block:Even}
BLOCK;

    $block = new BlockParser($content);
    $post = new PostParser;
    $post->renderPostIndex($block, 1);

    $this->assertNotContains(
      "{block:Post1}{/block:Post1}", $block->getBlock(),
      'PostParser::renderPostIndexes should have rendered {block:Post1}'
    );

    $this->assertNotContains(
      "{block:Odd}{/block:Odd}", $block->getBlock(),
      'PostParser::renderPostIndexes should have rendered {block:Odd}'
    );

    $this->assertContains(
      "{block:Post2}{/block:Post2}", $block->getBlock(),
      'PostParser::renderPostIndexes should not have rendered {block:Post2}'
    );

    $this->assertContains(
      "{block:Even}{/block:Even}", $block->getBlock(),
      'PostParser::renderPostIndexes should not have rendered {block:Even}'
    );
  }

  /**
   * @test
   * @covers  Themer\Theme\Parser\PostParser::renderPost
   */
  public function renders_single_posts()
  {
    $data = array(
      'PostType' => 'chat',
      'Lines'    => array(
        array('Label' => 'Braden', 'Line' => 'Hello, World!'),
        array('Label' => 'World',  'Line' => 'Hello, Braden!'),
      ),
      'Title' => "A quick one while she's away",
      'Time'  => mktime(1, 1, 1, 1, 1, 2012)
    );

    $content = <<<BLOCK
{block:Chat}
{block:Title}{Title}{/block:Title}
{block:Lines}
{Label}: {Line}{/block:Lines}

{Year}
{/block:Chat}
BLOCK;

    $expected = <<<BLOCK
A quick one while she's away

Braden: Hello, World!
World: Hello, Braden!

2012
BLOCK;

    $block = new BlockParser($content);
    $post = new PostParser;
    $post->renderPost($block, $data);

    $this->assertEquals(
      $expected, trim($block->getBlock()),
      "PostParser::renderPost does not seem like its working"
    );
  }
  

  /**
   * @test
   * @covers  Themer\Theme\Parser\PostParser::renderTags
   */
  public function renders_tag_blocks()
  {
    $tags = array('jim', 'derrick');

    $content = <<<BLOCK
{block:Tags}
Tag:          {Tag}
URLSafeTag:   {URLSafeTag}
TagURL:       {TagURL}
TagURLChrono: {TagURLChrono}
{/block:Tags}
BLOCK;

    $expected = <<<BLOCK
Tag:          jim
URLSafeTag:   jim
TagURL:       /tagged/jim
TagURLChrono: /tagged/jim/chrono

Tag:          derrick
URLSafeTag:   derrick
TagURL:       /tagged/derrick
TagURLChrono: /tagged/derrick/chrono
BLOCK;

    $block = new BlockParser($content);
    $parser = new PostParser();
    $parser->renderTags($block, $tags);

    $this->assertEquals(
      $expected, trim($block->getBlock()),
      "PostParser::renderTags did not render the tags as expected"
    );
  }
}
/* End of file PostParserTest.php */
/* Location: ./test/Themer/Theme/Parser/PostParserTest.php */