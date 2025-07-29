<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers\PreprocessHandlers;

use Ordermind\DrupalTengstromShared\HookHandlers\PreprocessHandlerInterface;

class PreprocessImageWidgetHandler implements PreprocessHandlerInterface {

  public function preprocess(array &$variables): void {
    $this->removeUploadButton($variables);
  }

  protected function removeUploadButton(array &$variables): void {
    if (!empty($variables['element']['upload_button']['#attributes']['class'])) {
      $variables['element']['upload_button']['#attributes']['class'][] = 'file-widget--upload-button';
    }

    if (!empty($variables['data']['upload_button']['#attributes']['class'])) {
      $variables['data']['upload_button']['#attributes']['class'][] = 'file-widget--upload-button';
    }
  }

}
