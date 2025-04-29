<?php

namespace Drupal\farm_contact\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class FarmContactController extends ControllerBase {
  public function contactProfile($contacts) {
    $build['#attached']['library'][] = 'farm_contact/farm_contact_profile_styles';

    $connection = Database::getConnection();
    $query = $connection->select('contact_field_data', 'c')
    ->fields('c', ['id', 'display_as', 'email', 'phone_number', 'address', 'photo'])
    ->condition('id', $contacts)
    ->range(0, 50);
    $results = $query->execute()->fetchAll();
    if (!$results) {
      throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
    }
    $record = reset($results); // Only take 1 contact.

    $photo_url = NULL;
    if (!empty($record->photo)) {
      $file = \Drupal::entityTypeManager()->getStorage('file')->load($record->photo);
      if ($file) {
        $photo_url = file_create_url($file->getFileUri());
      }
    }
    $build = [
      '#theme' => 'farm_contact_profile',
      '#contacts' => [
        'display_as' => $record->display_as ?? 'No Name Found',
        'id' => $record->id ?? NULL,
        'email' => $record->email ?? NULL,
        'phone_number' => $record->phone_number ?? NULL,
        'address' => $record->address ?? NULL,
        'photo_url' => $photo_url,  // <-- notice this!
        // You can add more fields like ranch_name, city, phone_number, etc. if needed
      ],
        ];
  
    return $build;
  }
  
   public function title(){
        return $this->t('Contacts');
   }
   public function testPage() {
    $connection = Database::getConnection();
    $query = $connection->select('contact_field_data', 'c')
      ->fields('c', ['id', 'display_as'])
      ->range(0, 50);
    $results = $query->execute()->fetchAll();

    $rows = [];
    foreach ($results as $record) {
      $rows[] = [
        'data' => [
          'name' => [
            'data' => [
              '#type' => 'link',
              '#title' => $record->display_as,
              '#url' => \Drupal\Core\Url::fromRoute('farm_contact.contact_profile', ['contacts' => $record->id ?? 0]), // Adjust if needed
              '#attributes' => [
                'class' => ['contact-name-link'],
              ],
            ],
          ],
        ],
      ];
    }

    $header = [
      [
        'data' => $this->t('Name'),
        'field' => 'display_as',
      ],
      [
        'data' => $this->t('Tags'),
      ],
      
    ];

    $build['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#attributes' => [
        'class' => ['contact-list-table'],
      ],
      '#empty' => $this->t('No contacts found.'),
    ];

    return $build;
  }
}
