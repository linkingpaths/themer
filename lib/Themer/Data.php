<?php
/**
 * @package   Themer
 * @author    Braden Schaeffer
 * @link      http://github.com/tmbly/themer
 * @license   http://www.opensource.org/licenses/mit-license.html MIT
 */
namespace Themer;

/**
 * Themer's data handling class.
 */
class Data implements ArrayAccess {

  /**
   * @access  protected
   * @var     array   Theme parsing data
   */
  protected $data = array();

  // --------------------------------------------------------------------

  /**
   * Implemented for the ArrayAccess interface.
   *
   * @access  public
   * @param   string|int  the offset to set
   * @param   mixed       the value
   * @return  mixed       the value
   *
   * @throws  InvalidArgumentException if the offset is NULL
   */
  public function offsetSet($offset, $value)
  {
    if (is_null($offset))
    {
      throw new \InvalidArgumentException(
        'Themer does not allow pushing data to the Data class as an array.'
      );
    }

    return $this->data[$offset] = $value;
  }

  /**
   * Implemented for the ArrayAccess interface.
   *
   * @access  public
   * @param   string|int  the offset to retrieve
   * @return  mixed       the value of the offset or NULL
   */
  public function offsetGet($offset)
  {
    return ($this->offsetExists($offset)) ? $this->data[$offset] : NULL;
  }

  /**
   * Implemented for the ArrayAccess interface.
   *
   * @access  public
   * @param   string|int  the offset to test
   * @return  bool        whether or not the offset isset
   */
  public function offsetExists($offset)
  {
    return isset($this->data[$offset]);
  }

  /**
   * Implemented for the ArrayAccess interface.
   *
   * @access  public
   * @param   string|int  the offset to unset
   * @return  void
   */
  public function offsetUnset($offset)
  {
    unset($this->data[$offset]);
  }
}
/* End of file Data.php */
/* Location: ./Themer/Data.php */