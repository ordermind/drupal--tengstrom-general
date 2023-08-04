<?php

declare(strict_types=1);

namespace Drupal\Tests\tengstrom_general\Unit\Fixtures;

use Drupal\Core\Entity\EntityInterface;
use Drupal\tengstrom_general\Repository\EntityRepositoryInterface;
use Ordermind\DrupalTengstromShared\Test\Fixtures\EntityStorage\ContentEntityArrayStorage;

class TestContentEntityRepository implements EntityRepositoryInterface {
  protected ContentEntityArrayStorage $storage;

  public function __construct(ContentEntityArrayStorage $storage) {
    $this->storage = $storage;
  }

  /**
   * @param int $entityId
   */
  public function hasEntityIdForType(string $entityTypeId, int|string $entityId): bool {
    return $this->storage->hasEntityId($entityId);
  }

  public function countEntitiesOfType(string $entityTypeId): int {
    return $this->storage->count();
  }

  /**
   * @return int[]
   */
  public function fetchEntityIdsOfType(string $entityTypeId, int $offset = 0, int $chunkSize = 100): array {
    return array_map(
      fn (EntityInterface $entity) => (int) $entity->id(),
      $this->fetchEntitiesOfType($entityTypeId, $offset, $chunkSize)
    );
  }

  public function fetchEntitiesOfType(string $entityTypeId, int $offset = 0, int $chunkSize = 100): array {
    $items = $this->storage->loadAll();

    return array_slice($items, $offset, $chunkSize);
  }

  public function fetchFirstEntityOfType(string $entityTypeId): ?EntityInterface {
    $items = $this->storage->loadAll();

    return reset($items);
  }

}
