<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers\FormAlterHandlers;

use Drupal\Core\Form\FormStateInterface;

interface WidgetFormAlterHandlerInterface {

  public function alter(array &$form, FormStateInterface $formState, array $context): void;

}
