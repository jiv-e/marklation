<?php
/**
 * Marklation - Markup translator and dictionary standard
 * Copyright (c) 2014 Juho Viitasalo
 */

namespace Marklation;

class MarkupElement {

  private $id;
  private $source;
  private $target;
  private $extends;
  /**
   * @var MarkupElementCollection
   */
  private $extendedBy;

  /**
   * @return MarkupElementCollection
   */
  public function getExtendedBy() {
    return $this->extendedBy;
  }

  function __construct($id, $source, $target, $extends = NULL) {
    $this->id = $id;
    $this->source = Utilities::sanitize($source);
    $this->target = $target;
    $this->extends = $extends;

    $this->extendedBy = new MarkupElementCollection();
  }

  /**
   * @return mixed
   */
  public function getExtends() {
    return $this->extends;
  }

  /**
   * @return mixed
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getSource() {
    return $this->source;
  }

  /**
   * @return mixed
   */
  public function getTarget() {
    return $this->target;
  }

  /**
   * @param MarkupElement $element
   * @return MarkupElement
   */
  public function addExtendedBy(MarkupElement $element) {
    $this->extendedBy->addElement($element);
    return $element;
  }

}
