<?php

declare(strict_types=1);

namespace Drupal\Tests\tengstrom_general\Kernel\Repository;

use Drupal\Core\Entity\EntityInterface;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\Tests\node\Traits\ContentTypeCreationTrait;
use Drupal\Tests\node\Traits\NodeCreationTrait;

use function Ordermind\Helpers\Misc\xrange;

class EntityRepositoryTest extends EntityKernelTestBase {
  use ContentTypeCreationTrait;
  use NodeCreationTrait;

  protected function setUp(): void {
    parent::setUp();

    $this->enableModules(['node', 'tengstrom_general']);
    $this->installConfig(['node']);
    $this->installEntitySchema('node');
    $this->createContentType(['type' => 'bundle_1', 'name' => 'Bundle 1']);

    foreach (xrange(1, 10) as $id) {
      $this->createNode(['nid' => $id * 2, 'label' => "Node #{$id}"]);
    }
  }

  public function testCountEntitiesOfType(): void {
    /** @var \Drupal\tengstrom_general\Repository\EntityRepository $repository */
    $repository = \Drupal::service('tengstrom_general.entity_repository');
    $this->assertSame(10, $repository->countEntitiesOfType('node'));
  }

  public function testFetchEntityIdsOfType(): void {
    /** @var \Drupal\tengstrom_general\Repository\EntityRepository $repository */
    $repository = \Drupal::service('tengstrom_general.entity_repository');
    $result = $repository->fetchEntityIdsOfType('node', 2, 5);
    $expectedResult = [
      3 => '6',
      4 => '8',
      5 => '10',
      6 => '12',
      7 => '14',
    ];

    $this->assertSame($expectedResult, $result);
  }

  public function testFetchEntitiesOfType(): void {
    /** @var \Drupal\tengstrom_general\Repository\EntityRepository $repository */
    $repository = \Drupal::service('tengstrom_general.entity_repository');

    $result = array_map(fn (EntityInterface $entity) => $entity->id(), $repository->fetchEntitiesOfType('node'));

    $expectedResult = [
      2 => '2',
      4 => '4',
      6 => '6',
      8 => '8',
      10 => '10',
      12 => '12',
      14 => '14',
      16 => '16',
      18 => '18',
      20 => '20',
    ];

    $this->assertSame($expectedResult, $result);
  }

  public function testFetchFirstEntityOfType(): void {
    /** @var \Drupal\tengstrom_general\Repository\EntityRepository $repository */
    $repository = \Drupal::service('tengstrom_general.entity_repository');

    $this->assertSame('2', $repository->fetchFirstEntityOfType('node')->id());
  }

}
