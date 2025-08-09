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
     * Registered script handles keyed by base filename (no extension).
     *
     * @var array<string,string>
     */
    private array $script_handles = [];

    /**
     * Registered style handles keyed by base filename (no extension).
     *
     * @var array<string,string>
     */
    private array $style_handles = [];

    /**
     * Initialize the plugin
     *
     * @action init
     */
    public function init(): void {
        // Load text domain for translations
        \load_plugin_textdomain( 'wordpress-plugin-boilerplate', false, dirname( \plugin_basename( WP_PLUGIN_BOILERPLATE_PLUGIN_FILE ) ) . '/languages' );

        // Auto-register assets from build directory
        $this->register_assets();

        // Initialize components
        $this->init_components();
    }

    /**
     * Initialize plugin components
     */
    private function init_components(): void {
        // Initialize admin functionality
        if ( \is_admin() ) {
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
        // Enqueue default base assets if present
        $this->enqueue_script( 'index' );
        $this->enqueue_style( 'index' );

        // Localize script for AJAX when the 'index' script exists
        $handle = $this->script_handles['index'] ?? null;
        if ( $handle === null ) {
            return;
        }

        \wp_localize_script(
            $handle,
            'wpPluginBoilerplate',
            [
                'ajaxUrl' => \admin_url( 'admin-ajax.php' ),
                'nonce'   => \wp_create_nonce( 'wp_plugin_boilerplate_nonce' ),
            ]
        );
    }

    /**
     * Enqueue admin scripts and styles
     *
     * @action admin_enqueue_scripts
     */
    public function enqueue_admin_scripts(): void {
        // Enqueue default base assets if present
        $this->enqueue_script( 'index' );
        $this->enqueue_style( 'index' );
    }

    /**
     * Auto-register all JS and CSS in the build directory.
     */
    private function register_assets(): void {
        $build_dir = WP_PLUGIN_BOILERPLATE_PLUGIN_DIR . 'build/';
        $build_url = WP_PLUGIN_BOILERPLATE_PLUGIN_URL . 'build/';

        if ( ! is_dir( $build_dir ) ) {
            return;
        }

        // Register scripts
        $js_files = glob( $build_dir . '*.js' ) ?: [];
        foreach ( $js_files as $js_path ) {
            $base   = basename( $js_path, '.js' );
            $handle = $this->build_handle( $base, 'script' );
            $src    = $build_url . $base . '.js';

            [ $deps, $ver ] = $this->get_asset_meta( $build_dir, $base );
            if ( $ver === null ) {
                $mtime = @filemtime( $js_path );
                $ver   = is_int( $mtime ) ? (string) $mtime : WP_PLUGIN_BOILERPLATE_VERSION;
            }

            \wp_register_script( $handle, $src, $deps, $ver, true );
            $this->script_handles[ $base ] = $handle;
        }

        // Register styles
        $css_files = glob( $build_dir . '*.css' ) ?: [];
        foreach ( $css_files as $css_path ) {
            $base   = basename( $css_path, '.css' );
            $handle = $this->build_handle( $base, 'style' );
            $src    = $build_url . $base . '.css';

            [ , $ver ] = $this->get_asset_meta( $build_dir, $base );
            if ( $ver === null ) {
                $mtime = @filemtime( $css_path );
                $ver   = is_int( $mtime ) ? (string) $mtime : WP_PLUGIN_BOILERPLATE_VERSION;
            }

            \wp_register_style( $handle, $src, [], $ver );
            $this->style_handles[ $base ] = $handle;
        }
    }

    /**
     * Helper: enqueue a registered script by base filename (without extension).
     *
     * @param string $base Base filename without extension (e.g. 'index').
     * @param array<string> $deps_override Optional dependency override.
     * @param bool $in_footer Whether to load in the footer.
     */
    public function enqueue_script( string $base, array $deps_override = [], bool $in_footer = true ): void {
        $handle = $this->script_handles[ $base ] ?? null;

        if ( $handle === null ) {
            // Attempt late registration if file was added after init.
            $this->register_assets();
            $handle = $this->script_handles[ $base ] ?? null;
            if ( $handle === null ) {
                return;
            }
        }

        if ( $deps_override !== [] ) {
            $scripts    = \wp_scripts();
            $registered = $scripts->registered[ $handle ] ?? null;
            if ( $registered === null ) {
                return;
            }

            $src = isset( $registered->src ) ? $registered->src : '';
            $ver = isset( $registered->ver ) ? $registered->ver : WP_PLUGIN_BOILERPLATE_VERSION;
            if ( $src === '' ) {
                return;
            }

            \wp_deregister_script( $handle );
            \wp_register_script( $handle, $src, $deps_override, $ver, $in_footer );
        }

        \wp_enqueue_script( $handle );
    }

    /**
     * Helper: enqueue a registered style by base filename (without extension).
     *
     * @param string $base Base filename without extension (e.g. 'index').
     * @param array<string> $deps_override Optional dependency override.
     * @param string $media Media attribute value.
     */
    public function enqueue_style( string $base, array $deps_override = [], string $media = 'all' ): void {
        $handle = $this->style_handles[ $base ] ?? null;

        if ( $handle === null ) {
            // Attempt late registration if file was added after init.
            $this->register_assets();
            $handle = $this->style_handles[ $base ] ?? null;
            if ( $handle === null ) {
                return;
            }
        }

        if ( $deps_override !== [] ) {
            $styles     = \wp_styles();
            $registered = $styles->registered[ $handle ] ?? null;
            if ( $registered === null ) {
                return;
            }

            $src = isset( $registered->src ) ? $registered->src : '';
            $ver = isset( $registered->ver ) ? $registered->ver : WP_PLUGIN_BOILERPLATE_VERSION;
            if ( $src === '' ) {
                return;
            }

            \wp_deregister_style( $handle );
            \wp_register_style( $handle, $src, $deps_override, $ver, $media );
        }

        \wp_enqueue_style( $handle );
    }

    /**
     * Build a unique handle for the asset.
     *
     * @param string $base Base filename without extension.
     * @param string $type 'script'|'style'
     * @return string
     */
    private function build_handle( string $base, string $type ): string {
        $suffix = $type === 'script' ? 'js' : 'css';
        return 'wordpress-plugin-boilerplate-' . $base . '-' . $suffix;
    }

    /**
     * Return [deps, ver] from a WP Scripts .asset.php file if available.
     *
     * @param string $build_dir Absolute path to build directory with trailing slash.
     * @param string $base Base filename without extension.
     * @return array{0: array<int,string>, 1: ?string}
     */
    private function get_asset_meta( string $build_dir, string $base ): array {
        $asset_file = $build_dir . $base . '.asset.php';
        if ( ! file_exists( $asset_file ) ) {
            return [ [], null ];
        }

        $data = require $asset_file;

        if ( ! is_array( $data ) ) {
            return [ [], null ];
        }

        $deps = ( isset( $data['dependencies'] ) && is_array( $data['dependencies'] ) ) ? $data['dependencies'] : [];
        $ver  = ( isset( $data['version'] ) && ( is_string( $data['version'] ) || is_int( $data['version'] ) ) ) ? (string) $data['version'] : null;

        return [ $deps, $ver ];
    }
}
