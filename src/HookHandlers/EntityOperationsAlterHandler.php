<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

class EntityOperationsAlterHandler {
  public function alter(array &$operations, EntityInterface $entity): void {
    $this->hideViewEntityButtonFromOperations($operations);
  }

  protected function hideViewEntityButtonFromOperations(&$operations): void {
    unset($operations['view']);
  }
}
