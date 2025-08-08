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
     * Plugin constructor
     */
    public function __construct() {
        parent::__construct();
        
        $this->init_hooks();
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks(): void {
        add_action( 'init', [ $this, 'init' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
    }

    /**
     * Initialize the plugin
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
            new Admin\Admin();
        }

        // Initialize frontend functionality
        new Frontend\Frontend();

        // Initialize API endpoints
        new Api\Api();
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts(): void {
        wp_enqueue_script(
            'wordpress-plugin-boilerplate-frontend',
            WP_PLUGIN_BOILERPLATE_PLUGIN_URL . 'assets/js/frontend.js',
            [ 'jquery' ],
            WP_PLUGIN_BOILERPLATE_VERSION,
            true
        );

        wp_enqueue_style(
            'wordpress-plugin-boilerplate-frontend',
            WP_PLUGIN_BOILERPLATE_PLUGIN_URL . 'assets/css/frontend.css',
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
     */
    public function enqueue_admin_scripts(): void {
        wp_enqueue_script(
            'wordpress-plugin-boilerplate-admin',
            WP_PLUGIN_BOILERPLATE_PLUGIN_URL . 'assets/js/admin.js',
            [ 'jquery' ],
            WP_PLUGIN_BOILERPLATE_VERSION,
            true
        );

        wp_enqueue_style(
            'wordpress-plugin-boilerplate-admin',
            WP_PLUGIN_BOILERPLATE_PLUGIN_URL . 'assets/css/admin.css',
            [],
            WP_PLUGIN_BOILERPLATE_VERSION
        );
    }
}
