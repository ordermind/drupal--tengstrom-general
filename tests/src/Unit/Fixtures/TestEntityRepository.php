<?php

declare(strict_types=1);

namespace Drupal\Tests\tengstrom_general\Unit\Fixtures;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\tengstrom_general\Repository\EntityRepositoryInterface;
use Ordermind\DrupalTengstromShared\Test\Fixtures\EntityStorage\ConfigEntityArrayStorage;
use Ordermind\DrupalTengstromShared\Test\Fixtures\EntityStorage\ContentEntityArrayStorage;

class TestEntityRepository implements EntityRepositoryInterface {
  protected EntityTypeManagerInterface $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * @param string $entityId
   */
  public function hasEntityIdForType(string $entityTypeId, int|string $entityId): bool {
    return $this->getStorage($entityTypeId)->hasEntityId($entityId);
  }

  public function countEntitiesOfType(string $entityTypeId): int {
    return $this->getStorage($entityTypeId)->count();
  }

  /**
   * @return string[]
   */
  public function fetchEntityIdsOfType(string $entityTypeId, int $offset = 0, int $chunkSize = 100): array {
    return array_map(
      fn (EntityInterface $entity) => (string) $entity->id(),
      $this->fetchEntitiesOfType($entityTypeId, $offset, $chunkSize)
    );
  }

  public function fetchEntitiesOfType(string $entityTypeId, int $offset = 0, int $chunkSize = 100): array {
    $items = $this->getStorage($entityTypeId)->loadAll();

    return array_slice($items, $offset, $chunkSize);
  }

  public function fetchFirstEntityOfType(string $entityTypeId): ?EntityInterface {
    $items = $this->getStorage($entityTypeId)->loadAll();

    return reset($items);
  }

  protected function getStorage(string $entityTypeId): ContentEntityArrayStorage | ConfigEntityArrayStorage {
    return $this->entityTypeManager->getStorage($entityTypeId);
  }

}
