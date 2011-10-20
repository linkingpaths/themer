<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer\Parser;

class BlockParserTest extends \PHPUnit_Framework_TestCase {

  /**
   * @test
   * @covers  Themer\Parser\BlockParser::__construct
   * @covers  Themer\Parser\BlockParser::__toString
   * @covers  Themer\Parser\BlockParser::getBlock
   * @covers  Themer\Parser\BlockParser::setBlock
   * @covers  Themer\Parser\BlockParser::getOriginal
   */
  public function manages_block_content()
  {
    $original = 'Some content.';
    $block = new BlockParser($original);

    $this->assertEquals(
      $original, "$block",
      'BlockParser::__toString did not return the correct content.'
    );

    $this->assertEquals(
      $original, $block->getBlock(),
      'Block::getBlock did not return the correct content.'
    );

    $new = "New content.";
    $block->setBlock($new);

    $this->assertEquals(
      $new, $block->getBlock(),
      'Block::setBlock did not correctly set the block content.'
    );

    $this->assertEquals(
      $original, $block->getOriginal(),
      'BlockParser::getOriginal did not return the original block content.'
    );
  }

  /**
   * @test
   * @covers  Themer\Parser\BlockParser::renderVariable
   */
  public function renders_variables()
  {
    $content = "{variable}";
    $expected = "Tumblr is awesome!";
    
    $block = new BlockParser($content);
    $block->renderVariable('variable', 'Tumblr is awesome!');
    
    $this->assertEquals(
      $expected, $block->getBlock(),
      'BlockParser::renderVariable did not render the tag {variable} correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Parser\BlockParser::renderVariables
   */
  public function renders_an_array_of_variables()
  {
    $content = "{greeting}, {name}!";
    $expected = "Hello, World!";
    $data = array(
      'greeting' => 'Hello',
      'name'     => 'World',
    );

    $block = new BlockParser($content);
    $block->renderVariables($data);

    $this->assertEquals(
      $expected, $block->getBlock(),
      'BlockParser::renderVariables did not render an array of variables correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Parser\BlockParser::renderBlock
   */
  public function renders_blocks_the_way_they_did_when_I_was_your_age()
  {
    $content = <<<BLOCK
{block:TestBlock}Hello{/block:TestBlock}
{block:TestBlock}World{/block:TestBlock}
BLOCK;

    $expected = <<<BLOCK
Hello
World
BLOCK;

    $block = new BlockParser($content);
    $block->renderBlock('TestBlock');

    $this->assertEquals(
      $expected, $block->getBlock(),
      'BlockParser::renderBlock did not render the given blocks correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Parser\BlockParser::renderBlock
   */
  public function runs_callbacks_when_rendering_blocks()
  {
    $mCallback = $this->getMock('StdClass', array('render'));
    $mCallback->expects($this->exactly(2))
              ->method('render')
              ->with($this->equalTo('We made it!'));

    $content = <<<BLOCK
{block:TestBlock}{/block:TestBlock}
{block:TestBlock}{/block:TestBlock}
BLOCK;

    $block = new BlockParser($content);
    $block->renderBlock('TestBlock', function ($block) use ($mCallback) {
      $mCallback->render('We made it!');
    });
  }

  /**
   * @test
   * @covers  Themer\Parser\BlockParser::renderEach
   */
  public function renders_multiple_sets_of_data_for_one_block_template()
  {
    $data = array(
      array('Name' => 'John'),
      array('Name' => 'Paul'),
      array('Name' => 'George'),
      array('Name' => 'Ringo')
    );

    $content = <<<BLOCK
{block:Test}
{Name}{/block:Test}
BLOCK;

    $expected = <<<BLOCK

John
Paul
George
Ringo
BLOCK;

    $block = new BlockParser($content);
    $block->renderEach('Test', $data);

    $this->assertEquals(
      $expected, $block->getBlock(),
      'BlockParser::renderEach did not render each set of data for each found template'
    );
  }

  /**
   * @test
   * @covers  Themer\Parser\BlockParser::renderTemplate
   */
  public function renders_block_templates()
  {
    $content = <<<BLOCK
My Page Title

{block:Template}
{variable}{/block:Template}
BLOCK;

    $data = array('Good paragraph.', 'Great paragraph!');

    $expected = <<<BLOCK
My Page Title


{$data[0]}
{$data[1]}
BLOCK;

    $block = new BlockParser($content);
    $block->renderTemplate('Template', function ($template) use ($data)
    {
      foreach ($data as $p)
      {
        $template->renderVariable('variable', $p);
        $template->next();
      }
    });

    $this->assertEquals(
      $expected, $block->getBlock(),
      'BlockParser::renderTemplate did not render the given template correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Parser\BlockParser::replace
   */
  public function replaces_strings_correctly()
  {
    $content  = 'Line: Hello, Dolly!';
    $expected = 'Line: Bye, Bye, Bird-he...';

    $block = new BlockParser($content);
    $block->replace('Hello, Dolly!', 'Bye, Bye, Bird-he...');

    $this->assertEquals(
      $expected, $block->getBlock(),
      "BlockParser::replace did not replace the given strings correctly."
    );
  }
}
/* End of file BlockParserTest.php */
/* Location: ./test/Themer/Parser/BlockParserTest.php */