<?php

namespace Drupal\my_module\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure My Module settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'my_module_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['my_module.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['bundle'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Bundle'),
      '#description' => $this->t('Enter the bundle you want to use in my module block'),
      '#default_value' => $this->config('my_module.settings')->get('bundle'),
    ];
    $form['count'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Count'),
      '#description' => $this->t('Enter the number of titles listed in the block.'),
      '#required' => TRUE,
      '#default_value' => $this->config('my_module.settings')->get('count'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!is_numeric($form_state->getValue('count'))) {
      $form_state->setErrorByName('count', $this->t('The count value must be numeric.'));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('my_module.settings')
      ->set('bundle', $form_state->getValue('bundle'))
      ->set('count', $form_state->getValue('count'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
