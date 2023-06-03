<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers\PreprocessHandlers;

interface PreprocessHandlerInterface {

  public function preprocess(array &$variables): void;

}
