<?php

declare(strict_types=1);

namespace Drupal\tengstrom_general\HookHandlers;

use Drupal\Core\Security\TrustedCallbackInterface;

class ElementInfoAlterHandler implements TrustedCallbackInterface {

  public static function trustedCallbacks() {
    return ['hideFormatElements'];
  }

  public function alter(array &$info): void {
    if (!isset($info['text_format'])) {
      return;
    }

    $info['text_format']['#pre_render'][] = [
      static::class,
      'hideFormatElements',
    ];
  }

  /**
   * Additional #pre_render callback for 'text_format' elements.
   *
   * Hides help and guidelines elements according to configuration.
   */
  public static function hideFormatElements(array $element) {
    $hideFormatSection = !empty($element['#hide_format_section']);
    $hideHelp = !empty($element['#hide_help']);
    $hideGuideLines = !empty($element['#hide_guidelines']);
    $formatSectionExists = !empty($element['format']);
    $helpElementExists = !empty($element['format']['help']);
    $guidelinesElementExists = !empty($element['format']['guidelines']);

    // If there is no more than one allowed format and that one format
    // has already been selected, always hide the whole format section.
    // Conversely, if there is more than one allowed format, never hide
    // the whole format section. Allow override by manual setting of
    // #hide_format_section.
    if (
      !empty($element['#allowed_formats'])
      && count($element['#allowed_formats']) == 1
      && !empty($element['#format'])
      && reset($element['#allowed_formats']) === $element['#format']
      && !isset($element['#hide_format_section'])
    ) {
      $hideFormatSection = TRUE;
    }
    elseif (!empty($element['#allowed_formats']) && count($element['#allowed_formats']) > 1) {
      $hideFormatSection = FALSE;
    }

    if (($hideHelp || $hideFormatSection) && $helpElementExists) {
      $element['format']['help']['#access'] = FALSE;
    }

    if (($hideGuideLines || $hideFormatSection) && $guidelinesElementExists) {
      $element['format']['guidelines']['#access'] = FALSE;
    }

    if ($hideFormatSection && $formatSectionExists) {
      $element['format']['#attributes']['class'][] = 'hidden';
    }

    return $element;
  }

}
