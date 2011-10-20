<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer;

use Themer\Data;
use Symfony\Component\Yaml\Yaml;

class DataTest extends \PHPUnit_Framework_TestCase {
  
  static public $data_path = '';
  static public $defaults  = array();

  static public function setUpBeforeClass()
  {
    static::$data_path = implode(DIRECTORY_SEPARATOR, array(
      THEMER_BASEPATH, 'data'
    ));
    
    static::$defaults = Yaml::parse(static::$data_path . DIRECTORY_SEPARATOR . 'defaults.yml');
  }

  /**
   * @test
   * @covers  Themer\Data::__construct
   * @covers  Themer\Data::addPath
   */
  public function adds_correct_default_path_in_constructor()
  {
    $data = new Data;

    $this->assertAttributeEquals(
      array(static::$data_path),
      'paths',
      $data
    );
  }

  /**
   * @test
   * @covers  Themer\Data::load
   * @covers  Themer\Data::getData
   */
  public function loads_data()
  {
    $data = new Data;
    $data->load('defaults.yml');

    $this->assertEquals(
      static::$defaults, $data->getData(),
      "Themer\Data::__construct did not load the 'defaults.yml' correctly."
    );

    return $data;
  }

  /**
   * @test
   * @covers  Themer\Data::isLoaded
   *
   * @depends loads_data
   */
  public function caches_previously_loaded_files($data)
  { 
    $this->assertTrue(
      $data->isLoaded(static::$data_path . DIRECTORY_SEPARATOR . 'defaults.yml')
    );

    return $data;
  }
  
  /**
   * @test
   * @covers  Themer\Data::offsetSet
   * @covers  Themer\Data::offsetGet
   * @covers  Themer\Data::offsetExists
   * @covers  Themer\Data::offsetUnset
   *
   * @depends caches_previously_loaded_files
   */
  public function is_array_accessible($data)
  {
    // Themer\Data::offsetGet
    $this->assertEquals(
      static::$defaults['Title'], $data['Title'],
      "Themer\Data::offsetGet did not return the expected value for 'Title'."
    );

    // Themer\Data::offsetSet
    $data['new-index'] = 'Hello World!';

    $this->assertContains(
      array('new-index' => 'Hello World!'), $data->getData(),
      "Themer\Data::offsetSet did not set the correct value for 'new-index'."
    );

    // Themer\Data::offsetUnset
    // Themer\Data::offsetExists
    unset($data['new-index']);

    $this->assertFalse(
      isset($data['new-index']),
      "Themer\Data::offsetUnset did not unset the index 'new-index' correctly."
    );

    // Themer\Data::offsetGet
    //    $value = $data['undefined'];
    $this->assertNull(
      $data['undefined'],
      'Themer\Data::offsetGet should return NULL for undefined offsets.'
    );

    // Themer\Data::offsetSet
    //    $data[] = 'value';
    $this->setExpectedException(
      'InvalidArgumentException',
      'Themer does not allow pushing data to the Data class as an array.'
    );

    $data[] = 'value';
  }

  /**
   * @test
   * @covers  Themer\Data::load
   */
  public function throws_an_exception_when_loading_non_existent_data_files()
  {
    $this->setExpectedException(
      'InvalidArgumentException', 'Data file not found'
    );

    $data = new Data;
    $data->load('some-fake-file.yml');
  }

  /**
   * @test
   * @covers  Themer\Data::loadLang
   */
  public function loads_language_files()
  {
    $data = new Data();
    $data->loadLang('en');

    $expected = Yaml::parse(implode(DIRECTORY_SEPARATOR, array(
      static::$data_path, 'locales', 'en.yml'
    )));

    $this->assertEquals(
      $expected, $data['lang'],
      'Themer\Data::loadLang did not load the expected language.'
    );
  }

  /**
   * @test
   * @covers  Themer\Data::loadLang
   */
  public function throws_an_exception_when_loading_non_existent_language_files()
  {
    $this->setExpectedException(
      'InvalidArgumentException', 'Language is not supported'
    );

    $data = new Data;
    $data->loadLang('some-fake-file');
  }
}
/* End of file DataTest.php */
/* Location: ./test/Themer/DataTest.php */