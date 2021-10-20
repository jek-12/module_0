(function ($, Drupal, drupalSettings) {
    'use strict';
    Drupal.behaviors.jek_12_FormJek12 = {
        attach: function (context, settings) {
            let name = document.querySelector('[id^="edit-cats-name"]');
            let mail = document.querySelector('[id^="edit-cats-mail"]');
            let submit = document.querySelector('[id^="edit-submit"]');
            name.selectionStart = name.value.length;
            mail.selectionStart = mail.value.length;
            // $(submit).mouseleave(function () {
            //     console.log(name.value);
            //     name.value = '';
            //     console.log(name.value);
            //     mail.value = '';
            // });

        }
    };
})(jQuery, Drupal, drupalSettings);
