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
  public function hasEntityIdForType(string $entityTypeId, int $entityId): bool {
    $storage = $this->entityTypeManager->getStorage($entityTypeId);
    $identifierField = $storage->getEntityTypeId();

    $result = $storage
      ->getQuery()
      ->accessCheck(FALSE)
      ->condition($identifierField, $entityId, '=')
      ->count()
      ->execute();

    return $result > 0;
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
  public function fetchEntityIdsOfType(string $entityTypeId, int $offset = 0, int $chunkSize = 100): array {
    $storage = $this->entityTypeManager->getStorage($entityTypeId);

    return $storage->getQuery()
      ->accessCheck(FALSE)
      ->range($offset, $chunkSize)
      ->execute();
  }

  /**
   * {@inheritDoc}
   */
  public function fetchEntitiesOfType(string $entityTypeId, int $offset = 0, int $chunkSize = 100): array {
    $storage = $this->entityTypeManager->getStorage($entityTypeId);

    $entityIds = $this->fetchEntityIdsOfType($entityTypeId, $offset, $chunkSize);
    if (!$entityIds) {
      return [];
    }

    return $storage->loadMultiple($entityIds);
  }

  /**
   * {@inheritDoc}
   */
  public function fetchFirstEntityOfType(string $entityTypeId): ?EntityInterface {
    $storage = $this->entityTypeManager->getStorage($entityTypeId);

    $entityIds = $this->fetchEntityIdsOfType($entityTypeId, 0, 1);
    if (!$entityIds) {
      return NULL;
    }

    return $storage->load(reset($entityIds));
  }

}
