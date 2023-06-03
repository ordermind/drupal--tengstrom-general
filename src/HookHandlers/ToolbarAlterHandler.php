<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers;

class ToolbarAlterHandler {

  public function alter(array &$items): void {
    $this->removeHomeItem($items);
  }

  protected function removeHomeItem(array &$items): void {
    unset($items['home']);
  }

}
