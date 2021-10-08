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
    $form['suffix'] = '</div>';
    $form['cats_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your cat’s name:'),
      '#placeholder' => $this->t('lolik'),
      '#description' => $this->t('Number of characters in the name: 2 - 32'),
      '#validated' => FALSE,
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::ala',
        'event' => 'keyup',
        'wrapper' => 'formWrapper',
      ],
    ];
    $form['cats_mail'] = [
      '#type' => 'textfield',
      '#title' => 'Your email:',
      '#description' => 'The name can only contain Latin letters, an underscore, or a hyphen',
      '#placholder' => 'cat@example.com',
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add cat'),
      '#ajax' => [
        'callback' => '::ala',
        'event' => 'click',
        'wrapper' => 'formWrapper',
      ],
    ];
    return $form;
  }

//  public function ajaxMailValidate(array &$form, FormStateInterface $form_state) {
//    return $form;
//  }
//
//  public function validateCatName (array &$form, FormStateInterface $form_state) {
//
//    return $form;
//  }
//
//  public function validateMail (FormStateInterface $form_state) {
//    if(preg_match('/[a-zA-Z._+-]+@[a-z]+.[a-z]{2,5}$/', $form_state -> getValue('cats_mail'))) {
//      $form_state->setErrorByName('cats_mail', $this->t('Valid cats name'));
//    } else {
//      $form_state->setErrorByName('cats_mail', $this->t('Invalid cats name'));
//    }
//  }
//
  /**
   * Return form with validation status (drupal message).
   */
////  public function ajaxNameValidate(array &$form, FormStateInterface $form_state) {
////    return $form;
////  }



  /**
   * {@inheritDoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $charNameValue = strlen($form_state->getValue('cats_name'));
    if ($charNameValue < 2 || $charNameValue > 32) {
      $form_state->setErrorByName('cats_name', $this->t('Invalid cats name'));
    }
//    $this -> setErrorName($form, $form_state, $nameFailValidation);
//    if(preg_match('/[a-zA-Z._+-]+@[a-z]+.[a-z]{2,5}$/', $form_state -> getValue('cats_mail'))) {
//      $form_state->setErrorByName('cats_mail', $this->t('Valid cats name'));
//    }
  }
  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
//    $this -> setErrorName($form, $form_state, self::$nameFailValidation);
//    $a = gettype($form_state->getError('cats_name'));
//    if(!($form_state->getError('cats_name'))) {
      \Drupal::messenger()->addMessage('Valid cats name');
//    }
  }

  public function ala(array &$form, FormStateInterface $form_state) {
    return $form;
  }
  /**
   * {@inheritDoc}
   */
  public function getFormId(): string {
    return 'FormJek12';
  }

}
