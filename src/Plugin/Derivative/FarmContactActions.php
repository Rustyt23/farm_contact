<?php

namespace Drupal\farm_contact\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides local actions for farm_contact.
 */
class FarmContactActions extends DeriverBase implements ContainerDeriverInterface {

  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static();
  }

  public function getDerivativeDefinitions($base_plugin_definition) {
    $this->derivatives['farm.contact.add'] = $base_plugin_definition + [
      'route_name' => 'farm_contact.add_contact',
      'title' => t('Add Contact'),
      'appears_on' => ['farm_contact.add_contact'],
      'entity_type' => 'contacts', // 👈 THIS IS REQUIRED
    ];
    return $this->derivatives;
  }
}

