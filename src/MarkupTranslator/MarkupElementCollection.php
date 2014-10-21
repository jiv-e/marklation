<?php
/**
 * Created by PhpStorm.
 * User: jiv
 * Date: 21/10/14
 * Time: 15:44
 */

namespace MarkupTranslator;


class MarkupElementCollection implements \IteratorAggregate {

  /**
   * @var array
   */
  protected $collection;

  function __construct() {
    $this->collection = array();
  }

  /**
   * @param $element MarkupElement
   */
  public function addElement($element) {
    $this->collection[$element->getId()] = $element;
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Retrieve an external iterator
   * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
   * @return \Iterator
   */
  public function getIterator() {
    return new \ArrayIterator($this->collection);
  }

  public function getLength() {
    return count($this->collection);
  }
}
