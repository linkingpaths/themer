<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */

use Symfony\Component\Yaml\Yaml; 
 
class LocalesTest extends PHPUnit_Framework_TestCase {

  static public $indexes = array();

  static public function setUpBeforeClass()
  {
    static::$indexes = Yaml::parse(__DIR__.'/indexes.yml');
  }

  // --------------------------------------------------------------------

  /**
  * @test
  */
  public function english()
  {
    $this->runLocaleTest('en');
  }

  // --------------------------------------------------------------------

  private function getLocale($locale)
  {
    $file =  THEMER_BASEPATH."/data/locales/$locale.yml";
    return Yaml::parse($file);
  }

  private function runLocaleTest($locale)
  {
    $data = $this->getLocale($locale);
    $keys = array_keys($data);

    foreach (static::$indexes as $index)
    {
      $this->assertContains(
        $index, $keys,
        "The locales '$locale.yml' did not contain the index '$index'."
      );
    }
  }
}
/* End of file LocalesTest.php */
/* Location: ./test/Themer/data/locales/LocalesTest.php */