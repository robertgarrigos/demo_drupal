<?php

namespace Drupal\demo\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 *
 */
class deleteConfig extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'demo_config_delete_confirm';
  }

  /**
   *
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['dump'] = demo_get_config_dumps();
    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['delete'] = [
      '#type' => 'submit',
      '#value' => t('Delete'),
      '#submit' => ['demo_config_delete_submit'],
    ];

    // If there are no snapshots yet, hide the selection and form actions.
    if (empty($form['dump']['#options'])) {
      $form['dump']['#access'] = FALSE;
      $form['actions']['#access'] = FALSE;
    }

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

  }

}
