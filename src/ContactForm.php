<?php

namespace Drupal\farm_contact;

use Drupal\Core\StringTranslation\StringTranslationTrait;

class ContactForm {

   use StringTranslationTrait;

   public function title(){
      	return $this->t('Add Contact');
   }
}
