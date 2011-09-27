<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
 
use Themer\Variable;

class VariableTest extends PHPUnit_Framework_TestCase {

  /**
   * @test
   * @covers  Themer\Variable::render
   * @covers  Themer\Variable::_simple
   */
  public function renders_variables_correctly()
  {
    $search = 'variable';
    $replace = 'Hello World!';

    $this->assertEquals(
      'Hello World!', Variable::render('{variable}', $search, $replace),
      'Variable::render did not render the tag "{variable}" correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Variable::render
   * @covers  Themer\Variable::_simple
   * @covers  Themer\Variable::_plaintext
   */
  public function renders_Plaintext_tags_correctly()
  {
    $search = 'variable';
    $replace = '© "Braden & Schaeffer &copy;';

    $parsed = htmlspecialchars($replace);

    $this->assertEquals(
      $parsed, Variable::render('{Plaintextvariable}', $search, $replace),
      'Variable::render did not render the tag "{plaintext:variable}" correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Variable::render
   * @covers  Themer\Variable::_simple
   * @covers  Themer\Variable::_js
   */
  public function renders_JS_tags_correctly()
  {
    $search = 'variable';
    $replace = '{Hello World!}';

    $parsed = json_encode($replace);

    $this->assertEquals(
      $parsed, Variable::render('{JSvariable}', $search, $replace),
      'Variable::render did not render the tag "{plaintext:variable}" correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Variable::render
   * @covers  Themer\Variable::_simple
   * @covers  Themer\Variable::_jsplaintext
   */
  public function renders_JSPlaintext_tags_correctly()
  {
    $search = 'variable';
    $replace = '&quot; "{This needs better testing!}';

    $parsed = json_encode(htmlspecialchars($replace));

    $this->assertEquals(
      $parsed, Variable::render('{JSPlaintextvariable}', $search, $replace),
      'Variable::render did not render the tag "{plaintext:variable}" correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Variable::render
   * @covers  Themer\Variable::_simple
   * @covers  Themer\Variable::_urlencoded
   */
  public function renders_URLEncoded_tags_correctly()
  {
    $search = 'variable';
    $replace = '&quot; "{This needs better testing!}';

    $parsed = urlencode($replace);

    $this->assertEquals(
      $parsed, Variable::render('{URLEncodedvariable}', $search, $replace),
      'Variable::render did not render the tag "{plaintext:variable}" correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Variable::render
   * @covers  Themer\Variable::_simple
   * @covers  Themer\Variable::_plaintext
   * @covers  Themer\Variable::_js
   * @covers  Themer\Variable::_jsplaintext
   * @covers  Themer\Variable::_urlencoded
   */
  public function renders_multiple_variable_occurrences_in_a_given_block()
  {
    $block = '';
    $value = "& Themer is a PHP project%!}";

    $search_and_replace = array(
      "{variable}"            => $value,
      "{Plaintextvariable}"   => htmlspecialchars($value),
      "{JSVariable}"          => json_encode($value),
      "{JSPlaintextvariable}" => json_encode(htmlspecialchars($value)),
      "{URLEncodedvariable}"  => urlencode($value)
    );

    foreach ($search_and_replace as $k => $v)
    {
      $block .= "-".$k;
    }

    $rendered = Variable::render($block, 'variable', $value);

    foreach ($search_and_replace as $k => $expected)
    {
      $this->assertContains(
        "-".$expected, $rendered,
        "Variable::render did not render the $k tag correctly"
      );
    }
  }

  /**
   * @test
   * @covers  Themer\Variable::render
   * @covers  Themer\Variable::_simple
   */
  public function does_not_transform_non_transformable_variable_tags()
  {
    $block = <<<EOF
{variable}
{Plaintextvariable}
{JSVariable}
{JSPlaintextvariable}
{URLEncodedvariable}
EOF;

    $expected = <<<EOF
Themer
{Plaintextvariable}
{JSVariable}
{JSPlaintextvariable}
{URLEncodedvariable}
EOF;

    $this->assertEquals(
      $expected, Variable::render($block, 'variable', 'Themer', FALSE),
      'Variable::render transformed explicitly non-transformable variables'
    );
  }
}

/* End of file VariableTest.php */
/* Location: ./test/Themer/VariableTest.php */