<?php

namespace Drupal\farm_contact\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_contact\Entity\Contact;

/**
 * Form for adding a new Contact.
 */
class AddContactForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_contact_add_contact_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['display_as'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Display Name'),
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
    ];

    $form['phone_number'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone Number'),
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save Contact'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Create and save a new Contact entity.
    $contact = Contact::create([
      'display_as' => $form_state->getValue('display_as'),
      'email' => $form_state->getValue('email'),
      'phone_number' => $form_state->getValue('phone_number'),
      'status' => 1,
    ]);

    $contact->save();

    $this->messenger()->addStatus($this->t('Contact @name has been saved.', ['@name' => $form_state->getValue('display_as')]));

    // Optionally, reset form after submission.
    $form_state->setRebuild();
  }

}
