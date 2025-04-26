<?php

namespace Drupal\farm_contact\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Contact entity.
 *
 * @ContentEntityType(
 *   id = "contact",
 *   label = @Translation("Contact"),
 *   base_table = "contact",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "display_as",
 *     "uuid" = "uuid"
 *   },
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\Core\Entity\ContentEntityForm",
 *       "edit" = "Drupal\Core\Entity\ContentEntityForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "access" = "Drupal\Core\Entity\EntityAccessControlHandler"
 *   },
 *   links = {
 *     "add-form" = "/farm-contact/contact/add",
 *     "canonical" = "/farm-contact/contact/{contact}",
 *     "edit-form" = "/farm-contact/contact/{contact}/edit",
 *     "delete-form" = "/farm-contact/contact/{contact}/delete"
 *   },
 *   field_ui_base_route = "entity.contact.admin_form"
 * )
 */
class Contact extends ContentEntityBase {

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setReadOnly(TRUE)
      ->setRequired(TRUE);

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setReadOnly(TRUE)
      ->setRequired(TRUE)
      ->setSetting('unsigned', TRUE);

    // Contact Details Group
    $fields['display_as'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Display As'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ]);

    $fields['ranch_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Ranch Name'))
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 1,
      ]);

    $fields['first_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('First Name'))
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 2,
      ]);

    $fields['last_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Last Name'))
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 3,
      ]);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 4,
      ]);

    // $fields['website'] = BaseFieldDefinition::create('uri')
    //   ->setLabel(t('Website'))
    //   ->setDisplayOptions('form', [
    //     'type' => 'link_default',
    //     'weight' => 5,
    //   ]);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 6,
      ]);

    $fields['tags'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Tags'))
      ->setSetting('target_type', 'taxonomy_term')
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete_tags',
        'weight' => 7,
      ]);

    $fields['comments'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Comments'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 8,
      ]);

    // $fields['photo'] = BaseFieldDefinition::create('image')
    //   ->setLabel(t('Photo'))
    //   ->setSettings([
    //     'file_directory' => 'contact_photos',
    //     'file_extensions' => 'png jpg jpeg',
    //     'alt_field' => FALSE,
    //     'title_field' => FALSE,
    //   ])
    //   ->setDisplayOptions('form', [
    //     'type' => 'image_image',
    //     'weight' => 9,
    //   ]);

    // Addresses Group
    $fields['address'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Address'))
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 10,
      ]);

    $fields['city'] = BaseFieldDefinition::create('string')
      ->setLabel(t('City'))
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 11,
      ]);

    $fields['state'] = BaseFieldDefinition::create('string')
      ->setLabel(t('State'))
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 12,
      ]);

    $fields['zip'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Zip'))
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 13,
      ]);

    $fields['country'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Country'))
      ->setSettings([
        'allowed_values' => [
          'India' => 'India',
          'USA' => 'USA',
          'Canada' => 'Canada',
          // Add more countries here
        ],
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 14,
      ]);

    // Phone Numbers Group
    $fields['phone_number'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Phone Number'))
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 15,
      ]);

    $fields['phone_location'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Phone Location'))
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 16,
      ]);

    // Email Addresses Group
    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email'))
      ->setDisplayOptions('form', [
        'type' => 'email_default',
        'weight' => 17,
      ]);

    $fields['email_location'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Email Location'))
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 18,
      ]);

    return $fields;
  }
}
