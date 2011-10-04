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
  
  private static $_temp_themes = array();

  private function _createThemeFile($contents)
  {
    $tmp = tempnam(sys_get_temp_dir(), 'Themer-PHPUnit-TestTheme');
    $handle = fopen($tmp, 'w');
    fwrite($handle, $contents);
    fclose($handle);
    static::$_temp_themes[] = $tmp;
    return $tmp;
  }

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
    $tmp_theme = $this->_createThemeFile($contents);
    
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
    $this->setExpectedException('InvalidArgumentException');

    new Theme('invalid-file-090980.html');
  }
  
  /**
   * @test
   * @covers  Themer\Theme::__construct
   */
  public function throws_exception_for_non_readable_file()
  {
    $this->setExpectedException('InvalidArgumentException');

    $tmp_theme = $this->_createThemeFile("Non readable theme.");
    chmod($tmp_theme, 0000);

    new Theme($tmp_theme);
  }

  /**
   * @test
   * @covers  Themer\Theme::getData
   */
  public function enables_access_to_the_data_object()
  {
    $theme = new Theme("Some theme\n");
    
    $this->assertInstanceOf(
      'Themer\\Data', $theme->getData(),
      'Theme::getData did not return a Themer\\Data instance.'
    );
  }

  /**
   * @test
   * @covers  Themer\Theme::__toString
   * @covers  Themer\Theme::getTheme
   * @covers  Themer\Theme::setTheme
   * @covers  Themer\Theme::getOriginal
   */
  public function manages_theme_content()
  {
    $original_content = "This should be the current content.\n";
    $theme = new Theme($original_content);
    
    $this->assertEquals(
      $original_content, "$theme",
      'Theme::__toString did not return the correct content.'
    );
    
    $this->assertEquals(
      $original_content, $theme->getTheme(),
      'Theme::getTheme did not return the correct content.'
    );
    
    $new_content = "This should be the new content!";
    $theme->setTheme($new_content);
    
    $this->assertEquals(
      $new_content, $theme->getTheme(),
      'Theme::setTheme did not correctly set the theme content.'
    );
    
    $this->assertEquals(
      $theme->getOriginal(), $original_content,
      'Theme::getOriginal did not return the original theme content.'
    );
  }
  
  /**
   * @test
   * @covers  Themer\Theme::renderVariable
   */
  public function renders_variables()
  {
    $content = "{variable}\n";
    $expected = "Tumblr is awesome!\n";
    
    $theme = new Theme($content);
    $theme->renderVariable('variable', 'Tumblr is awesome!');
    
    $this->assertEquals(
      $expected, $theme->getTheme(),
      'Theme::renderVariable did not render the tag {variable} correctly.'
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
    $content = "{variable}\n";
    $expected = "It works!\n";
    $theme = new Theme("{variable}\n");
    
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