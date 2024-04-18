<?php

namespace Drupal\hello_module\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures your_module’s settings.
 */
class Hello_ModuleConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hello_module_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'hello_module.admin_settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this -> config('hello_module.admin_settings');

    $form['your_message'] = [
      // '#type' => 'textfield',
      '#type' => 'text_format',
      '#title' => $this->t('Título Mensage'),
      '#default_value' => $config->get('your_message')['value'],
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this -> config('hello_module.admin_settings')
    ->set('your_message', $form_state->getValue('your_message'))
    ->save();
    parent::submitForm($form, $form_state);

    $form_state -> setRedirect('hello_module.welcome');
  }
}