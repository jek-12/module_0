<?php

namespace Drupal\jek_12\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;

/**
 * Custom class Form_jek_12 extend FormBase.
 */
class FormJek12 extends FormBase {

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = [
      '#prefix'=> '<div id="formWrapper">',
      '#suffix' => '</div>',
    ];
    $form['cats_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your cat’s name:'),
      '#placeholder' => $this->t('lolik'),
      '#description' => $this->t('Number of characters in the name: 2 - 32'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::ajaxValidateCatName',
        'event' => 'keyup',
        'wrapper' => 'formWrapper',
      ],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add cat'),
      '#ajax' => [
        'callback' => '::ajaxValidate',
        'event' => 'click',
        'wrapper' => 'formWrapper',
      ],
    ];
    return $form;
  }

  /**
   * Return form with validation status (drupal message).
   */
  public function ajaxValidate(array &$form, FormStateInterface $form_state) {
    return $form;
  }

  /**
   * Ajax validation Cat's name.
   */
  public function ajaxValidateCatName(array &$form, FormStateInterface $form_state) {
    $charNameQuantity = strlen($form_state->getValue('cats_name'));
    if ($charNameQuantity < 2 || $charNameQuantity > 32) {
      \Drupal::messenger()->addMessage('Invalid', 'error');
    } else {
      \Drupal::messenger()->addMessage('Valid');
    }
    $form_state->setRebuild(true);
    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
      $this->ajaxValidateCatName($form, $form_state);
  }
//на сабміт проходить але меседж на сабміті перекритий забінженим чи сабміт не відпрацьовує бо статус меседжа - ерорр
  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
//    \Drupal::messenger()->addMessage('Valid');
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId(): string {
    return 'FormJek12';
  }

}

