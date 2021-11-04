<?php

namespace Drupal\jek_12\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\jek_12\Form\FormJek12;
use Drupal\file\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * An example controller.
 */
class SayHiController extends ControllerBase {

  /**
   * Secure database connection.
   *
   * @var \Drupal\Core\Database\Connection|object|null
   */
  protected $database;

  /**
   * Returns a render-able array.
   */
  protected static function formRender(): array {
    $form_class = FormJek12::class;
    $build['form'] = \Drupal::formBuilder()->getForm($form_class);
    return $build;
  }

  /**
   * Show cat`s details.
   */
  public function show($id): AjaxResponse {
    $dbselect = $this->database->select('jek_12', 'base')
      ->fields('base', [])
      ->condition('id', $id)
      ->execute();
    $cat = $dbselect->fetch();
    $cat->fid = [
      '#theme' => 'image_style',
      '#style_name' => 'wide',
      '#uri' => File::load($cat->fid)->getFileUri(),
      '#attributes' => [
        'class' => 'img-about',
        'alt' => 'cat',
      ],
    ];
    $arr = [
      '#theme' => 'about',
      '#picture' => $cat->fid,
      '#name' => $cat->cats_name,
      '#mail' => $cat->cats_mail,
      '#time' => $cat->created_time,
      '#id' => $id,
      '#attached' => [
        'library' => [
          'jek_12/custom_libs',
        ],
      ],
    ];
    $dialog_options = [
      'width' => '800',
      'height' => '500',
      'dialogClass' => 'image-style-medium',
      'modal' => 'true',
    ];

    $response = new AjaxResponse();
    $response->addCommand(new OpenModalDialogCommand('about', $arr, $dialog_options));
    return $response;
  }

  /**
   * {@inheritdoc}
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
      ->orderBy('id', 'DESC')
      ->execute();

    $obj = $dbselect->fetchAll();
    $rowQuantity = count($obj);
    $rowsArr = [];
    for ($i = 0; $i < $rowQuantity; $i++) {
      $arr = get_object_vars($obj[$i]);
      $keys = array_keys($arr);
      $quantityRowsFields = count(get_object_vars($obj[$i]));
      for ($b = 0; $b < $quantityRowsFields; $b++) {
        $rowsArr[$keys[$b]][] = $arr[$keys[$b]];
      }
    }
    if (array_key_exists('fid', $rowsArr)) {
      foreach ($rowsArr['fid'] as &$fid) {
        if (File::load($fid) !== NULL) {
          $fid = [
            '#theme' => 'image_style',
            '#style_name' => 'medium',
            '#alt' => 'cat',
            '#uri' => File::load($fid)->getFileUri(),
          ];
        }
      }
      unset($fid);
    }
    if (array_key_exists('created_time', $rowsArr)) {
      foreach ($rowsArr['created_time'] as &$created_time) {
        if ($created_time !== NULL) {
          $created_time = \Drupal::service('date.formatter')->format($created_time);
        }
      }
      unset($created_time);
    }
    $main = [
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
    return $main;

  }

}
