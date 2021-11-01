<?php

namespace Drupal\jek_12\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\file\Entity\File;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Custom class Overlay extends FormBase.
 */
class Overlay extends FormBase {

  /**
   * Secure database connection.
   *
   * @var \Drupal\Core\Database\Connection|object|null
   */
  protected $database;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): Overlay {
    $service = parent::create($container);
    $service->database = $container->get('database');
    return $service;
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId(): string {
    return 'Overlay';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['cat_img_overlay'] = [
      '#type' => 'link',

    ];
  }

  /**
   * Show cat`s details.
   */
  public function show($id) {
    $dbselect = $this->database->select('jek_12', 'myr')
      ->fields('myr', ['fid', 'cats_name', 'cats_mail', 'created_time', 'id'])
      ->orderBy('id', 'DESC')
      ->execute();

    $obj = $dbselect->fetch(PDO::FETCH_ASSOC);

    $dialog_options = [
      'width' => 'auto',
      'height' => 'auto',
      'dialogClass' => 'image-style-medium',
      'modal' => 'true',
    ];
//    $response = new AjaxResponse();
//    $response->addCommand(new OpenModalDialogCommand('.image-style-medium', $rowsArr, $dialog_options));
//    return $response;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {

  }

}
