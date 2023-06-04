<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers;

use Symfony\Component\HttpFoundation\RequestStack;

class MenuLocalTasksAlterHandler {
  protected RequestStack $requestStack;

  public function __construct(RequestStack $requestStack) {
    $this->requestStack = $requestStack;
  }

  public function alter(array &$data): void {
    $this->passDestinationToTabs($data);
  }

  /**
   * Passes the current destination to tabs (i.e. local tasks).
   */
  protected function passDestinationToTabs(array &$data): void {
    $currentDestination = $this->requestStack->getCurrentRequest()->query->get('destination');
    if (!$currentDestination) {
      return;
    }

    if (empty($data['tabs'] || !is_array($data['tabs']))) {
      return;
    }

    foreach ($data['tabs'] as $tabs) {
      foreach ($tabs as $tab) {
        $tab['#link']['url']->setOption('query', ['destination' => $currentDestination]);
      }
    }
  }

}
