<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers\PreprocessHandlers;

use Drupal\Core\Link;
use Ordermind\DrupalTengstromShared\HookHandlers\PreprocessHandlerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PreprocessEntityAddListHandler implements PreprocessHandlerInterface {
  protected RequestStack $requestStack;

  public function __construct(RequestStack $requestStack) {
    $this->requestStack = $requestStack;
  }

  public function preprocess(array &$variables): void {
    $this->passDestinationToCreateEntityForBundleLinks($variables);
  }

  /**
   * When creating an entity, if there are multiple bundles, there is a page
   * where you select which bundle you want to use when creating the entity.
   * This method passes on the current destination to the links so that after
   * the creation the user is redirected back to the page that they came from.
   */
  protected function passDestinationToCreateEntityForBundleLinks(array &$variables) {
    if (empty($variables['bundles'])) {
      return;
    }

    $currentDestination = $this->requestStack->getCurrentRequest()->query->get('destination');
    if (!$currentDestination) {
      return;
    }

    foreach ($variables['bundles'] as $item) {
      if (!empty($item['add_link']) && $item['add_link'] instanceof Link) {
        /** @var \Drupal\Core\Link $link */
        $link = $item['add_link'];

        $url = $link->getUrl();
        $url->setOption('query', ['destination' => $currentDestination]);
      }
    }
  }

}
