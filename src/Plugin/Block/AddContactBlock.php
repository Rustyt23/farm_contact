<?php

namespace Drupal\farm_contact\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides a block with Add Contact Form.
 *
 * @Block(
 *   id = "add_contact_block",
 *   admin_label = @Translation("Add Contact Block"),
 * )
 */
class AddContactBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * Constructs a new AddContactBlock.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $form_builder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Load your existing Contact form.
    $form = $this->formBuilder->getForm('\Drupal\farm_contact\Form\AddContactForm');

    return [
      '#markup' => '<button id="open-contact-form" class="button button--primary">Add Contact</button>',
      '#attached' => [
        'library' => [
          'farm_contact/contact_modal',
        ],
        'drupalSettings' => [
          'contactFormHtml' => \Drupal::service('renderer')->renderRoot($form),
        ],
      ],
    ];
  }

}
