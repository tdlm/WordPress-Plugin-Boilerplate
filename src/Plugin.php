<?php

namespace WordPressPluginBoilerplate;

use GreatScottPlugins\WordPressPlugin\Plugin as BasePlugin;

/**
 * Main plugin class
 *
 * @package WordPressPluginBoilerplate
 */
class Plugin extends BasePlugin {

    /**
     * Admin component instance
     *
     * @var Admin\Admin|null
     */
    private $admin = null;

    /**
     * Frontend component instance
     *
     * @var Frontend\Frontend|null
     */
    private $frontend = null;

    /**
     * API component instance
     *
     * @var Api\Api|null
     */
    private $api = null;

    /**
     * Initialize the plugin
     *
     * @action init
     */
    public function init(): void {
        // Load text domain for translations
        load_plugin_textdomain( 'wordpress-plugin-boilerplate', false, dirname( plugin_basename( WP_PLUGIN_BOILERPLATE_PLUGIN_FILE ) ) . '/languages' );

        // Initialize components
        $this->init_components();
    }

    /**
     * Initialize plugin components
     */
    private function init_components(): void {
        // Initialize admin functionality
        if ( is_admin() ) {
            $this->admin = Admin\Admin::instance();
        }

        // Initialize frontend functionality
        $this->frontend = Frontend\Frontend::instance();

        // Initialize API endpoints
        $this->api = Api\Api::instance();
    }

    /**
     * Enqueue frontend scripts and styles
     *
     * @action wp_enqueue_scripts
     */
    public function enqueue_scripts(): void {
        wp_enqueue_script(
            'wordpress-plugin-boilerplate-frontend',
            WP_PLUGIN_BOILERPLATE_PLUGIN_URL . 'build/index.js',
            [ 'jquery' ],
            WP_PLUGIN_BOILERPLATE_VERSION,
            true
        );

        wp_enqueue_style(
            'wordpress-plugin-boilerplate-frontend',
            WP_PLUGIN_BOILERPLATE_PLUGIN_URL . 'build/index.css',
            [],
            WP_PLUGIN_BOILERPLATE_VERSION
        );

        // Localize script for AJAX
        wp_localize_script(
            'wordpress-plugin-boilerplate-frontend',
            'wpPluginBoilerplate',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'wp_plugin_boilerplate_nonce' ),
            ]
        );
    }

    /**
     * Enqueue admin scripts and styles
     *
     * @action admin_enqueue_scripts
     */
    public function enqueue_admin_scripts(): void {
        wp_enqueue_script(
            'wordpress-plugin-boilerplate-admin',
            WP_PLUGIN_BOILERPLATE_PLUGIN_URL . 'build/index.js',
            [ 'jquery' ],
            WP_PLUGIN_BOILERPLATE_VERSION,
            true
        );

        wp_enqueue_style(
            'wordpress-plugin-boilerplate-admin',
            WP_PLUGIN_BOILERPLATE_PLUGIN_URL . 'build/index.css',
            [],
            WP_PLUGIN_BOILERPLATE_VERSION
        );
    }
}
