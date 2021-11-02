<?php

namespace Drupal\jek_12\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 *
 */
class Filter extends AbstractExtension {

  /**
   *
   */
  public function getFunctions() {
    return [
      new TwigFunction('unset', ['Drupal\jek_12\TwigExtension\Filter', 'test'], ['needs_context' => TRUE]),
    ];
  }

  /**
   * $context is a special array which hold all know variables inside
   * If $key is not defined unset the whole variable inside context
   * If $key is set test if $context[$variable] is defined if so unset $key inside multidimensional array.
   */
  public static function test(&$context, $variable, $key = NULL) {
    if ($key === NULL) {
      unset($context[$variable]);
    }
    else {
      if (isset($context[$variable])) {
        unset($context[$variable][$key]);
      }
    }
  }

}
