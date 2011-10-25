<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer\Helper;

/**
 * A helper class for rendering Tumblr timestamps
 */
class Time {
  
  static protected $tags = array(
    'DayOfMonth'          => 'j',
    'DayOfMonthWithZero'  => 'd',
    'DayOfWeek'           => 'l',
    'ShortDayOfWeek'      => 'D',
    'DayOfWeekNumber'     => 'N',
    'DayOfMonthSuffix'    => 'S',
    'DayOfYear'           => 'z',
    'WeekOfYear'          => 'W',
    'Month'               => 'F',
    'ShortMonth'          => 'M',
    'MonthNumber'         => 'n',
    'MonthNumberWithZero' => 'm',
    'Year'                => 'Y',
    'ShortYear'           => 'y',
    'AmPm'                => 'a',
    'CapitalAmPm'         => 'A',
    '12Hour'              => 'g',
    '24Hour'              => 'G',
    '12HourWithZero'      => 'h',
    '24HourWithZero'      => 'H',
    'Minutes'             => 'i',
    'Seconds'             => 'j',
    'Beats'               => 'B',
    'Timestamp'           => 'U',
    'TimeAgo'             => array(__CLASS__, 'relative')
  );

  /**
   * Returns a single date formatted string for a single Tumblr date tag.
   *
   * @access  public
   * @param   integer   the timestamp
   * @param   string    the requested timestamp
   * @return  string    the timestamp formatted as requested
   */
  static public function getTag($timestamp, $tag)
  {
    if ( ! isset(static::$tags[$tag]))
    {
      throw new \InvalidArgumentException("Invalid time/date tag: {$tag}");
    }

    if (is_array(static::$tags[$tag]))
    {
      return call_user_func(static::$tags[$tag], $timestamp, time());
    }
    else
    {
      return date(static::$tags[$tag], $timestamp);
    }
  }

  /**
   * Converts a Unix timestamp to an array of all possible Tumblr date
   * formatted string.
   *
   * @static
   * @access  public
   * @param   integer   the timestamp
   * @return  array     an array of date formatted tags => values
   */
  static public function getTags($timestamp)
  {
    $tags = array();

    foreach (static::$tags as $tag => $formatter)
    {
      $tags[$tag] = self::getTag($timestamp, $tag);
    }

    return $tags;
  }

  /**
   * Returns a relative time string
   * 
   * @access  public
   * @return  void
   */
  static public function relative($timestamp, $compare = NULL)
  {
    $plural = function ($diff) {
      return ($diff != 1) ? 's' : '';
    };

    $diff = (is_null($compare) ? time() : $compare) - $timestamp;
    
    if ($diff < 60)
    {
      return $diff . " second" . $plural($diff) . " ago";
    }
    
    $diff = round($diff / 60);
    
    if ($diff < 60)
    {
      return $diff . " minute" . $plural($diff) . " ago";
    }

    $diff = round($diff / 60);
    
    if ($diff < 24)
    {
      return $diff . " hour" . $plural($diff) . " ago";
    }
     
    $diff = round($diff / 24);
    
    if ($diff < 7)
    {
      return $diff . " day" . $plural($diff) . " ago";
    }

    $diff = round($diff / 7);
    
    if ($diff < 4)
    {
      return $diff . " week" . $plural($diff) . " ago";
    }

    return "on " . date("F j, Y", $timestamp);
  }
}
/* End of file Time.php */
/* Location: ./path/to/file/Time.php */