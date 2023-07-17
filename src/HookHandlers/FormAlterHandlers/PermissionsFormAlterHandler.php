<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers\FormAlterHandlers;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Ordermind\DrupalTengstromShared\HookHandlers\FormAlterHandlerInterface;

class PermissionsFormAlterHandler implements FormAlterHandlerInterface {

  public function alter(array &$form, FormStateInterface $formState, string $formId): void {
    $this->displayMachineNamesOnPermissionList($form);
  }

  protected function displayMachineNamesOnPermissionList(array &$form): void {
    foreach (Element::children($form['permissions']) as $key) {
      if (!empty($form['permissions'][$key]['description']['#template'])) {
        $form['permissions'][$key]['description']['#template'] = str_replace('{{ title }}', '{{ title }} (<code>{{ machine_name }}</code>)', $form['permissions'][$key]['description']['#template']);
        $form['permissions'][$key]['description']['#context']['machine_name'] = $key;
      }
    }
  }

}
