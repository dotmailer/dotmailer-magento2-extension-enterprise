define([
    'jquery',
    'mage/url'
], function ($, url) {
    'use strict';

    return function (config, element) {
        const formId = $(element).find('iframe').attr('id'),
            parentAddRespondentAttribute = $(element).data('add-respondent');

        let shouldSubscribe = parentAddRespondentAttribute ?
            parentAddRespondentAttribute === 1 :
            $(element).find('script[data-form-id]').data('add-respondent') === 1;

        ecPF.onComplete(function (formData) {
            const hasContactEmail = formData.contactEmail != null && formData.contactEmail.length > 0;

            if (typeof window.dmPt !== 'undefined' && hasContactEmail) {
                window.dmPt('identify', formData.contactEmail);
            }

            if (shouldSubscribe && hasContactEmail) {
                $.post(url.build('newsletter/subscriber/new'), {
                    email: formData.contactEmail
                }).done(function () {
                    window.scrollTo(0, 0);
                    window.location.reload();
                });
            }
        }, formId);
    };
});
