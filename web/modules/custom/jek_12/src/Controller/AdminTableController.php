<?php

namespace Drupal\jek_12\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DisplayTableController
 *
 * @package Drupal\mymodule\Controller
 */
class AdminTableController extends ControllerBase {

  protected $database;

  public static function create(ContainerInterface $container): AdminTableController {
    $service = parent::create($container);
    $service->database = $container->get('database');
    return $service;
  }

  public function index() {
    //create table header
    $header_table = [
      'id' => t('ID'),
      'cats_name' => t('cats_name'),
      'cats_mail' => t('cats_mail'),
      'delete' => t('Delete'),
      'edit' => t('Edit'),
    ];


    // get data from database
    $query = $this->database->select('jek_12', 'ms')
      ->fields('ms', ['fid', 'cats_name', 'cats_mail', 'created_time', 'id'])
      ->orderBy('id', 'DESC')
      ->execute();
    $dbselect = $query->fetchAll();
    $rows = [];
    foreach ($dbselect as $data) {
      $url_delete = Url::fromRoute('jek_12.delete', ['id' => $data->id], []);
      $url_edit = Url::fromRoute('jek_12.edit', ['id' => $data->id], []);
      $linkDelete = Link::fromTextAndUrl('Delete', $url_delete);
      $linkEdit = Link::fromTextAndUrl('Edit', $url_edit);

      //get data
      $rows[] = [
        'id' => $dbselect->fid,
        'cats_name' => $dbselect->cats_name,
        'cats_mail' => $dbselect->cats_mail,
        'delete' => $linkDelete,
        'edit' => $linkEdit,
      ];

    }
    // render table
    $form['table'] = [
      '#type' => 'table',
      '#header' => $header_table,
      '#rows' => $rows,
      '#empty' => t('No data found'),
    ];
    return $form;

  }

}
