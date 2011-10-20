<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer\Parser;

class TemplateParserTest extends \PHPUnit_Framework_TestCase {

  /**
   * @test
   * @covers  Themer\Parser\TemplateParser
   */
  public function renders_templates_correctly()
  {
    $content = <<<BLOCK
{variable}

BLOCK;

    $data = array(
      'A haiku for you',
      'Something, Something, the darkside',
      'Better safe than sound'
    );

    $expected = <<<BLOCK
{$data[0]}
{$data[1]}
{$data[2]}

BLOCK;

    $template = new TemplateParser($content);

    foreach ($data as $line)
    {
      $template->renderVariable('variable', $line);
      $template->next();
    }

    $this->assertEquals(
      $expected, $template->getTemplate(),
      "TemplateParser did not render the given block template correctly"
    );
  }
}
/* End of file TemplateParserTest.php */
/* Location: ./test/Themer/Parser/TemplateParserTest.php */