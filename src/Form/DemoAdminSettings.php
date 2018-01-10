<?php

namespace Drupal\demo\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 *
 */
class DemoAdminSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'demo_admin_settings';
  }

  /**
   *
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['demo_dump_path'] = [
      '#type' => 'textfield',
      '#title' => t('Snapshot file system path'),
      '#field_prefix' => 'private://',
      '#default_value' => \Drupal::state()->get('demo_dump_path', 'demo'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   *
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!file_prepare_directory($form_state->getValue(['demo_dump_path']), FILE_CREATE_DIRECTORY)) {
      $form_state->setErrorByName('demo_dump_path', t('The snapshot directory %directory could not be created.', [
        '%directory' => $form_state->getValue(['demo_dump_path']),
      ]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('demo.settings');

    foreach (Element::children($form) as $variable) {
      $config->set($variable, $form_state->getValue($form[$variable]['#parents']));
    }
    $config->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['demo.settings'];
  }

}
