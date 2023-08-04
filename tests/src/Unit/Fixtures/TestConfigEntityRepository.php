<?php

declare(strict_types=1);

namespace Drupal\Tests\tengstrom_general\Unit\Fixtures;

use Drupal\Core\Entity\EntityInterface;
use Drupal\tengstrom_general\Repository\EntityRepositoryInterface;
use Ordermind\DrupalTengstromShared\Test\Fixtures\EntityStorage\ConfigEntityArrayStorage;

class TestConfigEntityRepository implements EntityRepositoryInterface {
  protected ConfigEntityArrayStorage $storage;

  public function __construct(ConfigEntityArrayStorage $storage) {
    $this->storage = $storage;
  }

  /**
   * @param string $entityId
   */
  public function hasEntityIdForType(string $entityTypeId, int|string $entityId): bool {
    return $this->storage->hasEntityId($entityId);
  }

  public function countEntitiesOfType(string $entityTypeId): int {
    return $this->storage->count();
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
    $items = $this->storage->loadAll();

    return array_slice($items, $offset, $chunkSize);
  }

  public function fetchFirstEntityOfType(string $entityTypeId): ?EntityInterface {
    $items = $this->storage->loadAll();

    return reset($items);
  }

}
