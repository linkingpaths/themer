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
   * @covers  Themer\Variable::renderSimple
   */
  public function renders_variables_correctly()
  {
    $search = 'variable';
    $replace = 'Hello World!';

    $this->assertEquals(
      'Hello World!', Variable::renderSimple('{variable}', $search, $replace),
      'Variable::render did not render the tag "{variable}" correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Variable::renderPlaintext
   */
  public function renders_Plaintext_tags_correctly()
  {
    $search = 'variable';
    $replace = '© "Braden & Schaeffer &copy;';

    $parsed = htmlspecialchars($replace);

    $this->assertEquals(
      $parsed, Variable::renderPlaintext('{Plaintextvariable}', $search, $replace),
      'Variable::render did not render the tag "{Plaintextvariable}" correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Variable::renderJS
   */
  public function renders_JS_tags_correctly()
  {
    $search = 'variable';
    $replace = '{Hello World!}';

    $parsed = json_encode($replace);

    $this->assertEquals(
      $parsed, Variable::renderJS('{JSvariable}', $search, $replace),
      'Variable::renderJS did not render the tag "{JSvariable}" correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Variable::renderJSPlaintext
   */
  public function renders_JSPlaintext_tags_correctly()
  {
    $search = 'variable';
    $replace = '&quot; "{This needs better testing!}';

    $parsed = json_encode(htmlspecialchars($replace));

    $this->assertEquals(
      $parsed, Variable::renderJSPlaintext('{JSPlaintextvariable}', $search, $replace),
      'Variable::render did not render the tag "{JSPlaintextvariable}" correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Variable::renderURLEncoded
   */
  public function renders_URLEncoded_tags_correctly()
  {
    $search = 'variable';
    $replace = '&quot; "{This needs better testing!}';

    $parsed = urlencode($replace);

    $this->assertEquals(
      $parsed, Variable::renderURLEncoded('{URLEncodedvariable}', $search, $replace),
      'Variable::render did not render the tag "{URLEncodedvariable}" correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Variable::render
   * @covers  Themer\Variable::renderSimple
   * @covers  Themer\Variable::renderPlaintext
   * @covers  Themer\Variable::renderJS
   * @covers  Themer\Variable::renderJSPlaintext
   * @covers  Themer\Variable::renderURLEncoded
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
   * @covers  Themer\Variable::renderSimple
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