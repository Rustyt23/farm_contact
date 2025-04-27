<?php

namespace Drupal\farm_contact\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class FarmContactController extends ControllerBase {

   public function title(){
        return $this->t('Contacts');
   }

public function testPage() {
  $connection = Database::getConnection();
  $query = $connection->select('contact', 'c')
    ->fields('c', ['id', 'display_as', 'email', 'phone_number'])
    ->condition('c.bundle', 'default') // Specify the bundle
    ->range(0, 50); // Increase limit if you want
  $results = $query->execute()->fetchAll();
  $output = '';

  if (!empty($results)) {
    $output .= '<table style="width:100%; border-collapse: collapse;" border="1">';
    $output .= '<thead><tr><th>Display Name</th><th>Email</th><th>Phone</th></tr></thead>';
    $output .= '<tbody>';
    foreach ($results as $record) {
      $output .= '<tr>';
      $output .= '<td>' . $record->display_as . '</td>';
      $output .= '<td>' . $record->email . '</td>';
      $output .= '<td>' . $record->phone_number . '</td>';
      $output .= '</tr>';
    }
    $output .= '</tbody></table>';
  }
  else {
    $output .= '<p>No contacts found.</p>';
  }

  return [
    '#type' => 'markup',
    '#markup' => $output,
    '#allowed_tags' => ['table', 'thead', 'tbody', 'tr', 'td', 'th', 'h2', 'p', 'a'],
  ];
}
}
