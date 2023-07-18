<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\Drush\Commands;

use Drupal\Core\Site\Settings;
use Drush\Drupal\Commands\pm\PmCommands;

class DevModulesCommands extends PmCommands {

  /**
   * Command for installing dev modules
   *
   * @command tengstrom-general:install-dev-modules
   */
  public function installDevModules(): void {
    parent::install($this->getDevModules());
  }

  /**
   * Command for uninstalling dev modules
   *
   * @command tengstrom-general:uninstall-dev-modules
   */
  public function uninstallDevModules() {
    parent::uninstall($this->getDevModules());
  }

  protected function getDevModules(): array {
    $devModules = Settings::get('dev_modules', []);
    if (!$devModules) {
      throw new \LogicException('No dev modules could be found');
    }

    return $devModules;
  }

}
