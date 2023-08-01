<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\Repository;

use Drupal\Core\Entity\EntityInterface;

interface EntityRepositoryInterface {

  /**
   * Count all persisted entities of a certain entity type.
   */
  public function countEntitiesOfType(string $entityTypeId): int;

  /**
   * Fetch all entity ids of a certain type. This method yields chunks of entity
   * ids using a generator.
   *
   * @return \Generator|int[][]
   */
  public function fetchEntityIdsOfType(string $entityTypeId, int $chunkSize): \Generator;

  /**
   * Fetch all entities of a certain type. This method yields chunks of entities
   * using a generator.
   *
   * @return \Generator|EntityInterface[][]
   */
  public function fetchEntitiesOfType(string $entityTypeId, int $chunkSize): \Generator;

  /**
   * Fetch the first entity of a certain type
   *
   * @return \Drupal\Core\Entity\EntityInterface|null The fetched entity, or null if
   * no entity could be found.
   */
  public function fetchFirstEntityOfType(string $entityTypeId): ?EntityInterface;

}
