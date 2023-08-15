<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Url;

class CancelButtonDestinationAlterHandler {

  public function __construct(protected ConfigFactoryInterface $configFactory) {}

  public function alter(Url &$destination, array $context): void {
    $this->overrideCanonicalPageDestination($destination);
  }

  protected function overrideCanonicalPageDestination(Url &$destination): void {
    if ($destination->isExternal()) {
      return;
    }

    preg_match('/entity\.[^.]+\.canonical/', $destination->getRouteName(), $matches);
    if (empty($matches)) {
      return;
    }

    $frontPageRoute = $this->configFactory->get('system.site')->get('page.front');

    $destination = Url::fromUserInput($frontPageRoute);
  }

}
