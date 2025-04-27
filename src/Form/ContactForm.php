<?php

namespace Drupal\farm_contact\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the Contact entity edit forms.
 */
class ContactForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = $entity->save();

    if ($status === SAVED_NEW) {
      $this->messenger()->addStatus($this->t('The contact %label has been created.', ['%label' => $entity->label()]));
    }
    else {
      $this->messenger()->addStatus($this->t('The contact %label has been updated.', ['%label' => $entity->label()]));
    }

    $form_state->setRedirect('entity.contact.canonical', ['contact' => $entity->id()]);
  }

}