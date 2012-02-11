<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer\Utils;

class PathnameTest extends \PHPUnit_Framework_TestCase {

  const DS = DIRECTORY_SEPARATOR;

  public static function getPath($parts)
  {
    return implode(self::DS, func_get_args());
  }

  // --------------------------------------------------------------------

  /**
   * @test
   * @covers  Themer\Utils\Pathname::build
   */
  public function build()
  {
    $parts = array(
       self::DS . 'some',
       'long', 
       self::DS,
       'path' . self::DS
     );

    $pathname = Pathname::build($parts);

    $path = self::DS . self::getPath('some', 'long', 'path');

    $this->assertEquals(
      $path, $pathname->getPath(),
      "::build() did not build the correct path."
    );

    $this->assertEquals(
      self::DS, $pathname->getRoot(),
      "::build() did not build the path with the correct root."
    );
  }

  /**
   * @test
   * @covers  Themer\Utils\Pathname::__construct
   * @covers  Themer\Utils\Pathname::__toString
   * @covers  Themer\Utils\Pathname::getPath
   */
  public function path_string()
  {
    $path = self::getPath('some', 'path');
    $pathname = new Pathname($path);

    $this->assertEquals(
      $path, $pathname->getPath(),
      "::getPath() did not return the expected path string."
    );

    $this->assertEquals(
      $path, (string) $pathname,
      "::__toString() did not return the expected path string."
    );
  }

   /**
   * @test
   * @covers  Themer\Utils\Pathname::__construct
   */
  public function constructor_removes_trailing_slash()
  {
    $path = self::getPath('some', 'path');
    $pathname = new Pathname($path . self::DS);

    $this->assertEquals(
      $path, $pathname->getPath(),
      "::__construct() did not remove the trailing '" . self::DS . "'."
    );
  }

  /**
   * @test
   * @covers  Themer\Utils\Pathname::__construct
   * @covers  Themer\Utils\Pathname::getRoot
   */
  public function constructor_sets_root()
  {
    $path = self::DS . self::getPath('some', 'path');
    $pathname = new Pathname($path);

    $this->assertEquals(
      self::DS, $pathname->getRoot(),
      '::__construct() did not set the root correctly.'
    );
  }

  /**
   * @test
   * @covers  Themer\Utils\Pathname::ascend
   */
  public function ascend()
  {
    $expected = array(
      self::DS . self::getPath('some', '..', 'dir'),
      self::DS . self::getPath('some', '..'),
      self::DS . self::getPath('some'),
      self::DS
    );

    $pathname = new Pathname($expected[0]);
    $actual = array_map(function ($item) {
      return $item->getPath();
    }, $pathname->ascend());

    $this->assertEquals(
      $expected, $actual,
      '::ascend() did not provide the correct array of Pathname obejcts.'
    );
  }

  /**
   * @test
   * @covers  Themer\Utils\Pathname::join
   */
  public function join()
  {
    $path = self::getPath('some', 'path');
    $pathname = new Pathname($path);

    $joined = $pathname->join(array('add', self::DS, 'this', 'too' . self::DS));

    $expected = self::getPath('some', 'path', 'add', 'this', 'too');
    
    $this->assertEquals(
      $expected, $joined->getPath(),
      '::join() did not join paths correctly.'
    );
  }

  // --------------------------------------------------------------------

  public function sliceProvider()
  {
    $path = self::getPath('some', 'much', 'long', 'er', 'path');

    return array(
      array(
        $path,                          // the original path
        2,                              // where to slice
        self::getPath('some', 'much')   // the expected path
      ),
      array(
        self::DS . $path,
        1,
        self::DS . 'some'
      )
    );
  }

  /**
   * @test
   * @covers  Themer\Utils\Pathname::slice
   *
   * @dataProvider  sliceProvider
   */
  public function slice($original, $slice, $expected)
  {
    $pathname = new Pathname($original);

    $this->assertEquals(
      $expected, (string) $pathname->slice($slice),
      "::slice() did not slice the path correctly."
    );
  }

  // --------------------------------------------------------------------

  public function rootProvider()
  {
    $path = self::getPath('some', 'path');

    return array(
      array(
        '/',     // the directory separator to use
        $path,   // the path to use
        ''       // the root
      ),
      array('/', '/' . $path, '/'),

      array('\\', $path, ''),
      array('\\', '\\' . $path, '\\'),
      array('\\', "C:\\" . $path, "C:\\")
    );
  }

  /**
   * @test
   * @covers  Themer\Utils\Pathname::extractRoot
   *
   * @dataProvider  rootProvider
   */
  public function extractRoot($ds, $path, $expected)
  {
    $root = Pathname::extractRoot($path, $ds);

    $this->assertEquals(
      $expected, $root,
      '::extractRoot() did not extract the root correctly.'
    );
  }
}
/* End of file BlockTest.php */
/* Location: ./test/Themer/BlockTest.php */