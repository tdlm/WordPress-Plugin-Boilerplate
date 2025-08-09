<?php
/**
 * Plugin Name: WordPress Plugin Boilerplate
 * Plugin URI: https://github.com/wordpress-plugin-boilerplate/wordpress-plugin-boilerplate
 * Description: A modern WordPress plugin boilerplate for rapid development
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.2
 * Author: WordPress Plugin Boilerplate
 * Author URI: https://wordpress-plugin-boilerplate.com
 * License: GPL v3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: wordpress-plugin-boilerplate
 * Domain Path: /languages
 * Network: false
 *
 * @package WordPressPluginBoilerplate
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'WP_PLUGIN_BOILERPLATE_VERSION', '1.0.0' );
define( 'WP_PLUGIN_BOILERPLATE_PLUGIN_FILE', __FILE__ );
define( 'WP_PLUGIN_BOILERPLATE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_PLUGIN_BOILERPLATE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_PLUGIN_BOILERPLATE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Autoloader
require_once WP_PLUGIN_BOILERPLATE_PLUGIN_DIR . 'vendor/autoload.php';

// Initialize the plugin
add_action( 'plugins_loaded', function() {
    // Check if the required dependency is available
    if ( ! class_exists( 'GreatScottPlugins\\WordPressPlugin\\Plugin' ) ) {
        add_action( 'admin_notices', function() {
            echo '<div class="notice notice-error"><p>';
            echo esc_html__( 'WordPress Plugin Boilerplate requires the Great Scott Plugins WordPress Plugin library to be installed.', 'wordpress-plugin-boilerplate' );
            echo '</p></div>';
        } );
        return;
    }

    // Initialize the main plugin class using the singleton pattern
    \WordPressPluginBoilerplate\Plugin::load();
} );

// Activation hook
register_activation_hook( __FILE__, function() {
    // Activation logic here
    flush_rewrite_rules();
} );

// Deactivation hook
register_deactivation_hook( __FILE__, function() {
    // Deactivation logic here
    flush_rewrite_rules();
} );
