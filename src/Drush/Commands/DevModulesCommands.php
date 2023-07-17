<?php

namespace Drupal\tengstrom_general\Drush\Commands;

use Drupal\Core\Extension\ModuleInstallerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Site\Settings;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drush\Drupal\Commands\core\MessengerCommands;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A Drush commandfile.
 */
class DevModulesCommands extends MessengerCommands {
  use StringTranslationTrait;

  protected ModuleInstallerInterface $moduleInstaller;

  public function __construct(MessengerInterface $messenger, ModuleInstallerInterface $moduleInstaller) {
    parent::__construct($messenger);

    $this->moduleInstaller = $moduleInstaller;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger'),
      $container->get('module_installer')
    );
  }

  /**
   * Command for installing dev modules
   *
   * @command tengstrom-general:install-dev-modules
   */
  public function install() {
    $devModules = Settings::get('dev_modules', []);
    if (!$devModules) {
      $this->messenger->addError($this->t('No dev modules could be found'));

      return;
    }

    $this->moduleInstaller->install($devModules);
    $this->messenger->addStatus($this->t('Dev modules were successfully installed!'));
  }

  /**
   * Command for uninstalling dev modules
   *
   * @command tengstrom-general:uninstall-dev-modules
   */
  public function uninstall() {
    $devModules = Settings::get('dev_modules', []);
    if (!$devModules) {
      $this->messenger->addError($this->t('No dev modules could be found'));

      return;
    }

    $this->moduleInstaller->uninstall($devModules);
    $this->messenger->addStatus($this->t('Dev modules were successfully uninstalled!'));
  }

}
