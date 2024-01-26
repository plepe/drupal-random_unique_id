<?php

namespace Drupal\random_unique_id\Plugin\Field\FieldWidget;

use Drupal\Core\Datetime\DateHelper;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Plugin implementation of the 'random_unique_id' widget.
 *
 * @FieldWidget(
 *   id = "random_unique_id_widget",
 *   label = @Translation("Textfield"),
 *   field_types = {
 *    "random_unique_id"
 *   },
 *   settings = {
 *   },
 * )
 */
class RandomUniqueID extends WidgetBase {
  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $default_value = isset($items[$delta]->value) ? $items[$delta]->value : '';

    $element['value'] = $element + [
      '#type' => 'random_unique_id',
      '#default_value' => $default_value,
      '#element_validate' => [[static::class, 'validateElement']],
      '#required' => false,
      '#attributes' => ['class' => ['random_unique_id']],
    ];

    return $element;
  }

  /**
   * Form element validation handler for 'random_unique_id' element.
   *
   * Any value is okay.
   */
  public static function validateElement($element, FormStateInterface $form_state, $form) {
    return;
  }
}
