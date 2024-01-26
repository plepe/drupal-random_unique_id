<?php
namespace Drupal\random_unique_id\Element;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\FormElement;
use Drupal\Core\Render\Element\Textfield;

/**
 * Provides a form element for Random Unique ID Fields.
 *
 * @FormElement("random_unique_id")
 */
class RandomUniqueID extends Textfield {
  /**
   * {@inheritdoc}
   */
  public static function valueCallback (&$element, $input, FormStateInterface $form_state) {
    if ($input === '') {
      return 'FOOBAR';
    }

    return $input;
  }
}
