<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers\PreprocessHandlers;

class PreprocessPageTitleBlockHandler implements PreprocessHandlerInterface {

  public function preprocess(array &$variables): void {
    $this->supportComplexPageTitleStructure($variables);
  }

  /**
   * This allows the page title to be a container which creates more freedom.
   */
  protected function supportComplexPageTitleStructure(array &$variables): void {
    if (!is_array($variables['content']['#title'])) {
      return;
    }

    if (empty($variables['content']['#title']['#type'])) {
      return;
    }

    if ($variables['content']['#title']['#type'] !== 'container') {
      return;
    }

    $variables['content'] = $variables['content']['#title'];
  }

}
