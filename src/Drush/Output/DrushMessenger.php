<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\Drush\Output;

use Drush\Drush;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

class DrushMessenger {

  public function success(string $message): void {
    $this->getOutput()->writeln('<success>[success]</success> ' . $message);
  }

  public function notice(string $message): void {
    $this->getOutput()->writeln('<notice>[notice]</notice> ' . $message);
  }

  public function error(string $message): void {
    $this->getOutput()->writeln('<error>[error]</error> <error>' . $message . '</error>');
  }

  public function blankLine(): void {
    $this->getOutput()->writeln('');
  }

  /**
   * This is a workaround for the fact that Drush uses a separate DI container,
   * which means that we can't inject the drush output class as a service
   * because then it will throw an error when using the web interface because
   * the service only exists in the Drush container, not in the regular Drupal
   * container.
   */
  public function getOutput(): OutputInterface {
    $initializeNewInstance = function () {
      $output = Drush::output();
      $formatter = $output->getFormatter();

      $successStyle = new OutputFormatterStyle('white', 'green', ['font-weight' => 'bold']);
      $formatter->setStyle('success', $successStyle);

      $noticeStyle = new OutputFormatterStyle('white', 'cyan', ['font-weight' => 'bold']);
      $formatter->setStyle('notice', $noticeStyle);

      $errorStyle = new OutputFormatterStyle('white', 'red', ['font-weight' => 'bold']);
      $formatter->setStyle('error', $errorStyle);

      return $output;
    };

    $output = &drupal_static(static::class . '::getOutput');
    if (!$output) {
      $output = $initializeNewInstance();
    }

    return $output;
  }

}
