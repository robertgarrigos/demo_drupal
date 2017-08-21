<?php

/**
 * @file
 * Contains \Drupal\demo\Form\DemoDeleteConfirm.
 */

namespace Drupal\demo\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class DemoDeleteConfirm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'demo_delete_confirm';
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state, $filename = NULL) {
    $fileconfig = demo_get_fileconfig($filename);
    if (!file_exists($fileconfig['infofile'])) {
      return drupal_access_denied();
    }

    $form['filename'] = [
      '#type' => 'value',
      '#value' => $filename,
    ];
    return confirm_form($form, t('Are you sure you want to delete the snapshot %title?', [
      '%title' => $filename
      ]), 'admin/structure/demo', t('This action cannot be undone.'), t('Delete'));
  }

  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $files = demo_get_fileconfig($form_state->getValue(['filename']));
    unlink($files['sqlfile']);
    unlink($files['infofile']);
    drupal_set_message(t('Snapshot %title has been deleted.', [
      '%title' => $form_state->getValue(['filename'])
      ]));
    $form_state->set(['redirect'], 'admin/structure/demo');
  }

}
