/**
 * WordPress Plugin Boilerplate - Frontend JavaScript
 *
 * @package WordPressPluginBoilerplate
 */

(function($) {
    'use strict';

    /**
     * Frontend functionality
     */
    const PluginBoilerplate = {
        /**
         * Initialize the plugin
         */
        init: function() {
            this.bindEvents();
            this.initComponents();
        },

        /**
         * Bind event handlers
         */
        bindEvents: function() {
            $(document).on('click', '.plugin-boilerplate-trigger', this.handleTrigger);
            $(document).on('submit', '.plugin-boilerplate-form', this.handleFormSubmit);
        },

        /**
         * Initialize components
         */
        initComponents: function() {
            // Example: Initialize any components that need setup
            this.setupAjaxHandlers();
        },

        /**
         * Setup AJAX handlers
         */
        setupAjaxHandlers: function() {
            // Example AJAX handler
            if (typeof wpPluginBoilerplate !== 'undefined') {
                console.log('Plugin Boilerplate AJAX ready');
            }
        },

        /**
         * Handle trigger clicks
         *
         * @param {Event} e The click event
         */
        handleTrigger: function(e) {
            e.preventDefault();
            
            const $element = $(this);
            const action = $element.data('action') || 'default';
            
            PluginBoilerplate.performAction(action, {
                element: $element
            });
        },

        /**
         * Handle form submissions
         *
         * @param {Event} e The submit event
         */
        handleFormSubmit: function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const formData = $form.serialize();
            
            PluginBoilerplate.submitForm($form, formData);
        },

        /**
         * Perform an action
         *
         * @param {string} action The action to perform
         * @param {Object} data Additional data
         */
        performAction: function(action, data = {}) {
            if (typeof wpPluginBoilerplate === 'undefined') {
                console.error('Plugin Boilerplate not initialized');
                return;
            }

            $.ajax({
                url: wpPluginBoilerplate.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'plugin_boilerplate_action',
                    nonce: wpPluginBoilerplate.nonce,
                    data: JSON.stringify(data)
                },
                success: function(response) {
                    if (response.success) {
                        PluginBoilerplate.handleSuccess(response.data);
                    } else {
                        PluginBoilerplate.handleError(response.data);
                    }
                },
                error: function(xhr, status, error) {
                    PluginBoilerplate.handleError({
                        message: 'AJAX request failed',
                        error: error
                    });
                }
            });
        },

        /**
         * Submit a form
         *
         * @param {jQuery} $form The form element
         * @param {string} formData The serialized form data
         */
        submitForm: function($form, formData) {
            if (typeof wpPluginBoilerplate === 'undefined') {
                console.error('Plugin Boilerplate not initialized');
                return;
            }

            $.ajax({
                url: wpPluginBoilerplate.ajaxUrl,
                type: 'POST',
                data: formData + '&action=plugin_boilerplate_action&nonce=' + wpPluginBoilerplate.nonce,
                success: function(response) {
                    if (response.success) {
                        PluginBoilerplate.handleSuccess(response.data);
                    } else {
                        PluginBoilerplate.handleError(response.data);
                    }
                },
                error: function(xhr, status, error) {
                    PluginBoilerplate.handleError({
                        message: 'Form submission failed',
                        error: error
                    });
                }
            });
        },

        /**
         * Handle successful responses
         *
         * @param {Object} data The response data
         */
        handleSuccess: function(data) {
            console.log('Plugin Boilerplate success:', data);
            
            // Example: Show success message
            if (data.message) {
                this.showMessage(data.message, 'success');
            }
        },

        /**
         * Handle error responses
         *
         * @param {Object} data The error data
         */
        handleError: function(data) {
            console.error('Plugin Boilerplate error:', data);
            
            // Example: Show error message
            if (data.message) {
                this.showMessage(data.message, 'error');
            }
        },

        /**
         * Show a message to the user
         *
         * @param {string} message The message to show
         * @param {string} type The message type (success, error, warning, info)
         */
        showMessage: function(message, type = 'info') {
            const $message = $('<div>')
                .addClass('plugin-boilerplate-message')
                .addClass('plugin-boilerplate-message--' + type)
                .text(message);

            $('body').append($message);

            // Auto-remove after 5 seconds
            setTimeout(function() {
                $message.fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        PluginBoilerplate.init();
    });

})(jQuery);
