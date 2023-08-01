<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\Repository;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class EntityRepository {
  protected EntityTypeManagerInterface $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  public function countEntitiesOfType(string $entityTypeId): int {
    $storage = $this->entityTypeManager->getStorage($entityTypeId);

    return $storage->getQuery()->accessCheck(FALSE)->count()->execute();
  }

  /**
   * @return \Generator|int[][]
   */
  public function fetchEntityIdsOfType(string $entityTypeId, int $chunkSize): \Generator {
    $storage = $this->entityTypeManager->getStorage($entityTypeId);

    $entityQuery = $storage->getQuery()
      ->accessCheck(FALSE);

    while (
      $entityIds = $entityQuery
        ->range(0, $chunkSize)
        ->execute()
    ) {
      yield $entityIds;
    }
  }

  /**
   * @return \Generator|EntityInterface[][]
   */
  public function fetchEntitiesOfType(string $entityTypeId, int $chunkSize): \Generator {
    $storage = $this->entityTypeManager->getStorage($entityTypeId);

    foreach ($this->fetchEntityIdsOfType($entityTypeId, $chunkSize) as $entityIds) {
      yield $storage->loadMultiple($entityIds);
    }
  }

  public function fetchFirstEntityOfType(string $entityTypeId): ?EntityInterface {
    $storage = $this->entityTypeManager->getStorage($entityTypeId);

    $entityIds = $storage->getQuery()
      ->accessCheck(FALSE)
      ->range(0, 1)
      ->execute();

    if (!$entityIds) {
      return NULL;
    }

    return $storage->load(reset($entityIds));
  }

}
