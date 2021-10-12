(function ($, Drupal, drupalSettings) {
    'use strict';
    Drupal.behaviors.jek_12_FormJek12 = {
        attach: function (context, settings) {
            let name = document.querySelector('[id^="edit-cats-name"]');
            let mail = document.querySelector('[id^="edit-cats-mail"]');
            name.selectionStart = name.value.length;
            mail.selectionStart = mail.value.length;
        }
    };
})(jQuery, Drupal, drupalSettings);
