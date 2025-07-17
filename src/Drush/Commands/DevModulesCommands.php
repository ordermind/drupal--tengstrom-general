<?php

namespace Drupal\tengstrom_general\Drush\Commands;

use Drush\Attributes as CLI;
use Drush\Commands\AutowireTrait;
use Drush\Commands\DrushCommands;
use Drupal\Core\Site\Settings;
use Drush\Commands\pm\PmCommands;

/**
 * A Drush commandfile.
 *
 */
final class DevModulesCommands extends DrushCommands {

  use AutowireTrait;

  /**
   * Constructs a DevModulesCommands object.
   */
  public function __construct(
    private PmCommands $pmCommands,
  ) {
    parent::__construct();
  }

  /**
   * Command for installing dev modules.
   */
  #[CLI\Command(name: 'tengstrom-general:install-dev-modules')]
  public function installDevModules() {
    // $this->pmCommands->install($this->getDevModules());
    $this->output()->writeln("Command temporarily disabled due to bugs in the tracer and webprofiler modules.");
  }

  /**
   * Command for uninstalling dev modules.
   */
  #[CLI\Command(name: 'tengstrom-general:uninstall-dev-modules')]
  public function uninstallDevModules() {
    // $this->pmCommands->uninstall($this->getDevModules());
    $this->output()->writeln("Command temporarily disabled due to bugs in the tracer and webprofiler modules.");
  }

  private function getDevModules(): array {
    $devModules = Settings::get('dev_modules', []);
    if (!$devModules) {
      throw new \LogicException('No dev modules could be found');
    }

    return $devModules;
  }

}
