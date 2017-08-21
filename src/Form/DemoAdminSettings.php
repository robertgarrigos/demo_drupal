<?php

/**
 * @file
 * Contains \Drupal\demo\Form\DemoAdminSettings.
 */

namespace Drupal\demo\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class DemoAdminSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'demo_admin_settings';
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

    if (method_exists($this, '_submitForm')) {
      $this->_submitForm($form, $form_state);
    }

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['demo.settings'];
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    if (!file_stream_wrapper_valid_scheme('private')) {
      $form_state->setErrorByName('', t('The <a href="@file-settings-url">private filesystem</a> must be configured in order to create or load snapshots.', [
        '@file-settings-url' => url('admin/config/media/file-system', [
          'query' => drupal_get_destination()
          ])
        ]));
    }

    $form['demo_dump_path'] = [
      '#type' => 'textfield',
      '#title' => t('Snapshot file system path'),
      '#field_prefix' => 'private://',
      '#default_value' => variable_get('demo_dump_path', 'demo'),
      '#required' => TRUE,
    ];
    $form['#validate'][] = 'demo_admin_settings_validate';

    return parent::buildForm($form, $form_state);
  }

  public function validateForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    if (!file_prepare_directory($form_state->getValue(['demo_dump_path']), FILE_CREATE_DIRECTORY)) {
      $form_state->setErrorByName('demo_dump_path', t('The snapshot directory %directory could not be created.', [
        '%directory' => $form_state->getValue(['demo_dump_path'])
        ]));
    }
  }

}
