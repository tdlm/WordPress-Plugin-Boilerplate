/**
 * WordPress Plugin Boilerplate - Admin JavaScript
 *
 * @package WordPressPluginBoilerplate
 */

(function($) {
    'use strict';

    /**
     * Admin functionality
     */
    const PluginBoilerplateAdmin = {
        /**
         * Initialize the admin
         */
        init: function() {
            this.bindEvents();
            this.initComponents();
        },

        /**
         * Bind event handlers
         */
        bindEvents: function() {
            $(document).on('click', '.plugin-boilerplate-admin-trigger', this.handleAdminTrigger);
            $(document).on('change', '.plugin-boilerplate-setting', this.handleSettingChange);
            $(document).on('submit', '.plugin-boilerplate-admin-form', this.handleAdminFormSubmit);
        },

        /**
         * Initialize components
         */
        initComponents: function() {
            // Example: Initialize admin-specific components
            this.setupAdminAjaxHandlers();
            this.initTabs();
        },

        /**
         * Setup admin AJAX handlers
         */
        setupAdminAjaxHandlers: function() {
            if (typeof wpPluginBoilerplate !== 'undefined') {
                console.log('Plugin Boilerplate Admin AJAX ready');
            }
        },

        /**
         * Initialize tabs
         */
        initTabs: function() {
            $('.plugin-boilerplate-tabs').each(function() {
                const $tabs = $(this);
                const $tabLinks = $tabs.find('.plugin-boilerplate-tab-link');
                const $tabPanels = $tabs.find('.plugin-boilerplate-tab-panel');

                $tabLinks.on('click', function(e) {
                    e.preventDefault();
                    
                    const target = $(this).data('tab');
                    
                    // Update active states
                    $tabLinks.removeClass('active');
                    $tabPanels.removeClass('active');
                    
                    $(this).addClass('active');
                    $tabs.find('[data-panel="' + target + '"]').addClass('active');
                });
            });
        },

        /**
         * Handle admin trigger clicks
         *
         * @param {Event} e The click event
         */
        handleAdminTrigger: function(e) {
            e.preventDefault();
            
            const $element = $(this);
            const action = $element.data('action') || 'default';
            
            PluginBoilerplateAdmin.performAdminAction(action, {
                element: $element
            });
        },

        /**
         * Handle setting changes
         *
         * @param {Event} e The change event
         */
        handleSettingChange: function(e) {
            const $element = $(this);
            const setting = $element.data('setting');
            const value = $element.val();
            
            PluginBoilerplateAdmin.saveSetting(setting, value);
        },

        /**
         * Handle admin form submissions
         *
         * @param {Event} e The submit event
         */
        handleAdminFormSubmit: function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const formData = $form.serialize();
            
            PluginBoilerplateAdmin.submitAdminForm($form, formData);
        },

        /**
         * Perform an admin action
         *
         * @param {string} action The action to perform
         * @param {Object} data Additional data
         */
        performAdminAction: function(action, data = {}) {
            if (typeof wpPluginBoilerplate === 'undefined') {
                console.error('Plugin Boilerplate not initialized');
                return;
            }

            $.ajax({
                url: wpPluginBoilerplate.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'plugin_boilerplate_admin_action',
                    nonce: wpPluginBoilerplate.nonce,
                    admin_action: action,
                    data: JSON.stringify(data)
                },
                success: function(response) {
                    if (response.success) {
                        PluginBoilerplateAdmin.handleAdminSuccess(response.data);
                    } else {
                        PluginBoilerplateAdmin.handleAdminError(response.data);
                    }
                },
                error: function(xhr, status, error) {
                    PluginBoilerplateAdmin.handleAdminError({
                        message: 'Admin AJAX request failed',
                        error: error
                    });
                }
            });
        },

        /**
         * Save a setting
         *
         * @param {string} setting The setting name
         * @param {string} value The setting value
         */
        saveSetting: function(setting, value) {
            if (typeof wpPluginBoilerplate === 'undefined') {
                console.error('Plugin Boilerplate not initialized');
                return;
            }

            $.ajax({
                url: wpPluginBoilerplate.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'plugin_boilerplate_save_setting',
                    nonce: wpPluginBoilerplate.nonce,
                    setting: setting,
                    value: value
                },
                success: function(response) {
                    if (response.success) {
                        PluginBoilerplateAdmin.showAdminMessage('Setting saved successfully', 'success');
                    } else {
                        PluginBoilerplateAdmin.showAdminMessage('Failed to save setting', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    PluginBoilerplateAdmin.showAdminMessage('Failed to save setting', 'error');
                }
            });
        },

        /**
         * Submit an admin form
         *
         * @param {jQuery} $form The form element
         * @param {string} formData The serialized form data
         */
        submitAdminForm: function($form, formData) {
            if (typeof wpPluginBoilerplate === 'undefined') {
                console.error('Plugin Boilerplate not initialized');
                return;
            }

            $.ajax({
                url: wpPluginBoilerplate.ajaxUrl,
                type: 'POST',
                data: formData + '&action=plugin_boilerplate_admin_action&nonce=' + wpPluginBoilerplate.nonce,
                success: function(response) {
                    if (response.success) {
                        PluginBoilerplateAdmin.handleAdminSuccess(response.data);
                    } else {
                        PluginBoilerplateAdmin.handleAdminError(response.data);
                    }
                },
                error: function(xhr, status, error) {
                    PluginBoilerplateAdmin.handleAdminError({
                        message: 'Admin form submission failed',
                        error: error
                    });
                }
            });
        },

        /**
         * Handle successful admin responses
         *
         * @param {Object} data The response data
         */
        handleAdminSuccess: function(data) {
            console.log('Plugin Boilerplate Admin success:', data);
            
            if (data.message) {
                this.showAdminMessage(data.message, 'success');
            }
        },

        /**
         * Handle admin error responses
         *
         * @param {Object} data The error data
         */
        handleAdminError: function(data) {
            console.error('Plugin Boilerplate Admin error:', data);
            
            if (data.message) {
                this.showAdminMessage(data.message, 'error');
            }
        },

        /**
         * Show an admin message
         *
         * @param {string} message The message to show
         * @param {string} type The message type (success, error, warning, info)
         */
        showAdminMessage: function(message, type = 'info') {
            const $message = $('<div>')
                .addClass('plugin-boilerplate-admin-message')
                .addClass('plugin-boilerplate-admin-message--' + type)
                .text(message);

            $('.wrap h1').after($message);

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
        PluginBoilerplateAdmin.init();
    });

})(jQuery);
