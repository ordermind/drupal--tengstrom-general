<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\Repository;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class EntityRepository implements EntityRepositoryInterface {
  protected EntityTypeManagerInterface $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritDoc}
   */
  public function countEntitiesOfType(string $entityTypeId): int {
    $storage = $this->entityTypeManager->getStorage($entityTypeId);

    return $storage->getQuery()->accessCheck(FALSE)->count()->execute();
  }

  /**
   * {@inheritDoc}
   */
  public function fetchEntityIdsOfType(string $entityTypeId, int $chunkSize): \Generator {
    $storage = $this->entityTypeManager->getStorage($entityTypeId);

    $entityQuery = $storage->getQuery()
      ->accessCheck(FALSE);

    $currentIndex = 0;
    while (
      $entityIds = $entityQuery
        ->range($currentIndex, $chunkSize)
        ->execute()
    ) {
      yield $entityIds;

      $currentIndex += $chunkSize;
    }
  }

  /**
   * {@inheritDoc}
   */
  public function fetchEntitiesOfType(string $entityTypeId, int $chunkSize): \Generator {
    $storage = $this->entityTypeManager->getStorage($entityTypeId);

    foreach ($this->fetchEntityIdsOfType($entityTypeId, $chunkSize) as $entityIds) {
      yield $storage->loadMultiple($entityIds);
    }
  }

  /**
   * {@inheritDoc}
   */
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
