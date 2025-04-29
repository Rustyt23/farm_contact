<?php

declare(strict_types=1);

namespace Drupal\farm_contact\Entity;

use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionLogEntityTrait;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\entity\Revision\RevisionableContentEntityBase;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the Contact entity.
 *
 * @ingroup farm_contact
 *
 * @ContentEntityType(
 *   id = "contacts",
 *   label = @Translation("Contacts"),
 *   bundle_label = @Translation("Contact type"),
 *   label_collection = @Translation("Contacts"),
 *   label_singular = @Translation("contacts"),
 *   label_plural = @Translation("contacts"),
 *   label_count = @PluralTranslation(
 *     singular = "@count contacts",
 *     plural = "@count contacts",
 *   ),
 *   handlers = {
 *     "storage" = "Drupal\farm_contact\Storage\ContactStorage",
 *     "access" = "Drupal\entity\UncacheableEntityAccessControlHandler",
 *     "list_builder" = "Drupal\farm_contact\ContactListBuilder",
 *     "permission_provider" = "Drupal\entity\UncacheableEntityPermissionProvider",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\farm_contact\Form\ContactForm",
 *       "edit" = "Drupal\farm_contact\Form\ContactForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\AdminHtmlRouteProvider",
 *       "revision" = "Drupal\entity\Routing\RevisionRouteProvider",
 *     },
 *     "local_task_provider" = {
 *       "default" = "Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
 *   },
 *   base_table = "contacts",
 *   data_table = "contact_field_data",
 *   revision_table = "contact_revision",
 *   translatable = TRUE,
 *   revisionable = TRUE,
 *   show_revision_ui = TRUE,
 *   admin_permission = "administer contacts",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "revision_id",
 *     "label" = "first_name",
 *     "owner" = "uid",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *   },
 *   field_ui_base_route = "entity.contact_type.edit_form",
 *   common_reference_target = TRUE,
 *   permission_granularity = "bundle",
 *   links = {
 *     "canonical" = "/contacts/{contacts}",
 *     "add-page" = "/contacts/add",
 *     "add-form" = "/contacts/add/{contact_type}",
 *     "collection" = "/admin/content/contacts",
 *     "edit-form" = "/contacts/{contacts}/edit",
 *     "delete-form" = "/contacts/{contacts}/delete",
 *     "delete-multiple-form" = "/contacts/delete",
 *     "revision" = "/contacts/{contacts}/revisions/{contact_revision}/view",
 *     "revision-revert-form" = "/contacts/{contacts}/revisions/{contact_revision}/revert",
 *     "version-history" = "/contacts/{contacts}/revisions",
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log_message",
 *   },
 * )
 */
class Contacts extends RevisionableContentEntityBase implements ContactInterface {

  use EntityChangedTrait;
  use EntityOwnerTrait;
  use RevisionLogEntityTrait;

  /**
   * {@inheritdoc}
   */
  public function label() {
    return $this->getFullName();
  }

  /**
   * {@inheritdoc}
   */
  public function getFullName() {
    return $this->get('first_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFullName(string $first_name) {
    $this->set('first_name', $first_name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime(): int {
    return (int) $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime(int $timestamp): static {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getBundleLabel(): string {
    // Return an empty string since bundles are no longer used.
    return '';
  }

  /**
   * {@inheritdoc}
   */
  public function getArchivedTime(): ?int {
    return $this->get('archived')->value ? (int) $this->get('archived')->value : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function setArchivedTime(int|string|null $timestamp): static {
    $this->set('archived', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function getCurrentUserId() {
    return [\Drupal::currentUser()->id()];
  }

  /**
   * {@inheritdoc}
   */
  public static function getRequestTime() {
    return \Drupal::time()->getRequestTime();
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);
    $fields += static::ownerBaseFieldDefinitions($entity_type);
    $fields += static::revisionLogBaseFieldDefinitions($entity_type);

    // Add the fields that were missing
    $fields['display_as'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Display As'))
      ->setRequired(FALSE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE);
    
    $fields['photo'] = BaseFieldDefinition::create('image')
    ->setLabel(t('Photo'))
    ->setDescription(t('Upload a photo for this contact.'))
    ->setRequired(FALSE)
    ->setDisplayOptions('view', [
      'label' => 'above',
      'type' => 'image',
      'weight' => -5,
    ])
    ->setDisplayOptions('form', [
      'type' => 'image_image',
      'weight' => -5,
      'settings' => [
        'file_directory' => 'contact_photos',
        'alt_field' => FALSE,
        'title_field' => FALSE,
      ],
    ])
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

    $fields['state'] = BaseFieldDefinition::create('string')
      ->setLabel(t('State'))
      ->setRequired(FALSE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 6,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 6,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['country'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Country'))
      ->setRequired(FALSE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 7,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 7,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['phone_location'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Phone Location'))
      ->setRequired(FALSE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 8,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 8,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['email_location'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Email Location'))
      ->setRequired(FALSE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 9,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 9,
      ])
      ->setDisplayConfigurable('form', TRUE);

      $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time when the contact was last edited.'))
      ->setTranslatable(TRUE)
      ->setRevisionable(TRUE);
    $fields['ranch_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Ranch Name'))
      ->setRequired(FALSE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -3,
      ])
      ->setDisplayConfigurable('form', TRUE);
    // Add Email field
    $fields['email'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Email'))
    ->setRequired(FALSE)
    ->setSetting('max_length', 255)
    ->setDisplayOptions('view', [
      'label' => 'above',
      'type' => 'string',
      'weight' => 0,
    ])
    ->setDisplayOptions('form', [
      'type' => 'string_textfield',
      'weight' => 0,
    ])
    ->setDisplayConfigurable('form', TRUE);

    // Add Phone Number field
    $fields['phone_number'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Phone Number'))
    ->setRequired(FALSE)
    ->setSetting('max_length', 255)
    ->setDisplayOptions('view', [
      'label' => 'above',
      'type' => 'string',
      'weight' => 1,
    ])
    ->setDisplayOptions('form', [
      'type' => 'string_textfield',
      'weight' => 1,
    ])
    ->setDisplayConfigurable('form', TRUE);
    $fields['first_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('First Name'))
      ->setRequired(FALSE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -2,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['last_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Last Name'))
      ->setRequired(FALSE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -1,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -1,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setRequired(FALSE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setRequired(FALSE)
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'boolean',
        'weight' => 1,
      ])
      ->setDisplayOptions('form', [
        'type' => 'checkbox',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['comments'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Comments'))
      ->setRequired(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_long',
        'weight' => 2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['address'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Address'))
      ->setRequired(FALSE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 3,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['city'] = BaseFieldDefinition::create('string')
      ->setLabel(t('City'))
      ->setRequired(FALSE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 4,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['zip_code'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Zip Code'))
      ->setRequired(FALSE)
      ->setSetting('max_length', 10)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 5,
      ])
      ->setDisplayConfigurable('form', TRUE);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return $this->getFullName();  // Or directly return the full_name field value
  }

  /**
   * {@inheritdoc}
   */
  public function setName(string $name): static {
    $this->setFullName($name);  // Set the name using the full_name field
    return $this;
  }
}
