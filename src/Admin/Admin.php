<?php

namespace WordPressPluginBoilerplate\Admin;

/**
 * Admin functionality
 *
 * @package WordPressPluginBoilerplate
 */
class Admin {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
        add_action( 'admin_init', [ $this, 'init_settings' ] );
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu(): void {
        add_menu_page(
            __( 'Plugin Boilerplate', 'wordpress-plugin-boilerplate' ),
            __( 'Plugin Boilerplate', 'wordpress-plugin-boilerplate' ),
            'manage_options',
            'wordpress-plugin-boilerplate',
            [ $this, 'admin_page' ],
            'dashicons-admin-generic',
            30
        );
    }

    /**
     * Initialize settings
     */
    public function init_settings(): void {
        register_setting(
            'wordpress_plugin_boilerplate_options',
            'wordpress_plugin_boilerplate_settings',
            [ $this, 'sanitize_settings' ]
        );

        add_settings_section(
            'wordpress_plugin_boilerplate_general',
            __( 'General Settings', 'wordpress-plugin-boilerplate' ),
            [ $this, 'settings_section_callback' ],
            'wordpress-plugin-boilerplate'
        );

        add_settings_field(
            'example_setting',
            __( 'Example Setting', 'wordpress-plugin-boilerplate' ),
            [ $this, 'example_setting_callback' ],
            'wordpress-plugin-boilerplate',
            'wordpress_plugin_boilerplate_general'
        );
    }

    /**
     * Admin page callback
     */
    public function admin_page(): void {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'wordpress_plugin_boilerplate_options' );
                do_settings_sections( 'wordpress-plugin-boilerplate' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Settings section callback
     */
    public function settings_section_callback(): void {
        echo '<p>' . esc_html__( 'Configure your plugin settings below.', 'wordpress-plugin-boilerplate' ) . '</p>';
    }

    /**
     * Example setting field callback
     */
    public function example_setting_callback(): void {
        $options = get_option( 'wordpress_plugin_boilerplate_settings', [] );
        $value   = isset( $options['example_setting'] ) ? $options['example_setting'] : '';
        ?>
        <input type="text" 
               name="wordpress_plugin_boilerplate_settings[example_setting]" 
               value="<?php echo esc_attr( $value ); ?>" 
               class="regular-text" />
        <p class="description">
            <?php esc_html_e( 'This is an example setting field.', 'wordpress-plugin-boilerplate' ); ?>
        </p>
        <?php
    }

    /**
     * Sanitize settings
     *
     * @param array $input The input array.
     * @return array
     */
    public function sanitize_settings( array $input ): array {
        $sanitized = [];

        if ( isset( $input['example_setting'] ) ) {
            $sanitized['example_setting'] = sanitize_text_field( $input['example_setting'] );
        }

        return $sanitized;
    }
}
