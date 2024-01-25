<?php

namespace Drupal\random_unique_id\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'Plain' formatter for 'random_unique_id' fields.
 *
 * @FieldFormatter(
 *   id = "random_unique_id_default",
 *   label = @Translation("Plain"),
 *   field_types = {
 *     "random_unique_id"
 *   }
 * )
 */
class RandomUniqueIDFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $element = [];

    foreach ($items as $delta => $item) {
      $element[$delta] = ['#markup' => $item->value];
    }

    return $element;
  }

}
