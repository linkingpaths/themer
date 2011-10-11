<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer\Parser;

use Themer\Data;
use Themer\Theme;

class LanguageParserTest extends \PHPUnit_Framework_TestCase {

  /**
   * @test
   * @covers  Themer\Parser\LanguageParser::__construct
   * @covers  Themer\Parser\LanguageParser::setLocale
   * @covers  Themer\Parser\LanguageParser::getLocale
   */
  public function locale_can_be_set_by_the_user()
  {
    $lang = new LanguageParser('some-locale');

    $this->assertEquals(
      'some-locale', $lang->getLocale(),
      'LanguageParser::__construct did not set the given locale correctly.'
    );

    $lang->setLocale('another-locale');

    $this->assertEquals(
      'another-locale', $lang->getLocale(),
      'LanguageParser::setLocale did not set the given locale correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Parser\LanguageParser::preload
   */
  public function preloads_a_given_locale()
  {
    $mData = $this->getMock('Themer\Data', array('loadLang'));
    $mData->expects($this->once())
         ->method('loadLang')
         ->with($this->equalTo('some-locale'));


    $theme = new Theme("some theme data\n", $mData);
    $theme->registerParser(new LanguageParser('some-locale'));
  }

  /**
   * @test
   * @covers  Themer\Parser\LanguageParser::render
   */
  public function renders_themes_correctly()
  {
    $lang = new LanguageParser('en');

    $theme = new Theme("{lang:About}\n");
    $theme->registerParser($lang);

    $data = $theme->getData();

    $this->assertContains(
      $data['lang']['About'], $theme->render(),
      "LanguageParser::render did not render the tag '{lang:About}' correctly."
    );
  }
}
/* End of file LanguageParserTest.php */
/* Location: ./test/Themer/Parser/LanguageParserTest.php */