<?php

namespace Drupal\jek_12\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Custom class Delete extend FormBase.
 */
class Delete extends ConfirmFormBase {

  /**
   * Secure database connection.
   *
   * @var \Drupal\Core\Database\Connection|object|null
   */
  protected $database;

  protected $id;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): Delete {
    $services = parent::create($container);
    $services->database = $container->get('database');
    return $services;
  }

  /**
   * @inheritDoc
   */
  public function getQuestion() {
    return t('Really?');
  }

  /**
   * @inheritDoc
   */
  public function getCancelUrl() {
    return new Url('jek_12.content');
  }

  /**
   * @inheritDoc
   */
  public function getCancelText() {
    return t('Cancel!');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Apply!');
  }

  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'delete';
  }

  /**
   * @inheritDdoc
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->id = $id;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->database->delete('jek_12')
      ->condition('id', $this->id)
      ->execute();
    $form_state->setRedirect('jek_12.content');
  }

}
