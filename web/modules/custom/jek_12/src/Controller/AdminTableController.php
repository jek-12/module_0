<?php

namespace Drupal\jek_12\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\mymodule\Controller
 */
class AdminTableController extends ControllerBase {

  /**
   * Secure db conection.
   *
   * @var object
   */
  public $database;

  /**
   * Db connection.
   */
  public static function create(ContainerInterface $container): AdminTableController {
    $service = parent::create($container);
    $service->database = $container->get('database');
    return $service;
  }

  /**
   * The main method which return table,
   * but must table select with submit form and ajax edit, delete form.
   */
  public function index(): array {
    // Create table header.
    $header_table = [
      'id' => $this->t('ID'),
      'image' => $this->t('image'),
      'cats_name' => $this->t('cats_name'),
      'cats_mail' => $this->t('cats_mail'),
      'delete' => $this->t('Delete'),
      'edit' => $this->t('Edit'),
    ];

    // Get data from database.
    $query = $this->database->select('jek_12', 'ms')
      ->fields('ms', ['fid', 'cats_name', 'cats_mail', 'created_time', 'id'])
      ->orderBy('id', 'DESC')
      ->execute();
    $dbselect = $query->fetchAll();
    $rows = [];
    foreach ($dbselect as $my) {
      $url_delete = Url::fromRoute('jek_12.delete', ['id' => $my->id], []);
      $url_edit = Url::fromRoute('jek_12.edit', ['id' => $my->id], []);
      $linkDelete = Link::fromTextAndUrl('Delete', $url_delete);
      $linkEdit = Link::fromTextAndUrl('Edit', $url_edit);
      $my->fid = [
        'data' => [
          '#theme' => 'image_style',
          '#style_name' => 'thumbnail',
          '#uri' => File::load($my->fid)->getFileUri(),
          '#attributes' => [
            'class' => 'cat-image',
            'alt' => 'cat',
          ],
        ],
      ];

      $rows[] = [
        'id' => $my->id,
        'image' => $my->fid,
        'cats_name' => $my->cats_name,
        'cats_mail' => $my->cats_mail,
        'delete' => $linkDelete,
        'edit' => $linkEdit,
      ];
    }
    $form['table'] = [
      '#type' => 'table',
      '#header' => $header_table,
      '#rows' => $rows,
      '#empty' => $this->t('No data found'),
      '#multiple' => FALSE,
      '#required' => TRUE,
    ];
    return $form;

  }

}
