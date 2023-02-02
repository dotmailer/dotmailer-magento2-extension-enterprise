function _inheritsLoose(subClass, superClass)
{
    subClass.prototype = Object.create(superClass.prototype); subClass.prototype.constructor = subClass; subClass.__proto__ = superClass;
}

define([
    'knockout', 'mage/translate', 'Magento_PageBuilder/js/content-type-menu/hide-show-option', 'Magento_PageBuilder/js/content-type/preview', 'Magento_PageBuilder/js/events', 'module', 'jquery', 'mage/url'
], function (_knockout, _translate, _hideShowOption, _preview, _events, _module, $, url) {

    var Preview =
        /*#__PURE__*/
        function (_preview2) {
            'use strict';

            _inheritsLoose(Preview, _preview2);

            function Preview(contentType, config, observableUpdater) {
                var _this;

                _this = _preview2.call(this, contentType, config, observableUpdater) || this;
                _this.hiddenData = null;
                _this.apiEnabled = _knockout.observable(!!_module.config().isDDGEnabled);
                _this.apiErrorMessage = _module.config().apiErrorMessage;
                _this.formDataControllerUrl = _module.config().ddgFormDataUrl;
                _this.messages = {
                    NOT_SELECTED: (0, _translate)('Edit to select a survey or form'),
                    UNKNOWN_ERROR: (0, _translate)('An unknown error occurred. Please try again.')
                };
                _this.placeholderText = _knockout.observable(_this.messages.NOT_SELECTED);
                _this.baseUrl = this.contentType.config.additional_data.formConfig.baseUrl;

                _events.on('contentType:mountAfter', function (args) {
                    if (args.contentType.id === this.contentType.id) {
                        this.contentType.dataStore.set('base_url', this.baseUrl);
                    }
                }.bind(this));
            }

            var _proto = Preview.prototype;

            /**
             *
             */
            _proto.retrieveOptions = function retrieveOptions() {
                var options = _preview2.prototype.retrieveOptions.call(this);

                // Change tooltips
                options.edit.title = 'Select form';

                options.hideShow = new _hideShowOption({
                    preview: this,
                    icon: _hideShowOption.showIcon,
                    title: _hideShowOption.showText,
                    action: this.onOptionVisibilityToggle,
                    classes: ['hide-show-content-type'],
                    sort: 40
                });

                return options;
            };

            /**
             *
             */
            _proto.bindEvents = function bindEvents() {
                _preview2.prototype.bindEvents.call(this);

                _events.on('form:' + this.contentType.id + ':saveAfter', function (data) {
                    if (data.form_select) {
                        this.getFormData(data, this.appendFormData.bind(this));
                    }
                }.bind(this));
            };

            /**
             *
             */
            _proto.afterObservablesUpdated = function afterObservablesUpdated() {
                _preview2.prototype.afterObservablesUpdated.call(this);

                var data = this.contentType.dataStore.getState();

                this.updatePlaceholder(data);
            };

            /**
             * @param {Object} data
             */
            _proto.updatePlaceholder = function updatePlaceholder(data) {
                if (!data.form_select || data.form_select.length === 0) {
                    this.placeholderText(this.messages.NOT_SELECTED);

                    return;
                }

                if (data.form_style === 'embedded') {
                    this.placeholderText('The form "' + data.form_name + '" will be embedded.');
                } else if (data.form_style === 'popover') {
                    this.placeholderText('The form "' + data.form_name + '" will be displayed as a pop-over after ' + data.show_after + ' seconds.');
                } else {
                    this.placeholderText(this.messages.NOT_SELECTED);
                }
            };

            /**
             * @param data
             * @param callback
             */
            _proto.getFormData = function getFormData(data, callback) {

                let formId = data.form_select;
                let postFormData = {}
                let postKeys = [
                    'account_select',
                    'form_style',
                    'form_sharing',
                    'show_after',
                    'appearances',
                    'enable_use_esc',
                    'show_mobile',
                    'add_respondent',
                    'stop_displaying'
                ];

                for (const property in data) {
                    if(postKeys.includes(property)) postFormData[property] = data[property]
                }

                if ( !formId
                    || !postFormData
                    || !postFormData.hasOwnProperty('account_select') // websiteId
                    || !postFormData.hasOwnProperty('form_style')  // formStyle
                ) {
                    return;
                }

                $.ajax({
                    type: 'POST',
                    dataType: 'JSON',
                    url: url.build(this.formDataControllerUrl + '?form_id=' + formId),
                    data: {
                        form_data: postFormData,
                        form_key: window.FORM_KEY
                    }
                })
                .done((response) => callback(data, response))
            };

            _proto.appendFormData = function setFormData(data, additionalData) {
                if (additionalData) {
                    $.extend(data, additionalData);
                    this.contentType.dataStore.setState(data);
                }
            };

            return Preview;
        }(_preview);

    return Preview;
});
//# sourceMappingURL=preview.js.map
