<?php
/**
 * Marklation - Markup translator and dictionary standard
 * Copyright (c) 2014 Juho Viitasalo
 */

namespace Marklation;


interface MarkupTranslatorInterface {
  #
  # Main function. Performs some preprocessing on the input text
  # and pass it through the document gamut.
  #
  public function translate($text);

}
