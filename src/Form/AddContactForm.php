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
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save Content'),
      '#button_type' => 'primary',
      '#weight' => -100,  // This ensures the button appears at the top
    ];
    // Define vertical tabs wrapper.
    $form['tabs'] = [
      '#type' => 'vertical_tabs',
      '#title' => $this->t('Contact Information'),
    ];
  
    // Contact Details Tab
    $form['contact_details'] = [
      '#type' => 'details',
      '#title' => $this->t('Contact Details'),
      '#group' => 'tabs',
      '#open' => TRUE,
    ];
    $form['contact_details']['display_as'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Display Name'),
      '#required' => TRUE,
    ];
    $form['contact_details']['ranch_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Ranch Name'),
    ];
    $form['contact_details']['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First Name'),
    ];
    $form['contact_details']['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last Name'),
    ];
    $form['contact_details']['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
    ];
    $form['contact_details']['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Active'),
      '#default_value' => 1,
    ];
    $form['contact_details']['tags'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Tags'),
      '#target_type' => 'taxonomy_term',
      '#tags' => TRUE,
    ];
    $form['contact_details']['comments'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Comments'),
    ];
  
    // Addresses Tab
    $form['addresses'] = [
      '#type' => 'details',
      '#title' => $this->t('Addresses'),
      '#group' => 'tabs',
    ];
    $form['addresses']['address'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Address'),
    ];
    $form['addresses']['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
    ];
    $form['addresses']['state'] = [
      '#type' => 'textfield',
      '#title' => $this->t('State'),
    ];
    $form['addresses']['zip'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Zip Code'),
    ];
    $form['addresses']['country'] = [
      '#type' => 'select',
      '#title' => $this->t('Country'),
      '#options' => [
        'India' => 'India',
        'USA' => 'USA',
        'Canada' => 'Canada',
      ],
    ];
  
    // Phone Numbers Tab
    $form['phone_numbers'] = [
      '#type' => 'details',
      '#title' => $this->t('Phone Numbers'),
      '#group' => 'tabs',
    ];
    $form['phone_numbers']['phone_number'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone Number'),
    ];
    $form['phone_numbers']['phone_location'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone Location'),
    ];
  
    // Email Addresses Tab
    $form['email_addresses'] = [
      '#type' => 'details',
      '#title' => $this->t('Email Addresses'),
      '#group' => 'tabs',
    ];
    $form['email_addresses']['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
    ];
    $form['email_addresses']['email_location'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email Location'),
    ];
  
  
    return $form;
  }
  

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $values = $form_state->getValues();

    // Create and save a new Contact entity.
    $contact = Contact::create([
      'display_as' => $values['display_as'],
      'ranch_name' => $values['ranch_name'],
      'first_name' => $values['first_name'],
      'last_name' => $values['last_name'],
      'title' => $values['title'],
      'status' => !empty($values['status']),
      'tags' => $values['tags'],
      'comments' => $values['comments'],
      'address' => $values['address'],
      'city' => $values['city'],
      'state' => $values['state'],
      'zip' => $values['zip'],
      'country' => $values['country'],
      'phone_number' => $values['phone_number'],
      'phone_location' => $values['phone_location'],
      'email' => $values['email'],
      'email_location' => $values['email_location'],
    ]);

    $contact->save();

    $this->messenger()->addStatus($this->t('Contact @name has been saved.', ['@name' => $values['display_as']]));

    $form_state->setRedirect('<front>');
  }

}
