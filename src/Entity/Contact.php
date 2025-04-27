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
 *   id = "contact",
 *   label = @Translation("Contact"),
 *   bundle_label = @Translation("Contact type"),
 *   label_collection = @Translation("Contacts"),
 *   label_singular = @Translation("contact"),
 *   label_plural = @Translation("contacts"),
 *   label_count = @PluralTranslation(
 *     singular = "@count contact",
 *     plural = "@count contacts",
 *   ),
 *   handlers = {
 *     "storage" = "Drupal\farm_contact\ContactStorage",
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
 *   base_table = "contact",
 *   data_table = "contact_field_data",
 *   revision_table = "contact_revision",
 *   translatable = TRUE,
 *   revisionable = TRUE,
 *   show_revision_ui = TRUE,
 *   admin_permission = "administer contacts",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "revision_id",
 *     "bundle" = "type",
 *     "label" = "full_name",
 *     "owner" = "uid",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *   },
 *   bundle_entity_type = "contact_type",
 *   field_ui_base_route = "entity.contact_type.edit_form",
 *   common_reference_target = TRUE,
 *   permission_granularity = "bundle",
 *   links = {
 *     "canonical" = "/contact/{contact}",
 *     "add-page" = "/contact/add",
 *     "add-form" = "/contact/add/{contact_type}",
 *     "collection" = "/admin/content/contact",
 *     "edit-form" = "/contact/{contact}/edit",
 *     "delete-form" = "/contact/{contact}/delete",
 *     "delete-multiple-form" = "/contact/delete",
 *     "revision" = "/contact/{contact}/revisions/{contact_revision}/view",
 *     "revision-revert-form" = "/contact/{contact}/revisions/{contact_revision}/revert",
 *     "version-history" = "/contact/{contact}/revisions",
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log_message",
 *   },
 * )
 */
class Contact extends RevisionableContentEntityBase implements ContactInterface {

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
  public function getName(): string {
    return $this->get('name')->value ?? '';
  }

  /**
   * {@inheritdoc}
   */
  public function setName(string $name): static {
    $this->set('name', $name);
    return $this;
  }
  /**
   * {@inheritdoc}
   */
  public function getFullName() {
    return $this->get('full_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFullName(string $full_name) {
    $this->set('full_name', $full_name);
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
  $bundle = $this->bundle();
  $bundle_info = \Drupal::service('entity_type.bundle.info')->getBundleInfo('contact');
  return $bundle_info[$bundle]['label'] ?? '';
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

    $fields['full_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Full name'))
      ->setDescription(t('The full name of the contact.'))
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setSetting('text_processing', 0)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of the author of the contact.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDefaultValueCallback(static::class . '::getCurrentUserId')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 12,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the contact was created.'))
      ->setRevisionable(TRUE)
      ->setDefaultValueCallback(static::class . '::getRequestTime')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'timestamp',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 13,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time the contact was last edited.'))
      ->setRevisionable(TRUE);

    return $fields;
  }

}
