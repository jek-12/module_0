<?php

namespace Drupal\jek_12\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\jek_12\Form\FormJek12;
use Drupal\file\Entity\File;
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
      ->fields('myr', ['fid', 'cats_name', 'cats_mail', 'created_time', 'id'])
      ->orderBy('id','DESC')
      ->execute();


    $obj = $dbselect->fetchAll();
    $rowQuantity = count($obj);
    $rowsArr = [];
    for ($i = 0; $i < $rowQuantity; $i++) {
      $arr = get_object_vars($obj[$i]);
      $keys = array_keys($arr);
      $quantityRowsFields = count(get_object_vars($obj[$i]));
      for($b = 0; $b < $quantityRowsFields; $b++) {
        $rec = $b;
        $rowsArr[$keys[$rec]][] = $arr[$keys[$rec]];
      }
    }
//    unset($rowsArr['created_time']);
//    $rowsArr = array_values($rowsArr);

    return [
      '#theme' => 'test',
      '#hi_text' => t('“Hello! You can add here a photo of your cat.”'),
      '#form' => $form_func,
      '#rowQuantity' => $rowQuantity,
      '#rowsArr' => $rowsArr,
      '#attached' => [
        'library' => [
          'jek_12/custom_libs',
        ],
      ],
    ];
  }

}
