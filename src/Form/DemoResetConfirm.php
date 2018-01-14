<?php

namespace Drupal\demo\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;

/**
 *
 */
class DemoResetConfirm extends ConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'demo_reset_confirm';
  }

  /**
   *
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['dump'] = demo_get_dumps();

    drupal_set_message(t('This action cannot be undone.'), 'warning');

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Reset now'),
    ];

    return $form;
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
    // Reset site to chosen snapshot.
    _demo_reset($form_state->getValue(['filename']));

    // Do not redirect from the reset confirmation form by default, as it is
    // likely that the user wants to reset all over again (e.g., keeping the
    // browser tab open).
  }

  /**
   *
   */
  public function getCancelUrl() {
    return new Url('demo.manage_form');
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Do you want to reset the site?');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete');
  }

}
