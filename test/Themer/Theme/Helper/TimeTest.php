<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer\Theme\Helper;

class TimeTest extends \PHPUnit_Framework_TestCase {

  /**
   * @test
   * @covers  Themer\Theme\Helper\Time::getTag
   */
  public function throws_exception_for_invalid_tag()
  {
    $this->setExpectedException(
      'InvalidArgumentException', 'Invalid time/date tag:'
    );

    Time::getTag(time(), 'InvalidTag');
  }

  /**
   * @test
   * @covers  Themer\Theme\Helper\Time::getTag
   * @covers  Themer\Theme\Helper\Time::getTags
   */
  public function formats_all_possbile_tags_at_once()
  {
    $timestamp = mktime(19, 30, 0, 5, 4, 1959);

    $expected = array(
      'DayOfMonth'          => 4,
      'DayOfMonthWithZero'  => 04,
      'DayOfWeek'           => 'Monday',
      'ShortDayOfWeek'      => 'Mon',
      'DayOfWeekNumber'     => 1,
      'DayOfMonthSuffix'    => 'th',
      'DayOfYear'           => 123,
      'WeekOfYear'          => 19,
      'Month'               => 'May',
      'ShortMonth'          => 'May',
      'MonthNumber'         => 5,
      'MonthNumberWithZero' => 05,
      'Year'                => 1959,
      'ShortYear'           => 59,
      'AmPm'                => 'pm',
      'CapitalAmPm'         => 'PM',
      '12Hour'              => 7,
      '24Hour'              => 19,
      '12HourWithZero'      => 07,
      '24HourWithZero'      => 19,
      'Minutes'             => 30,
      'Seconds'             => 4,
      //'Beats'               => 051,
      'Timestamp'           => $timestamp,
      'TimeAgo'             => 'on May 4, 1959'
    );

    $tags = Time::getTags($timestamp);

    foreach ($expected as $tag => $value)
    {
      $this->assertEquals(
        $value, $tags[$tag],
        "Time::getTag did not format the tag '$tag' as expected"
      );
    }
  }

  // --------------------------------------------------------------------

  public function relativeProvider()
  {
    $now = time();

    return array(
      array($now - 10,                    $now, '10 seconds ago'),
      array($now - (60 * 10),             $now, '10 minutes ago'),
      array($now - (60 * 60 * 10),        $now, '10 hours ago'),
      array($now - (60 * 60 * 24 * 6),    $now, '6 days ago'),
      array($now - (60 * 60 * 24 * 15),   $now, '2 weeks ago'),
      array(mktime(0, 0, 0, 11, 9, 1993), $now, 'on November 9, 1993')
    );
  }

  /**
   * @test
   * @covers  Themer\Theme\Helper\Time::relative
   *
   * @dataProvider  relativeProvider
   */
  public function test($timestamp, $compare, $expected)
  {
    $this->assertEquals($expected, Time::relative($timestamp, $compare));
  }
}
/* End of file TimeTest.php */
/* Location: ./test/Themer/Theme/Helper/TimeTest.php */