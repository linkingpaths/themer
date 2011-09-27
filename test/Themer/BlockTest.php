<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
 
use Themer\Block;

class BlockTest extends PHPUnit_Framework_TestCase {

  /**
   * @test
   * @covers  Themer\Block::render
   * @covers  Themer\Block::getMatcher
   */
  public function renders_specified_blocks()
  {
    $block = "{block:foo}Hello World!{/block:foo}";

    $this->assertEquals(
      'Hello World!', Block::render($block, 'foo'),
      'Block::render did not render the tag "{block:foo}" correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Block::render
   * @covers  Themer\Block::getMatcher
   */
  public function does_not_render_unspecified_blocks()
  {
    $block = "{block:foo}Hello World!{/block:foo}";

    $this->assertEquals(
      $block, Block::render($block, 'bar'),
      'Block::render rendered the tag "{block:foo}" when it should not have.'
    );
  }

  /**
   * @test
   * @covers  Themer\Block::renderIf
   * @covers  Themer\Block::formatIfBlockTag
   * @covers  Themer\Block::remove
   * @covers  Themer\Block::render
   */
  public function renders_if_blocks()
  {
    $if     = "{block:Iffoo}If{/block:Iffoo}";
    $ifnot  = "{block:IfNotfoo}IfNot{/block:IfNotfoo}";

    $this->assertEquals(
      'If', Block::renderIf($if.$ifnot, 'foo', TRUE),
      'Block::renderIf did not render the "If" block correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Block::renderIf
   * @covers  Themer\Block::formatIfBlockTag
   * @covers  Themer\Block::remove
   * @covers  Themer\Block::render
   */
  public function renders_if_not_blocks()
  {
    $if     = "{block:Iffoo}If{/block:Iffoo}";
    $ifnot  = "{block:IfNotfoo}IfNot{/block:IfNotfoo}";

    $this->assertEquals(
      'IfNot', Block::renderIf($if.$ifnot, 'foo', FALSE),
      'Block::renderIf did not render the "IfNot" block correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Block::cleanup
   */
  public function cleanup_removes_all_valid_blocks()
  {
    $block = "{block:foo}foo{/block:foo}{block:bar}bar{/block:bar}";

    $this->assertEmpty(
      Block::cleanup($block),
      'Block::cleanup did not remove all of the blocks correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Block::remove
   * @covers  Themer\Block::find
   */
  public function removes_specified_blocks()
  {
    $foo = "{block:foo}foo{/block:foo}";
    $bar = "{block:bar}bar{/block:bar}";

    $this->assertEquals(
      $foo, Block::remove($foo.$bar, 'bar'),
      'Block::remove did not remove the tag "{block:bar}" correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Block::find
   * @covers  Themer\Block::getMatcher
   */
  public function finds_specified_blocks()
  {
    $foo = "{block:find}foo{/block:find}";
    $bar = "{block:find}bar{/block:find}";
    
    $results = Block::find($foo.$bar, 'find');

    $this->assertContains(
      $foo, $results,
      'Block::find did not find the block "{block:find}foo{/block:find}" correctly.'
    );
    
    $this->assertContains(
      $bar, $results,
      'Block::find did not find the block "{block:find}bar{/block:find}" correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Block::find
   * @covers  Themer\Block::getMatcher
   */
  public function finds_with_no_results_returns_an_array()
  {
    $this->assertEquals(
      array(), Block::find('', 'nonexistent'),
      'Block::find did not return an empty array() for a non-existent block.'
    );
  }

  /**
   * @test
   * @covers  Themer\Block::formatIfBlockTag
   */
  public function formats_if_block_tags_correctly()
  {
    $tag = "I_ Should HaveNo Spaces Or _'s";
    $expected = "IShouldHaveNoSpacesOr's";

    $this->assertEquals(
      $expected, Block::formatIfBlockTag($tag),
      'Block::formatIfBlockTag did not format the tag correctly.'
    );
  }
}

/* End of file BlockTest.php */
/* Location: ./test/Themer/BlockTest.php */