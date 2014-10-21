<?php
/**
 * Created by PhpStorm.
 * User: jiv
 * Date: 20/10/14
 * Time: 22:22
 */

namespace MarkupTranslator;

use Traversable;
use Underscore\Types\Arrays;

// See http://www.sitepoint.com/using-spl-iterators-2/
class MarkupDictionary implements \IteratorAggregate {

  /**
   * @var object
   */
  private $rawData;

  /**
   * @var array
   */
  private $elements;

  /**
   * @var MarkupElementCollection
   */
  protected $elementTree;

  /**
   * @var array
   */
  protected $orderedElements;

  //this returns a boolean indicating if the there
  // is data at the current position in the dataset
  public function valid() {
    return isset($this->currentElement);
  }

  public function __construct($path) {
    $this->rawData = json_decode(file_get_contents($path));
    $this->elements = $this->createElements($this->rawData);
    $this->elementTree = $this->buildElementCollection($this->elements);
    $this->orderedElements = $this->orderElements($this->elementTree);
  }

  public function getElement($id) {
    return Arrays::find($this->getElements(), function($element) use ($id) {
      return $element->getId() == $id;
    });
  }

  /**
   * @return array
   */
  public function getElements() {
    return $this->elements;
  }

  /**
   * @return array
   */
  public function getOrderedElements() {
    return $this->orderedElements;
  }

  protected function createElements($rawData) {
    //Build basic elements array
    return Arrays::each($rawData->elements, function($element) {
      return new MarkupElement($element->id, $element->source, $element->target, isset($element->extends) ? $element->extends : NULL);
    });
  }

  protected function buildElementCollection($elements) {
    //Array with element id's as keys
    $idKeyArray = Arrays::replaceKeys(
      $elements, Arrays::each(
        $elements, function($element) {
          return $element->getId();
        }));
    //Put extended elements in extendedBy of the corresponding element
    $elementCollection = Arrays::filter($idKeyArray, function($element) use(&$idKeyArray) {
      if($element->getExtends() !== NULL) {
        $idKeyArray[$element->getExtends()]->addExtendedBy($element);
        return FALSE;
      }
      return TRUE;
    });
    return $elementCollection;
  }

  /**
   * Orders elements so that extenders are positioned before the extended elements
   * @param $elementTree MarkupElement[]
   */
  protected function orderElements($elementTree) {
    $orderedElements = array();
    foreach ($elementTree as $element) {
      //Traverse extenders recursively
      //TODO Refactor more beautiful solution that doesn't need orderedElements array
      $orderedElements[] = $this->insertExtenders($element, $orderedElements);
    }
    return $orderedElements;
  }

  /**
   * @param $currentElement MarkupElement
   * @param $orderedElements MarkupElement[]
   * @return MarkupElement
   */
  protected function insertExtenders($currentElement, &$orderedElements) {
    if($currentElement->getExtendedBy()->getLength() > 0) {
      foreach ($currentElement->getExtendedBy() as $element) {
        $orderedElements[] = $this->insertExtenders($element, $orderedElements);
      }
    }
    return $currentElement;
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Retrieve an external iterator
   * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
   * @return Traversable An instance of an object implementing <b>Iterator</b> or
   * <b>Traversable</b>
   */
  public function getIterator() {
    return new \ArrayIterator($this->orderedElements);
  }
}
