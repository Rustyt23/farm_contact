(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.contactModal = {
      attach: function (context, settings) {
        $('#open-contact-form', context).once('contactModal').click(function () {
          if (!$('#contact-modal').length) {
            $('body').append('<div id="contact-modal"><div id="contact-modal-content">' + drupalSettings.contactFormHtml + '</div></div>');
          }
          $('#contact-modal').fadeIn();
        });
  
        $('body').on('click', '#contact-modal', function (e) {
          if (e.target.id === 'contact-modal') {
            $('#contact-modal').fadeOut();
          }
        });
      }
    };
  })(jQuery, Drupal, drupalSettings);
  