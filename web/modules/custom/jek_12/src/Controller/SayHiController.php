<?php

namespace Drupal\jek_12\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\jek_12\Form\FormJek12;

/**
 * An example controller.
 */
class SayHiController extends ControllerBase {

  /**
   * Returns a render-able array *.
   */
  protected static function formRender(): array {
    $form_class = FormJek12::class;
    $build['form'] = \Drupal::formBuilder()->getForm($form_class);
    return $build;
  }

  /**
   * Attached css libraries.
   */

  /**
   * Call formRender 'form'.
   */
  public function cats(): array {
    $form_func = self::formRender();
    return [
      '#theme' => 'test',
      '#hi_text' => t('“Hello! You can add here a photo of your cat.”'),
      '#form' => $form_func,
      '#attached' => [
        'library' => [
          'jek_12/custom_libs',
        ],
      ],
    ];
  }

}
