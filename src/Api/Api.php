<?php

namespace WordPressPluginBoilerplate\Api;

/**
 * REST API functionality
 *
 * @package WordPressPluginBoilerplate
 */
class Api {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }

    /**
     * Register REST API routes
     */
    public function register_routes(): void {
        register_rest_route(
            'wordpress-plugin-boilerplate/v1',
            '/example',
            [
                'methods'             => 'GET',
                'callback'            => [ $this, 'get_example_data' ],
                'permission_callback' => [ $this, 'get_permission' ],
            ]
        );

        register_rest_route(
            'wordpress-plugin-boilerplate/v1',
            '/example',
            [
                'methods'             => 'POST',
                'callback'            => [ $this, 'create_example_data' ],
                'permission_callback' => [ $this, 'post_permission' ],
            ]
        );
    }

    /**
     * Get permission callback
     *
     * @return bool
     */
    public function get_permission(): bool {
        return true; // Public endpoint
    }

    /**
     * Post permission callback
     *
     * @return bool
     */
    public function post_permission(): bool {
        return current_user_can( 'manage_options' );
    }

    /**
     * Get example data
     *
     * @param \WP_REST_Request $request The request object.
     * @return \WP_REST_Response
     */
    public function get_example_data( \WP_REST_Request $request ): \WP_REST_Response {
        $data = [
            'message' => __( 'Hello from Plugin Boilerplate API!', 'wordpress-plugin-boilerplate' ),
            'timestamp' => current_time( 'timestamp' ),
            'version' => WP_PLUGIN_BOILERPLATE_VERSION,
        ];

        return new \WP_REST_Response( $data, 200 );
    }

    /**
     * Create example data
     *
     * @param \WP_REST_Request $request The request object.
     * @return \WP_REST_Response
     */
    public function create_example_data( \WP_REST_Request $request ): \WP_REST_Response {
        $params = $request->get_params();
        $data   = sanitize_text_field( $params['data'] ?? '' );

        if ( empty( $data ) ) {
            return new \WP_REST_Response( 
                [ 'error' => __( 'Data is required.', 'wordpress-plugin-boilerplate' ) ], 
                400 
            );
        }

        // Process the data (example)
        $result = $this->process_api_data( $data );

        return new \WP_REST_Response( [
            'message' => __( 'Data created successfully.', 'wordpress-plugin-boilerplate' ),
            'result'  => $result,
        ], 201 );
    }

    /**
     * Process API data (example method)
     *
     * @param string $data The data to process.
     * @return string
     */
    private function process_api_data( string $data ): string {
        // Example processing logic
        return 'API Processed: ' . $data;
    }
}
