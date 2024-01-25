<?php

namespace Drupal\random_unique_id\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Plugin implementation of the 'random_unique_id' field type.
 *
 * @FieldType(
 *   id = "random_unique_id",
 *   label = @Translation("Random Unique ID"),
 *   description = {
 *     @Translation("Creates random unique IDs"),
 *     @Translation("You may set a template and a list of characters to build the ID from"),
 *   },
 *   category = @Translation("Text"),
 *   default_widget = "random_unique_id_widget",
 *   default_formatter = "random_unique_id_default"
 * )
 */
class RandomUniqueID extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {

    return [
      'columns' => [
        'value' => [
          'type' => 'char',
          'length' => 255,
          'not null' => TRUE,
          'description' => 'To store the random unique id.',
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Random Unique ID'));
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    $settings = [
      'prefix' => '',
      'suffix' => '',
      'length' => 4,
      'characters' => '0123456789',
    ] + parent::defaultFieldSettings();

    return $settings;
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    // Get base form from FileItem.
    $element = parent::fieldSettingsForm($form, $form_state);

    $settings = [...self::defaultFieldSettings(), ...$this->getSettings()];

    $element['prefix'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Prefix'),
      '#description' => $this->t('Prefix of the ID'),
      '#default_value' => $settings['prefix'],
      '#maxlength' => 255,
    ];

    $element['suffix'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Suffix'),
      '#description' => $this->t('Suffix of the ID'),
      '#default_value' => $settings['suffix'],
      '#maxlength' => 255,
    ];

    $element['length'] = [
      '#type' => 'number',
      '#title' => $this->t('Length of the ID part'),
      '#description' => $this->t('Length of the ID, not including prefix and suffix'),
      '#default_value' => $settings['length'],
      '#min' => 1,
    ];

    $element['characters'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Characters for ID'),
      '#description' => $this->t('List of characters for building the ID'),
      '#default_value' => $settings['characters'],
      '#maxlength' => 1024,
    ];

    return $element;
  }

}
