define([
    'Magento_Ui/js/form/element/select',
    'jquery',
    'mage/url',
    'module'
], function (Select, $, url, module) {
    'use strict';

    return Select.extend({
        defaults: {
            disabled: true,
            caption: '-- Please Select --',
            imports: {
                baseUrl: '${ $.provider }:data.base_url',
                accountId: '${ $.provider }:data.account_select'
            },
            listens: {
                baseUrl: 'setBaseUrl',
                accountId: 'fetchECForms'
            },
            previouslySelectedValue: '',
            formOptionsControllerUrl: module.config().ddgFormOptionsUrl
        },

        /**
         * Dependently display dropdown component if it contains more than one option
         *
         * @param {Object} data
         * @returns {Object} Chainable
         */
        setOptions: function (data) {
            this._super(data);

            if (this.options().length) {
                this.value(this.previouslySelectedValue);
                this.setDisabled(false);
            }

            return this;
        },

        /**
         * @param {String} websiteId
         */
        fetchECForms: function (websiteId) {
            var _this2 = this;

            if (!websiteId || typeof websiteId === 'undefined') {
                return;
            }

            if (this.source.data) {
                this.previouslySelectedValue = this.source.data.form_select;
            }

            $.ajax({
                url: url.build(this.formOptionsControllerUrl + '?website_id=' + websiteId),
                method: 'GET',
                dataType: 'JSON',
                contentType: 'application/json',
            }).done(function (response) {
                _this2.setOptions(response);
            });
        },

        /**
         * @param {String} baseUrl
         */
        setBaseUrl: function (baseUrl) {
            url.setBaseUrl(baseUrl);
        }
    });
});
