<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\Menu;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultAllowed;
use Drupal\Core\Menu\DefaultMenuLinkTreeManipulators as OverriddenManipulators;
use Drupal\Core\Menu\MenuLinkTreeElement;

class DefaultMenuLinkTreeManipulators extends OverriddenManipulators {

  /**
   * {@inheritdoc}
   */
  public function checkAccess(array $tree) {
    return parent::checkAccess($this->revokeAccessToNoLinkParentsWithoutChildren($tree));
  }

  protected function revokeAccessToNoLinkParentsWithoutChildren(array $tree): array {
    foreach ($tree as $key => $element) {
      // Other menu tree manipulators may already have calculated access, do not
      // overwrite the existing value in that case.
      if (isset($element->access)) {
        continue;
      }

      /** @var \Drupal\Core\Menu\MenuLinkTreeElement $element */
      if ($element->link->getRouteName() !== '<nolink>') {
        continue;
      }

      if (empty($element->subtree)) {
        continue;
      }

      $tree[$key]->subtree = $this->checkAccess($element->subtree);

      $hasAccessToChild = array_reduce($element->subtree, function (bool $carry, MenuLinkTreeElement $childElement) {
        if ($childElement->access instanceof AccessResultAllowed) {
          return TRUE;
        }

        return $carry;
      }, FALSE);

      if ($hasAccessToChild) {
        $tree[$key]->access = AccessResult::allowed();
      }
      else {
        $tree[$key]->access = AccessResult::forbidden('The user does not have access to any of the children of this menu nolink');
      }
    }

    return $tree;
  }

}
