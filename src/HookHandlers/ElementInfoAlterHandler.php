<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers;

use Drupal\Core\Security\TrustedCallbackInterface;

class ElementInfoAlterHandler implements TrustedCallbackInterface {

  public static function trustedCallbacks() {
    return ['preRenderTextFormat'];
  }

  public function alter(array &$info): void {
    if (!isset($info['text_format'])) {
      return;
    }

    $info['text_format']['#pre_render'][] = [
      static::class,
      'preRenderTextFormat',
    ];
  }

  /**
   * Additional #pre_render callback for 'text_format' elements.
   *
   * Hides help and guidelines elements according to configuration.
   */
  public static function preRenderTextFormat(array $element) {
    if (!empty($element['#hide_help']) && !empty($element['format']['help'])) {
      $element['format']['help']['#access'] = FALSE;
    }

    if (!empty($element['#hide_guidelines']) && !empty($element['format']['guidelines'])) {
      $element['format']['guidelines']['#access'] = FALSE;
    }

    return $element;
  }

}
