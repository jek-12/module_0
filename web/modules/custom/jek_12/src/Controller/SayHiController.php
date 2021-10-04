<?php

namespace Drupal\jek_12\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * An example controller.
 */
class SayHiController extends ControllerBase {

  /**
   * Returns a render-able array for a test page.
   */
  public function cats(): array {
    $build['example'] = [
      '#theme' => 'test',
      '#hi_text' => t('“Hello! You can add here a photo of your cat.”'),
    ];
    return $build;
  }

}
