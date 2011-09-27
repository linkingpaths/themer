<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */

use Symfony\Component\Yaml\Yaml; 
 
class Locales_Test extends PHPUnit_Framework_TestCase {

  static public $indexes = array();

  static public function setUpBeforeClass()
  {
    static::$indexes = Yaml::parse(__DIR__.'/indexes.yml');
  }

  // --------------------------------------------------------------------

  public function localesProvider()
  {
    $locales = array();
 
    foreach (glob(THEMER_BASEPATH.'/data/locales/*.yml') as $file)
    {
      array_push($locales, array(basename($file, '.yml'), $file));
    }
    
    return $locales;
  }

  /**
  * @test
  * @dataProvider  localesProvider
  */
  public function locales_contain_correct_indexes($locale, $file)
  {
    $data = Yaml::parse($file);
    $keys = array_keys($data);
    
    foreach (static::$indexes as $index)
    {
      $this->assertContains(
        $index, $keys,
        "The locale file '$locale.yml' did not contain the expected key: $index."
      );
    }
  }
}
/* End of file Locales_Test.php */
/* Location: ./test/Themer/data/locales/Locales_Test.php */