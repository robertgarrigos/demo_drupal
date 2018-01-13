<?php

namespace Drupal\demo\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;

/**
 *
 */
class DemoDeleteConfirm extends ConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'demo_delete_confirm';
  }
  
  public $filename;
  /**
   *
   */
  public function buildForm(array $form, FormStateInterface $form_state, $filename = NULL) {
    $fileconfig = demo_get_fileconfig($filename);
    if (!file_exists($fileconfig['infofile'])) {
      drupal_set_message(t('File not found'), 'error');
    }

    $form['filename'] = [
      '#type' => 'value',
      '#value' => $filename,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   *
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $files = demo_get_fileconfig($form_state->getValue(['filename']));
    unlink($files['sqlfile']);
    unlink($files['infofile']);
    drupal_set_message(t('Snapshot %title has been deleted.', [
      '%title' => $form_state->getValue(['filename']),
    ]));
    $form_state->setRedirect('demo.manage_form');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('demo.manage_form');
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Do you want to delete this screenshot?');
  }

  /**
   * {@inheritdoc}
   */
  function getConfirmText() {
    return t('Delete');
  }

}
