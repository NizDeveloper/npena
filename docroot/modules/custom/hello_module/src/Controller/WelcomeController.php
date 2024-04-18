<?php

namespace Drupal\hello_module\Controller;
use Drupal\Core\Controller\ControllerBase;

class WelcomeController extends ControllerBase{
  public function welcome() {
    $config = \Drupal::config('hello_module.admin_settings')->get('your_message')['value'];

    return [
      "#markup" => $config
    ];
  }
}