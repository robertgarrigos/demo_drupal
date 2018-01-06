<?php

namespace Drupal\demo\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class DemoResetConfirm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'demo_reset_confirm';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['dump'] = demo_get_dumps();

    $form['warning'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => [
          'messages',
          'warning',
        ]
        ],
    ];
    $form['warning']['message'] = [
      '#markup' => t('This action cannot be undone.')
      ];

    return confirm_form($form, t('Are you sure you want to reset the site?'), 'admin/structure/demo', t('Overwrites all changes that made to this site since the chosen snapshot.'), t('Reset'));
  }

  /**
   * {@inheritdoc}.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Reset site to chosen snapshot.
    _demo_reset($form_state->getValue(['filename']));

    // Do not redirect from the reset confirmation form by default, as it is
    // likely that the user wants to reset all over again (e.g., keeping the
    // browser tab open).
  }

}
