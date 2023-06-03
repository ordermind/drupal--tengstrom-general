<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers;

class PageAttachmentsHandler {

  public function execute(array &$attachments): void {
    $this->attachGlobalLibraryToEveryPage($attachments);
  }

  protected function attachGlobalLibraryToEveryPage(array &$attachments): void {
    $attachments['#attached']['library'][] = 'tengstrom_general/global';
  }

}
