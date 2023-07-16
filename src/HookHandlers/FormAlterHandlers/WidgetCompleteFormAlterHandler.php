<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers\FormAlterHandlers;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

class WidgetCompleteFormAlterHandler implements WidgetFormAlterHandlerInterface {

  public function alter(array &$widgetForm, FormStateInterface $formState, array $context): void {
    $this->disableExtraItemsOnMultipleValueFieldWidget($widgetForm, $formState);
  }

  /**
   * Prevents new items to be automatically added to multiple value field
   * widgets if there already exists at least one item beforehand.
   */
  protected function disableExtraItemsOnMultipleValueFieldWidget(array &$widgetForm, FormStateInterface $formState): void {
    $widget = &$widgetForm['widget'];
    $fieldName = $widget['#field_name'];

    if (empty($widget['#cardinality_multiple'])) {
      return;
    }

    if (empty($widget['#max_delta'])) {
      return;
    }

    if (!empty($formState->getValue($fieldName))) {
      return;
    }

    $triggeringElement = $formState->getTriggeringElement();
    if (
      is_array($triggeringElement)
      && !empty($triggeringElement['#attributes']['class'])
      && (in_array('remove-field-button', $triggeringElement['#attributes']['class'])
        || in_array('field-add-more-submit', $triggeringElement['#attributes']['class'])
      )
    ) {
      return;
    }

    unset($widget[$widget['#max_delta']]);
    $widget['#max_delta'] -= 1;

    $parents = $context['form']['#parents'] ?? [];
    $widgetState = WidgetBase::getWidgetState($parents, $fieldName, $formState);
    $widgetState['items_count'] = $widget['#max_delta'];
    WidgetBase::setWidgetState($parents, $fieldName, $formState, $widgetState);
  }

}
