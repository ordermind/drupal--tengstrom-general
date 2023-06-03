<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\Plugin\EntityReferenceSelection;

use Drupal\Core\Entity\Plugin\EntityReferenceSelection\DefaultSelection;

/**
 * Provides entity reference autocomplete selection that supports filters.
 *
 * @EntityReferenceSelection(
 *   id = "default:filter",
 *   label = @Translation("Filter Selection"),
 *   group = "default",
 *   weight = 0
 * )
 */
class FilterSelection extends DefaultSelection {

  /**
   * {@inheritdoc}
   */
  protected function buildEntityQuery($match = NULL, $match_operator = 'CONTAINS') {
    $query = parent::buildEntityQuery($match, $match_operator);

    $configuration = $this->getConfiguration();
    if (empty($configuration['filter'])) {
      return $query;
    }

    $this->buildQueryRecursive($query, $configuration['filter']);

    return $query;
  }

  /**
   * @param \Drupal\Core\Entity\Query\QueryInterface|ConditionInterface $query
   */
  protected function buildQueryRecursive($query, array $filters) {
    foreach ($filters as $fieldName => $value) {
      if ($fieldName === 'OR') {
        $orClause = $query->orConditionGroup();
        $this->buildQueryRecursive($orClause, $value);
        $query->condition($orClause);
      }
      else {
        $this->addConditionForFilterValue($query, $fieldName, $value);
      }
    }
  }

  /**
   * @param \Drupal\Core\Entity\Query\QueryInterface|ConditionInterface $query
   */
  protected function addConditionForFilterValue($query, string $fieldName, $value) {
    if (is_string($value) || is_numeric($value)) {
      $query->condition($fieldName, (string) $value, '=');
    }
    elseif (is_array($value)) {
      $query->condition($fieldName, $value, 'IN');
    }
  }

}
