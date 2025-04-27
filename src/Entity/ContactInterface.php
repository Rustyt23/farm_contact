<?php

declare(strict_types=1);

namespace Drupal\farm_contact\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Contact entities.
 *
 * @ingroup farm_contact
 */
interface ContactInterface extends ContentEntityInterface, EntityChangedInterface, RevisionLogInterface, EntityOwnerInterface {

  /**
   * Gets the contact name.
   *
   * @return string
   *   The contact name.
   */
  public function getName(): string;

  /**
   * Sets the contact name.
   *
   * @param string $name
   *   The contact name.
   *
   * @return static
   *   The contact entity.
   */
  public function setName(string $name): static;

  /**
   * Gets the contact creation timestamp.
   *
   * @return int
   *   Creation timestamp of the contact.
   */
  public function getCreatedTime(): int;

  /**
   * Sets the contact creation timestamp.
   *
   * @param int $timestamp
   *   The creation timestamp.
   *
   * @return static
   *   The contact entity.
   */
  public function setCreatedTime(int $timestamp): static;

  /**
   * Gets the contact archived timestamp.
   *
   * @return int|null
   *   Archived timestamp of the contact or NULL if not archived.
   */
  public function getArchivedTime(): ?int;

  /**
   * Sets the contact archived timestamp.
   *
   * @param int|string|null $timestamp
   *   Archived timestamp of the contact.
   *
   * @return static
   *   The contact entity.
   */
  public function setArchivedTime(int|string|null $timestamp): static;

  /**
   * Gets the label of the contact type (bundle).
   *
   * @return string
   *   The label of the contact type.
   */
  public function getBundleLabel(): string;

}
