<?php

namespace Drupal\jek_12\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\jek_12\Form\FormJek12;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * An example controller.
 */
class SayHiController extends ControllerBase {
  /**
   * @var \Drupal\Core\Database\Connection|object|null
   */
  protected $database;

  /**
   * Returns a render-able array *.
   */
  protected static function formRender(): array {
    $form_class = FormJek12::class;
    $build['form'] = \Drupal::formBuilder()->getForm($form_class);
    return $build;
  }

  /**
   *
   */
  public static function create(ContainerInterface $container): SayHiController {
    $service = parent::create($container);
    $service->database = $container->get('database');
    return $service;
  }

  /**
   * Attached css libraries.
   */

  /**
   * Call formRender 'form'.
   */
  public function cats(): array {
    $form_func = self::formRender();
    $dbselect = $this->database->select('jek_12', 'myr')
      ->fields('myr', ['id', 'cats_name', 'cats_mail', 'fid', 'created_time'])
      ->orderBy('id','DESC')
      ->execute();

//    $rows = [];
//
//    for($i = 0; $i < 10; $i++) {
//      $id[$i+1] = $dbselect->fetchAssoc();
//      $rows[$id[$i+1]] = $dbselect->fetchAssoc();
//    }

//    $rowQuantity = count($obj);
//    $rowsArr = [];
//    $quantityRowsFields = [];
//    for ($i = 0; $i < $rowQuantity; $i++) {
//      $rowsArr[$i + 1] = get_object_vars($obj[$i]);
//      $quantityRowsFields[$i + 1] = count(get_object_vars($obj[$i]));
//    }
    return [
      '#theme' => 'test',
      '#hi_text' => t('“Hello! You can add here a photo of your cat.”'),
      '#form' => $form_func,
//      '#rowsArr' => $rowsArr,
//      '#quantityRowsFields' => $quantityRowsFields,
      '#attached' => [
        'library' => [
          'jek_12/custom_libs',
        ],
      ],
    ];
  }

}
