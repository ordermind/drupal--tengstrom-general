<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers\FormAlterHandlers;

use Drupal\Core\Form\FormStateInterface;

class FieldWidgetFormAlterHandler implements WidgetFormAlterHandlerInterface {

  public function alter(array &$element, FormStateInterface $formState, array $context): void {
    $element['#after_build'][] = [$this, 'afterBuildCallback'];
  }

  public function afterBuildCallback(array $element, FormStateInterface $formState): array {
    $this->hideWysiwygHelpText($element);

    return $element;
  }

  protected function hideWysiwygHelpText(array &$element): void {
    if (empty($element['#type']) || $element['#type'] !== 'text_format') {
      return;
    }

    if (empty($element['format']['help'])) {
      return;
    }

    $element['format']['help']['#access'] = FALSE;
    $element['format']['#attributes']['class'][] = 'hidden';
  }

}
