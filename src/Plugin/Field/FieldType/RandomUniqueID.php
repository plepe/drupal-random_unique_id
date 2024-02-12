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
 *   description = @Translation("Creates random unique IDs. You may set a template and a list of characters to build the ID from"),
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

  public function preSave(): void {
    parent::preSave();

    $current_value = $this->getValue();

    if ($current_value['value'] === '') {
      $current_value['value'] = $this->createRandomUniqueID();

      $this->setValue($current_value);
    }
  }

  public function createRandomID(): string {
    $settings = [...self::defaultFieldSettings(), ...$this->getSettings()];

    $id = '';
    $char_count = strlen($settings['characters']) - 1;
    for ($i = 0; $i < $settings['length']; $i++) {
      $id .= substr($settings['characters'], random_int(0, $char_count), 1);
    }

    return $settings['prefix'] . $id . $settings['suffix'];
  }

  public function createRandomUniqueID(): string {
    do {
      $text = $this->createRandomID();
      trigger_error($text, E_USER_NOTICE);
    } while (!$this->checkUnique($text));

    return $text;
  }

  public function checkUnique ($text): bool {
    return unique_content_field_validation_field_is_unique(
      $this->getEntity()->getEntityTypeId(),
      $this->getLangcode(),
      $this->getFieldDefinition()->getName(),
      [$text],
      $this->getEntity()->bundle(),
      $this->getEntity()
    );
  }
}
