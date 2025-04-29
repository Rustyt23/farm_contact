<?php

declare(strict_types=1);

namespace Drupal\farm_contact\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Contact type configuration entity.
 *
 * @ConfigEntityType(
 *   id = "contact_type",
 *   label = @Translation("Contact type"),
 *   label_collection = @Translation("Contact types"),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\farm_contact\Form\ContactTypeForm",
 *       "edit" = "Drupal\farm_contact\Form\ContactTypeForm",
 *       "delete" = "Drupal\farm_contact\Form\ContactTypeDeleteForm",
 *     },
 *     "list_builder" = "Drupal\farm_contact\ContactTypeListBuilder",
 *   },
 *   admin_permission = "administer contacts types",
 *   bundle_of = "contacts",
 *   config_prefix = "contact_type",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/contact_type/add",
 *     "edit-form" = "/admin/structure/contact_type/{contact_type}/edit",
 *     "delete-form" = "/admin/structure/contact_type/{contact_type}/delete",
 *     "collection" = "/admin/structure/contact_type",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "uuid",
 *   }
 * )
 */
class ContactType extends ConfigEntityBundleBase {
  // No body needed. Inherits everything needed.
}
