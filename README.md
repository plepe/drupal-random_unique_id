# drupal-random_unique_id
Add a field for random unique IDs

This modules adds a field, which assigns random unique IDs, if left empty. If a duplicate ID has been entered interactively, the uniqueness is not guaranteed, you may use the project [Unique content field validation](https://www.drupal.org/project/unique_content_field_validation) (which is a dependency of this module anyway) for this.

You may set the following configuration options:
* Length of the unique ID
* Prefix and Suffix
* List of characters to build the ID from
