<?php

namespace Drupal\jek_12\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Custom class Form_jek_12 extend FormBase.
 */
class FormJek12 extends FormBase {

  /**
   * @var \Drupal\Core\Database\Connection|object|null
   */
  public $database;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): FormJek12 {
    $services = parent::create($container);
    $services->messenger = $container->get('messenger');
    $services->database = $container->get('database');
    return $services;
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $validatorCatImg = [
      'file_validate_extensions' => ['jpeg jpg png'],
      'file_validate_size' => [2097152],
    ];

    $form['#prefix'] = '<div id="formWrapper">';
    $form['#suffix'] = '</div>';
    $form['cats_img'] = [
      '#type' => 'managed_file',
      '#title' => 'Your cat’s photo',
      '#description' => $this->t('Only type of jpeg, jpg, png'),
      '#upload_location' => 'public://image',
      '#required' => TRUE,
      '#upload_validators' => $validatorCatImg,
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
        'progress' => [
          'type' => 'none',
        ],
      ],
    ];
    $form['cats_mail'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your email:'),
      '#placeholder' => $this->t('bo_bik@example.com'),
      '#description' => $this->t('The name can only contain Latin letters, an underscore, or a hyphen. Also be sure to use @'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::ajaxValidateMail',
        'event' => 'keyup',
        'wrapper' => 'formWrapper',
        'progress' => [
          'type' => 'none',
        ],
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
  public function ajaxValidate(array $form): array {
    return $form;
  }

  /**
   * Set warning for selected field name.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   this is FormStateInterface.
   * @param string $field
   *   - The machine name of the field for which the error is set.
   */
  protected function setWarnForField(FormStateInterface $form_state, string $field): void {
    $fieldValue = $form_state->getValue($field);
    $form_state->setErrorByName($field, t('Please enter correct @fieldName, because @fieldValue is incorrect!', [
      '@fieldValue' => $fieldValue,
      '@fieldName' => $field,
    ]));
  }

  /**
   * Unset warning for selected field name.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   this is FormStateInterface.
   * @param string $field
   *   - The machine name of the field for which the error is unset.
   */
  protected function unsetWarnForField(FormStateInterface $form_state, string $field): void {
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
  public function ajaxValidateCatName(array $form, FormStateInterface $form_state): array {
    $this->customValidate($form, $form_state, 'cats_name', 'ajax');
    return $form;
  }

  /**
   * Ajax validation Mail.
   */
  public function ajaxValidateMail(array $form, FormStateInterface $form_state): array {
    $this->customValidate($form, $form_state, 'cats_mail', 'ajax');
    return $form;
  }

  /**
   * Custom logic for different form fields.
   *
   * @param array $form
   *   - Form render array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   this is FormStateInterface.
   * @param string $whichForm
   *   - Machine name of your field.
   * @param string $validatefunc
   *   - Where current function will be used, bind different message.
   *   - Use 'ajax' in custom ajax callback.
   *   - Use 'form' in validateForm.
   */
  public function customValidate(array $form, FormStateInterface $form_state, string $whichForm, string $validatefunc = 'ajax'): array {
    $charNameQuantity = strlen($form_state->getValue('cats_name'));
    switch ($whichForm) {
      case 'cats_mail':
        switch ($validatefunc) {
          case 'ajax':
            if (preg_match('/[a-zA-Z._+-]+@[a-z]+.[a-z]{2,5}$/', $form_state->getValue('cats_mail'))) {
              $this->messenger()->addMessage('Valid mail', 'status');
            }
            else {
              $this->messenger()->addMessage('Invalid mail', 'error');
            }
            break;

          case 'form':
            if (preg_match('/[a-zA-Z._+-]+@[a-z]+.[a-z]{2,5}$/', $form_state->getValue('cats_mail'))) {
              $this->unsetWarnForField($form_state, 'cats_mail');
            }
            else {
              $this->setWarnForField($form_state, 'cats_mail');
            }
            break;
        }
        break;

      case 'cats_name':
        switch ($validatefunc) {
          case 'ajax':
            if ($charNameQuantity < 2 || $charNameQuantity > 32) {
              $this->messenger()->addMessage('Invalid cat`s name', 'error');
            }
            else {
              $this->messenger()->addMessage('Valid cat`s name', 'status');
            }
            break;

          case 'form':
            if ($charNameQuantity < 2 || $charNameQuantity > 32) {
              $this->setWarnForField($form_state, 'cats_name');
            }
            else {
              $this->unsetWarnForField($form_state, 'cats_name');
            }
            break;
        }
        break;
    }

    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $this->customValidate($form, $form_state, 'cats_mail', 'form');
    $this->customValidate($form, $form_state, 'cats_name', 'form');
  }

  /**
   * Push existing date to database.
   */
  protected function pushDate(array $form, FormStateInterface $form_state) {
    $requestTime = \Drupal::time()->getRequestTime();
    $image = $form_state->getValue('cats_img')[0];
    $data = [
      'fid' => $image,
      'cats_name' => $form_state->getValue('cats_name'),
      'cats_mail' => $form_state->getValue('cats_mail'),
      'created_time' => $requestTime,
    ];
    $this->database->insert('jek_12')->fields($data)->execute();
    $baseFields = $this->database->select('jek_12', 'base')->fields('base')->execute()->fetchAll();//controller
    $this->messenger()->addMessage(\Drupal::service('date.formatter')->format($requestTime));
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->messenger()->addMessage('Valid submit');
    $this->pushDate($form, $form_state);
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId(): string {
    return 'FormJek12';
  }

}
