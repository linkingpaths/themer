<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer;

use Themer\Theme;

class ThemeTest extends \PHPUnit_Framework_TestCase {

  // --------------------------------------------------------------------
  
  /**
   * @test
   * @covers  Themer\Theme::__construct
   */
  public function allows_strings_with_new_lines_as_theme_contents()
  {
    $contents = "This could be a theme\n";

    try
    {
      $theme = new Theme($contents);
    }
    catch (\InvalidArgumentException $e)
    {
      $this->fail('Theme::__construct should allow strings with new lines as the theme contents');
      return;
    }
  }

  /**
   * @test
   * @covers  Themer\Theme::__construct
   */
  public function reads_existing_files_as_theme_contents()
  {
    $contents = "This should be a valid theme!"; 

    $tmp_theme = tempnam(sys_get_temp_dir(), 'Themer-PHPUnit-TestTheme');
    $handle = fopen($tmp_theme, 'w');
    fwrite($handle, $contents);
    fclose($handle);
    
    try
    {
      $theme = new Theme($tmp_theme);
      unlink($tmp_theme);
    }
    catch (\InvalidArgumentException $e)
    {
      $this->fail('Theme::__construct should allow valid files as theme contents.');
      unlink($tmp_theme);
    }
  }

  /**
   * @test
   * @covers  Themer\Theme::__construct
   */
  public function throws_exception_for_non_existent_file()
  {
    $this->setExpectedException(
      'InvalidArgumentException', 'Theme file is invalid'
    );

    new Theme('invalid-file-090980.html');
  }
  
  /**
   * @test
   * @covers  Themer\Theme::__construct
   */
  public function throws_exception_for_non_readable_file()
  {
    $this->setExpectedException(
      'InvalidArgumentException', 'Theme file is invalid'
    );

    new Theme("invalid-protocol://");
  }

  /**
   * @test
   * @covers  Themer\Theme::__construct
   * @covers  Themer\Theme::getData
   */
  public function enables_access_to_the_data_object()
  {
    $data  = new Data;
    $theme = new Theme("Some theme\n");

    $this->assertEquals(
      $data, $theme->getData(),
      'Theme::__construct did not register the user-supplied data object.'
    );

    $this->assertInstanceOf(
      'Themer\\Data', $theme->getData(),
      'Theme::getData did not return a Themer\\Data instance.'
    );
  }

  /**
   * @test
   * @covers  Themer\Theme::getTheme
   * @covers  Themer\Theme::setTheme
   */
  public function manages_theme_content()
  {
    $original = "This should be the current content.\n";
    $theme = new Theme($original);
    
    $this->assertEquals(
      $original, $theme->getTheme(),
      'Theme::getTheme did not return the correct content.'
    );
    
    $new = "This should be the new content!";
    $theme->setTheme($new);
    
    $this->assertEquals(
      $new, $theme->getTheme(),
      'Theme::setTheme did not correctly set the theme content.'
    );
  }

  /**
   * @test
   * @covers  Themer\Theme::registerParsers
   * @covers  Themer\Theme::registerParser
   */
  public function registers_parsers()
  {
    $theme = new Theme("Some theme\n");
    
    $mock_parser = $this->getMock('Themer\\Parser\\BaseParser', array('render', 'preload'));
    $mock_parser->expects($this->exactly(2))
                ->method('preload')
                ->with($theme->getData());
                
    $theme->registerParsers(array(
      $mock_parser,
      $mock_parser
    ));
    
    return $theme;
  }

  /**
   * @test
   * @covers  Themer\Theme::registerParser
   * @covers  Themer\Theme::render
   * @covers  Themer\Theme::renderVariable
   */
  public function renders_a_theme()
  {
    $content = "{variable}{block:ShouldBeCleaned}{/block:ShouldBeCleaned}\n";
    $expected = "It works!\n";

    $theme = new Theme($content);
    
    $mock_parser = $this->getMock('Themer\\Parser\\BaseParser', array('render'));
    $mock_parser->expects($this->any())
                ->method('render')
                ->with($theme, $theme->getData())
                ->will($this->returnCallback(function () use ($theme) {
                  $theme->renderVariable('variable', 'It works!');
                }));
    
    $theme->registerParser($mock_parser);
    $theme->render();
    
    $this->assertEquals(
      $expected, $theme->render(),
      "Theme::render did not render the theme as expected."
    );
  } 
}
/* End of file ThemeTest.php */
/* Location: ./test/Themer/ThemeTest.php */