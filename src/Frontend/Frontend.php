<?php

namespace WordPressPluginBoilerplate\Frontend;

use GreatScottPlugins\WordPressPlugin\Hooks\ActionDecoratorHooks;
use GreatScottPlugins\WordPressPlugin\Singleton;

/**
 * Frontend functionality
 *
 * @package WordPressPluginBoilerplate
 */
class Frontend extends Singleton {
    use ActionDecoratorHooks;

    /**
     * Add content to footer
     *
     * @action wp_footer
     */
    public function addFooterContent(): void {
        // Example: Add a simple message to the footer
        if ( true === \is_user_logged_in() ) {
            echo '<div id="plugin-boilerplate-footer" style="display: none;">';
            echo '<p>' . esc_html__( 'Plugin Boilerplate is active!', 'wordpress-plugin-boilerplate' ) . '</p>';
            echo '</div>';
        }
    }

    /**
     * Handle AJAX action
     *
     * @ajax plugin_boilerplate_action
     */
    public function handleAjaxAction(): void {
        // Verify nonce
        if ( false === \wp_verify_nonce( $_POST['nonce'] ?? '', 'wp_plugin_boilerplate_nonce' ) ) {
            \wp_die( esc_html__( 'Security check failed.', 'wordpress-plugin-boilerplate' ) );
        }

        // Get the action
        $action = sanitize_text_field( $_POST['action'] ?? '' );

        switch ( $action ) {
            case 'plugin_boilerplate_action':
                $this->process_ajax_request();
                break;
            default:
                \wp_send_json_error( [ 'message' => __( 'Invalid action.', 'wordpress-plugin-boilerplate' ) ] );
        }
    }

    /**
     * Process AJAX request
     */
    private function processAjaxRequest(): void {
        $data = \sanitize_text_field( $_POST['data'] ?? '' );

        if ( true === empty( $data ) ) {
            \wp_send_json_error( [ 'message' => __( 'No data provided.', 'wordpress-plugin-boilerplate' ) ] );
        }

        // Process the data (example)
        $result = $this->processData( $data );

        \wp_send_json_success( [
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
    private function processData( string $data ): string {
        // Example processing logic
        return 'Processed: ' . $data;
    }
}
