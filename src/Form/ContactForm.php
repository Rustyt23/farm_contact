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
  public function getFormId() {
    return 'farm_contact_contact_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    // Add Save button
    $form['actions']['submit']['#value'] = $this->t('Save Contact');
    $form['#attributes']['class'][] = 'contact-form-custom';

    // Group fields in Tabs
    $form['tabs'] = [
      '#type' => 'vertical_tabs',
      '#title' => $this->t('Contact Information'),
      '#weight' => -10,
    ];

    // Contact Details Tab
    $form['contact_details'] = [
      '#type' => 'details',
      '#title' => $this->t('Contact Details'),
      '#group' => 'tabs',
      '#open' => TRUE,
    ];
    $form['contact_details']['display_as'] = $form['display_as'];
    $form['contact_details']['ranch_name'] = $form['ranch_name'];
    $form['contact_details']['first_name'] = $form['first_name'];
    $form['contact_details']['last_name'] = $form['last_name'];
    $form['contact_details']['title'] = $form['title'];
    $form['contact_details']['status'] = $form['status'];
    $form['contact_details']['comments'] = $form['comments'];
    $form['contact_details']['photo'] = $form['photo'];

    // Addresses Tab
    $form['addresses'] = [
      '#type' => 'details',
      '#title' => $this->t('Addresses'),
      '#group' => 'tabs',
    ];
    $form['addresses']['address'] = $form['address'];
    $form['addresses']['city'] = $form['city'];
    $form['addresses']['state'] = $form['state'];
    $form['addresses']['zip_code'] = $form['zip_code'];
    $form['addresses']['country'] = $form['country'];

    // Phone Numbers Tab
    $form['phone_numbers'] = [
      '#type' => 'details',
      '#title' => $this->t('Phone Numbers'),
      '#group' => 'tabs',
    ];
    $form['phone_numbers']['phone_number'] = $form['phone_number'];
    $form['phone_numbers']['phone_location'] = [
      '#type' => 'select',
      '#title' => $this->t('Phone Location'),
      '#options' => [
        'Ranch' => $this->t('Ranch'),
        'Work' => $this->t('Work'),
        'Home' => $this->t('Home'),
        'Mobile' => $this->t('Mobile'),
        'Fax' => $this->t('Fax'),
        'Pager' => $this->t('Pager'),
        'Other' => $this->t('Other'),
      ],
      '#default_value' => 'Work',
    ];
    // Email Addresses Tab
    $form['email_addresses'] = [
      '#type' => 'details',
      '#title' => $this->t('Email Addresses'),
      '#group' => 'tabs',
    ];
    
    $form['email_addresses']['email'] = $form['email'];
    $form['email_addresses']['email_location'] = [
      '#type' => 'select',
      '#title' => $this->t('Email Location'),
      '#options' => [
        'Ranch' => $this->t('Ranch'),
        'Work' => $this->t('Work'),
        'Home' => $this->t('Home'),
        'Mobile' => $this->t('Mobile'),
        'Fax' => $this->t('Fax'),
        'Pager' => $this->t('Pager'),
        'Other' => $this->t('Other'),
      ],
      '#default_value' => 'Work',
    ];  // Unset original fields to avoid duplicate rendering.
  unset(
    $form['display_as'],
    $form['ranch_name'],
    $form['first_name'],
    $form['last_name'],
    $form['title'],
    $form['status'],
    $form['comments'],
    $form['address'],
    $form['city'],
    $form['state'],
    $form['photo'], 
    $form['zip_code'],
    $form['country'],
    $form['phone_number'],
    $form['phone_location'],
    $form['email'],
    $form['email_location']
  );
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    if (!$entity->get('photo')->isEmpty()) {
      $file = $entity->get('photo')->entity;
      if ($file && !$file->isPermanent()) {
        $file->setPermanent();
        $file->save();
      }
    }
    $status = $entity->save();

    if ($status === SAVED_NEW) {
      $this->messenger()->addStatus($this->t('The contact %label has been created.', ['%label' => $entity->label()]));
    }
    else {
      $this->messenger()->addStatus($this->t('The contact %label has been updated.', ['%label' => $entity->label()]));
    }

    $form_state->setRedirect('farm_contact.contact_profile', ['contacts' => $entity->id()]);
  }

}