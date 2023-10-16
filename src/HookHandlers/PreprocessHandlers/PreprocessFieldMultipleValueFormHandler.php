<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers\PreprocessHandlers;

use Ordermind\DrupalTengstromShared\HookHandlers\PreprocessHandlerInterface;

class PreprocessFieldMultipleValueFormHandler implements PreprocessHandlerInterface {

  public function preprocess(array &$variables): void {
    $this->addDescriptionDisplaySupport($variables);
  }

  protected function addDescriptionDisplaySupport(array &$variables): void {
    if (empty($variables['element']['#description'])
    || empty($variables['element']['#description_display'])
    || $variables['element']['#description_display'] !== 'before'
    ) {
      return;
    }

    if (empty($variables['table'])) {
      return;
    }

    unset($variables['description']);

    $descriptionElement = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => ['description'],
      ],
      '#value' => $variables['element']['#description'],
    ];

    if (!empty($variables['table']['#header'][0]['data'])) {
      $variables['table']['#header'][0]['data'] = [
        '#type' => 'container',
        'label' => $variables['table']['#header'][0]['data'],
        'description' => $descriptionElement,
      ];
    }
    else {
      $variables['table']['#header'][0]['data'] = $descriptionElement;
    }
  }

}
