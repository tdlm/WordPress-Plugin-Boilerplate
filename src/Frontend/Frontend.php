<?php

namespace WordPressPluginBoilerplate\Frontend;

/**
 * Frontend functionality
 *
 * @package WordPressPluginBoilerplate
 */
class Frontend {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'wp_footer', [ $this, 'add_footer_content' ] );
        add_action( 'wp_ajax_plugin_boilerplate_action', [ $this, 'handle_ajax_action' ] );
        add_action( 'wp_ajax_nopriv_plugin_boilerplate_action', [ $this, 'handle_ajax_action' ] );
    }

    /**
     * Add content to footer
     */
    public function add_footer_content(): void {
        // Example: Add a simple message to the footer
        if ( is_user_logged_in() ) {
            echo '<div id="plugin-boilerplate-footer" style="display: none;">';
            echo '<p>' . esc_html__( 'Plugin Boilerplate is active!', 'wordpress-plugin-boilerplate' ) . '</p>';
            echo '</div>';
        }
    }

    /**
     * Handle AJAX action
     */
    public function handle_ajax_action(): void {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'wp_plugin_boilerplate_nonce' ) ) {
            wp_die( esc_html__( 'Security check failed.', 'wordpress-plugin-boilerplate' ) );
        }

        // Get the action
        $action = sanitize_text_field( $_POST['action'] ?? '' );

        switch ( $action ) {
            case 'plugin_boilerplate_action':
                $this->process_ajax_request();
                break;
            default:
                wp_send_json_error( [ 'message' => __( 'Invalid action.', 'wordpress-plugin-boilerplate' ) ] );
        }
    }

    /**
     * Process AJAX request
     */
    private function process_ajax_request(): void {
        $data = sanitize_text_field( $_POST['data'] ?? '' );

        if ( empty( $data ) ) {
            wp_send_json_error( [ 'message' => __( 'No data provided.', 'wordpress-plugin-boilerplate' ) ] );
        }

        // Process the data (example)
        $result = $this->process_data( $data );

        wp_send_json_success( [
            'message' => __( 'Data processed successfully.', 'wordpress-plugin-boilerplate' ),
            'result'  => $result,
        ] );
    }

    /**
     * Process data (example method)
     *
     * @param string $data The data to process.
     * @return string
     */
    private function process_data( string $data ): string {
        // Example processing logic
        return 'Processed: ' . $data;
    }
}
