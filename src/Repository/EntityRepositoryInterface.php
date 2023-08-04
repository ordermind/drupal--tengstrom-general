<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\Repository;

use Drupal\Core\Entity\EntityInterface;

interface EntityRepositoryInterface {

  /**
   * Check if an entity id exists in the database for a certain entity type.
   */
  public function hasEntityIdForType(string $entityTypeId, int $entityId): bool;

  /**
   * Count all persisted entities of a certain entity type.
   */
  public function countEntitiesOfType(string $entityTypeId): int;

  /**
   * Fetch the entity ids of a certain type.
   *
   * @return int[]
   */
  public function fetchEntityIdsOfType(string $entityTypeId, int $offset = 0, int $chunkSize = 100): array;

  /**
   * Fetch the entities of a certain type.
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   */
  public function fetchEntitiesOfType(string $entityTypeId, int $offset = 0, int $chunkSize = 100): array;

  /**
   * Fetch the first entity of a certain type
   *
   * @return \Drupal\Core\Entity\EntityInterface|null The fetched entity, or null if
   * no entity could be found.
   */
  public function fetchFirstEntityOfType(string $entityTypeId): ?EntityInterface;

}
