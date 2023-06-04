<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers\FormAlterHandlers;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;

class SystemPerformanceSettingsFormAlterHandler implements FormAlterHandlerInterface {
  protected ConfigFactoryInterface $config;

  public function __construct(ConfigFactoryInterface $config) {
    $this->config = $config;
  }

  public function alter(array &$form, FormStateInterface $formState, string $formId): void {
    $this->disableOptionsThatAreOverriddenInSettingsFile($form);
  }

  protected function disableOptionsThatAreOverriddenInSettingsFile(array &$form): void {
    // The default values for preprocess config do not take the config
    // overrides into account, so in order to avoid confusion we modify
    // the default value here manually.
    $systemPerformanceConfig = $this->config->get('system.performance');
    if (!empty($form['bandwidth_optimization']['preprocess_css'])) {
      $form['bandwidth_optimization']['preprocess_css']['#default_value'] = $systemPerformanceConfig->get('css.preprocess');
      $form['bandwidth_optimization']['preprocess_css']['#disabled'] = TRUE;
    }
    if (!empty($form['bandwidth_optimization']['preprocess_js'])) {
      $form['bandwidth_optimization']['preprocess_js']['#default_value'] = $systemPerformanceConfig->get('js.preprocess');
      $form['bandwidth_optimization']['preprocess_js']['#disabled'] = TRUE;
    }

    if (!empty($form['scss_compiler']['scss_cache'])) {
      $form['scss_compiler']['scss_cache']['#disabled'] = TRUE;
    }
  }

}
