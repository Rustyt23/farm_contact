<?php

namespace Drupal\farm_contact\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides an 'Add Contact' action.
 *
 * @Action(
 *   id = "farm_contact_add_contact_action",
 *   label = @Translation("Add Contact"),
 *   type = "any"
 * )
 */
class AddContactAction extends ActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    // Optional: Redirect, show message, etc.
    \Drupal::messenger()->addMessage($this->t('Add Contact action triggered.'));
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    return $this->accessResultAllowedIfHasPermission($account, 'access content', $return_as_object);
  }

}
