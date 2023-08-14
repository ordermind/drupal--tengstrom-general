<?php

declare(strict_types=1);

namespace Drupal\Tests\tengstrom_general\Unit\HookHandlers;

use Drupal\tengstrom_general\HookHandlers\ElementInfoAlterHandler;
use Drupal\Tests\UnitTestCase;

class ElementInfoAlterHandlerTest extends UnitTestCase {

  /**
   * @dataProvider provideHideFormatElementCases
   */
  public function testHideFormatElements(
    array $expectedResult,
    array $element
  ): void {
    $this->assertSame($expectedResult, ElementInfoAlterHandler::hideFormatElements($element));
  }

  public function provideHideFormatElementCases(): \Generator {
    // Do nothing for an empty array that misses all elements.
    yield [[], []];

    // Do nothing if the 'format' element is missing.
    $element = [
      '#hide_format_section' => TRUE,
      '#hide_help' => TRUE,
      '#hide_guidelines' => TRUE,
      '#allowed_formats' => ['formatted_html'],
      '#format' => 'formatted_html',
    ];
    yield [$element, $element];

    // Do not hide help if #hide_help is FALSE,
    // no matter whether a help element exists or not.
    foreach ([
      [
        '#hide_help' => FALSE,
        'format' => [],
      ],
      [
        '#hide_help' => FALSE,
        'format' => ['help' => ['#access' => TRUE]],
      ],
    ] as $element) {
      yield [$element, $element];
    }

    // Hide help element if #hide_help is TRUE and the help element is present.
    yield [
      [
        '#hide_help' => TRUE,
        'format' => ['help' => ['#access' => FALSE]],
      ],
      [
        '#hide_help' => TRUE,
        'format' => ['help' => ['#access' => TRUE]],
      ],
    ];

    // Do not hide guidelines if #hide_guidelines is FALSE,
    // no matter whether a guidelines element exists or not.
    foreach ([
      [
        '#hide_guidelines' => FALSE,
        'format' => [],
      ],
      [
        '#hide_guidelines' => FALSE,
        'format' => ['guidelines' => ['#access' => TRUE]],
      ],
    ] as $element) {
      yield [$element, $element];
    }

    // Hide guidelines if #hide_guidelines is TRUE and the guidelines element is present.
    yield [
      [
        '#hide_guidelines' => TRUE,
        'format' => ['guidelines' => ['#access' => FALSE]],
      ],
      [
        '#hide_guidelines' => TRUE,
        'format' => ['guidelines' => ['#access' => TRUE]],
      ],
    ];

    // Do not hide whole section if #hide_format_section is FALSE,
    // no matter the presence of elements.
    yield [
      [
        '#hide_format_section' => FALSE,
        'format' => ['help' => ['#access' => TRUE], 'guidelines' => ['#access' => TRUE]],
      ],
      [
        '#hide_format_section' => FALSE,
        'format' => ['help' => ['#access' => TRUE], 'guidelines' => ['#access' => TRUE]],
      ],
    ];

    // Add hidden class to format element if #hide_format_section is TRUE and
    // the format element is present.
    yield [
      [
        '#hide_format_section' => TRUE,
        'format' => ['#access' => TRUE, '#attributes' => ['class' => ['hidden']]],
      ],
      [
        '#hide_format_section' => TRUE,
        'format' => ['#access' => TRUE],
      ],
    ];

    // Hide help section if #hide_format_section is TRUE and the help section is present.
    yield [
      [
        '#hide_format_section' => TRUE,
        'format' => ['help' => ['#access' => FALSE], '#attributes' => ['class' => ['hidden']]],
      ],
      [
        '#hide_format_section' => TRUE,
        'format' => ['help' => ['#access' => TRUE]],
      ],
    ];

    // Hide guidelines section if #hide_format_section is TRUE and the guidelines section is present.
    yield [
      [
        '#hide_format_section' => TRUE,
        'format' => ['guidelines' => ['#access' => FALSE], '#attributes' => ['class' => ['hidden']]],
      ],
      [
        '#hide_format_section' => TRUE,
        'format' => ['guidelines' => ['#access' => TRUE]],
      ],
    ];

    // Hide both help and guidelines if #hide_format_section is TRUE and the elements are present.
    yield [
      [
        '#hide_format_section' => TRUE,
        'format' => [
          'help' => ['#access' => FALSE],
          'guidelines' => ['#access' => FALSE],
          '#attributes' => ['class' => ['hidden']],
        ],
      ],
      [
        '#hide_format_section' => TRUE,
        'format' => ['help' => ['#access' => TRUE], 'guidelines' => ['#access' => TRUE]],
      ],
    ];

    foreach ([
      // Do nothing based on allowed formats if #allowed_formats is missing.
      [
        '#format' => 'formatted_html',
        'format' => ['help' => ['#access' => TRUE], 'guidelines' => ['#access' => TRUE]],
      ],
      // Do nothing based on allowed formats if #allowed_formats contains more than one element.
      [
        '#format' => 'formatted_html',
        '#allowed_formats' => ['text_plain', 'formatted_html'],
        'format' => ['help' => ['#access' => TRUE], 'guidelines' => ['#access' => TRUE]],
      ],
      // Do nothing based on allowed formats if #format is missing.
      [
        '#allowed_formats' => ['formatted_html'],
        'format' => ['help' => ['#access' => TRUE], 'guidelines' => ['#access' => TRUE]],
      ],
      // Do nothing based on allowed formats if #format does not match the single allowed format.
      [
        '#format' => 'text_plain',
        '#allowed_formats' => ['formatted_html'],
        'format' => ['help' => ['#access' => TRUE], 'guidelines' => ['#access' => TRUE]],
      ],
      // Do nothing based on allowed formats if #hide_format_section is manually set to FALSE.
      [
        '#hide_format_section' => FALSE,
        '#format' => 'formatted_html',
        '#allowed_formats' => ['formatted_html'],
        'format' => ['help' => ['#access' => TRUE], 'guidelines' => ['#access' => TRUE]],
      ],
    ] as $element) {
      yield [$element, $element];
    }

    // Hide format section if #format matches the single allowed format and there is no override.
    yield [
      [
        '#format' => 'formatted_html',
        '#allowed_formats' => ['formatted_html'],
        'format' => [
          'help' => ['#access' => FALSE],
          'guidelines' => ['#access' => FALSE],
          '#attributes' => ['class' => ['hidden']],
        ],
      ],
      [
        '#format' => 'formatted_html',
        '#allowed_formats' => ['formatted_html'],
        'format' => ['help' => ['#access' => TRUE], 'guidelines' => ['#access' => TRUE]],
      ],
    ];

    // Do not hide whole section if there is more than one allowed format, even if
    // #hide_format_section is manually set to TRUE.
    yield [
      [
        '#hide_format_section' => TRUE,
        '#format' => 'formatted_html',
        '#allowed_formats' => ['text_plain', 'formatted_html'],
        'format' => ['help' => ['#access' => TRUE], 'guidelines' => ['#access' => TRUE]],
      ],
      [
        '#hide_format_section' => TRUE,
        '#format' => 'formatted_html',
        '#allowed_formats' => ['text_plain', 'formatted_html'],
        'format' => ['help' => ['#access' => TRUE], 'guidelines' => ['#access' => TRUE]],
      ],
    ];
  }

}
