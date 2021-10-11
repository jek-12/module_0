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
    $form['#prefix'] = '<div id="formWrapper">';
    $form['#suffix'] = '</div>';
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
    $form['cats_mail'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your email:'),
      '#placeholder' => $this->t('bo_bik@example.com'),
      '#description' => $this->t('The name can only contain Latin letters, an underscore, or a hyphen. Also be sure to use @'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::customValidate',
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
  public function ajaxValidate(array &$form) {
    return $form;
  }

  /**
   * @param $par
   * @return string
   * return value of $par with string type.
   */
  protected function changeType ($par): string {
    if (!is_string($par)) {
      $par = strval($par);
    }
    return $par;
  }
  /**
   * @param FormStateInterface $form_state
   * @param $field - the machine name of the field for which the error is set.
   */
  protected function setWarnForField (FormStateInterface $form_state, $field) {
    $field = $this->changeType($field);
    $fieldValue = $form_state->getValue($field);
    $form_state->setErrorByName($field, t('Please enter correct @fieldName, because @fieldValue is incorrect, ', [
      '@fieldValue' => $fieldValue,
      '@fieldName' => $field,
    ]));
  }

  public function unsetWarnForField (FormStateInterface $form_state, $field) {
    $field = $this->changeType($field);
    $form_errors = $form_state->getErrors();
    $form_state->clearErrors();
    unset($form_errors[$field]);
    foreach ($form_errors as $name => $error_message) {
      $form_state->setErrorByName($name, $error_message);
    }

  }

  /**
   * Ajax validation Cat's name.
   */
  public function ajaxValidateCatName(array $form) {

//    $form_state->setRebuild(true);
    return $form;
  }


  public function customValidate(array &$form, FormStateInterface $form_state, $validatefunc = 'ajax') {
    $char = strlen($form_state->getValue('cats_mail'));
    $charNameQuantity = strlen($form_state->getValue('cats_name'));
    switch ($validatefunc) {
      case 'ajax':
        if ($char > 10) {
          \Drupal::messenger()->addMessage('Valid mail', 'status');
        } else {
          \Drupal::messenger()->addMessage('Invalid mail', 'error');
        }
        if ($charNameQuantity < 2 || $charNameQuantity > 32) {
          \Drupal::messenger()->addMessage('Invalid cat`s name', 'error');
        } else {
          \Drupal::messenger()->addMessage('Valid cat`s name', 'status');
        }
        break;
      case 'form':
        if ($char > 10) {
          $this->unsetWarnForField($form_state, 'cats_mail');
        } else {
          $this->setWarnForField($form_state, 'cats_mail');
        }
        if ($charNameQuantity < 2 || $charNameQuantity > 32) {
          $this->unsetWarnForField($form_state, 'cats_name');
        } else {
          $this->setWarnForField($form_state, 'cats_name');
        }
        break;
    }

//    $form_state->setRebuild(true);
    return $form;
  }
//  function signup_validate($form, FormStateInterface $form_state) {
//    $a= $form_state->getValue('cats_name');
//
//    if ($a[0]['value'] != '') {
//      $form_state->clearErrors();
//    }
//  }

  /**
   * {@inheritDoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $this -> customValidate($form, $form_state, 'form');

//    $this->ajaxValidateCatName($form, $form_state);
//    $this->ajaxValidateMail($form, $form_state);
  }
//на сабміт проходить але меседж на сабміті перекритий забінженим чи сабміт не відпрацьовує бо статус меседжа - ерорр
  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::messenger()->addMessage('Valid submit');
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId(): string {
    return 'FormJek12';
  }

}

