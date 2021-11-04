<?php

namespace Drupal\jek_12\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;

/**
 * Custom form Edit.
 */
class Edit extends FormJek12 {

  /**
   * {@inheritDoc}
   */
  public function getFormId(): string {
    return 'editForm';
  }

  /**
   * Cat to edit if any.
   *
   * @var object
   */
  protected object $cat;

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, int $id = NULL): array {
    $result = $this->database->select('jek_12', 'c')
      ->fields('c', ['id', 'cats_name', 'cats_mail', 'fid', 'created_time'])
      ->condition('id', $id)
      ->execute();
    $cat = $result->fetch();
    $this->cat = $cat;
    $form = parent::buildForm($form, $form_state);
    $form['cats_name']['#default_value'] = $cat->cats_name;
    $form['cats_mail']['#default_value'] = $cat->cats_mail;
    $form['cats_img']['#default_value'][] = $cat->fid;
    $form['submit']['#value'] = $this->t('Edit cat');
    return $form;
  }

  /**
   * Submit edited version of the cat.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $updated = [
      'cats_name' => $form_state->getValue('cats_name'),
      'cats_mail' => $form_state->getValue('cats_mail'),
      'fid' => $form_state->getValue('cats_img')[0],
    ];
    $file = File::load($form_state->getValue('cats_img')[0]);
    $file->setPermanent();
    $file->save();

    $this->database
      ->update('jek_12')
      ->condition('id', $this->cat->id)
      ->fields($updated)
      ->execute();
  }

  /**
   * Redirect and update data.
   */
  public function ajaxValidate(): AjaxResponse {
    $response = new AjaxResponse();
    $url = Url::fromRoute('jek_12.content');
    $command = new RedirectCommand($url->toString());
    $response->addCommand($command);
    $response->addCommand(new CloseModalDialogCommand());
    return $response;
  }

}
