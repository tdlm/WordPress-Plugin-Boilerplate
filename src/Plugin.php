<?php

namespace WordPressPluginBoilerplate;

use GreatScottPlugins\WordPressPlugin\Plugin as BasePlugin;

/**
 * Main plugin class
 *
 * @package WordPressPluginBoilerplate
 */
class Plugin extends BasePlugin
{

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
    public function init(): void
    {
        // Load text domain for translations
        \load_plugin_textdomain('wordpress-plugin-boilerplate', false, dirname(\plugin_basename(WP_PLUGIN_BOILERPLATE_PLUGIN_FILE)) . '/languages');

        // Initialize components
        $this->initComponents();
    }

    /**
     * Initialize plugin components
     */
    private function initComponents(): void
    {
        // Initialize admin functionality
        if (true === \is_admin()) {
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
     * @action wp_enqueue_scripts, 11
     */
    public function enqueueScripts(): void
    {
        // Enqueue frontend base assets if present
        $this->enqueueScript('wordpress-plugin-boilerplate/frontend');
        $this->enqueueStyle('wordpress-plugin-boilerplate/frontend');

        // Localize script for AJAX when the 'frontend' script exists
        $handle = $this->scriptHandles['frontend'] ?? null;
        if ($handle === null) {
            return;
        }

        \wp_localize_script(
            $handle,
            'wpPluginBoilerplate',
            [
                'ajaxUrl' => \admin_url('admin-ajax.php'),
                'nonce' => \wp_create_nonce('wp_plugin_boilerplate_nonce'),
            ]
        );
    }

    /**
     * Enqueue admin scripts and styles
     *
     * @action admin_enqueue_scripts
     */
    public function enqueueAdminScripts(): void
    {
        // Enqueue admin base assets if present
        $this->enqueueScript('wordpress-plugin-boilerplate/admin');
        $this->enqueueStyle('wordpress-plugin-boilerplate/admin');
    }

  
    /**
     * Register assets.
     *
     * @action init
     */
    public function registerAssets()
    {
        $asset_uri = \trailingslashit(WP_PLUGIN_BOILERPLATE_PLUGIN_URL) . 'assets/build/';
        $asset_root = \trailingslashit(WP_PLUGIN_BOILERPLATE_PLUGIN_DIR) . 'assets/build/';
        $asset_files = glob($asset_root . '*.asset.php');

        // Enqueue webpack loader.js, if it exists.
        if (true === is_readable($asset_root . 'runtime.js')) {
            \wp_enqueue_script(
                'wordpress-plugin-boilerplate/runtime',
                $asset_uri . 'runtime.js',
                [],
                filemtime($asset_root . 'runtime.js')
            );
        }

        foreach ($asset_files as $asset_file) {
            $asset_script = require($asset_file);

            $asset_filename = basename($asset_file);

            $asset_slug_parts = explode('.asset.php', $asset_filename);
            $asset_slug = array_shift($asset_slug_parts);

            $asset_handle = sprintf('wordpress-plugin-boilerplate/%s', $asset_slug);

            $stylesheet_path = $asset_root . $asset_slug . '.css';
            $stylesheet_uri = $asset_uri . $asset_slug . '.css';

            $javascript_path = $asset_root . $asset_slug . '.js';
            $javascript_uri = $asset_uri . $asset_slug . '.js';

            if (true === is_readable($stylesheet_path)) {
                $style_dependencies = [];

                \wp_register_style(
                    $asset_handle,
                    $stylesheet_uri,
                    [],
                    $asset_script['version']
                );
            }

            if (true === is_readable($javascript_path)) {
                \wp_register_script(
                    $asset_handle,
                    $javascript_uri,
                    $asset_script['dependencies'],
                    $asset_script['version']
                );
            }
        }
    }
    /**
     * Enqueue script.
     *
     * @param string $handle
     * @param string $src
     * @param string[] $dependencies
     * @param string|bool|null $version
     * @param bool $in_footer
     *
     * @return void
     */
    public static function enqueueScript(
        string $handle,
        string $src = '',
        array  $dependencies = [],
               $version = false,
        bool   $in_footer = true
    )
    {
        $localizes = [];

        switch ($handle) {
            case 'wordpress-plugin-boilerplate/main':
                $localizes[] = [
                    'object_name' => 'WordPressPluginBoilerplate',
                    'value' => [
                        'ajaxUrl' => \admin_url('admin-ajax.php'),
                        'nonce' => \wp_create_nonce('wp_plugin_boilerplate_nonce'),
                    ],
                ];
                break;
        }

        \wp_enqueue_script($handle, $src, $dependencies, $version, $in_footer);

        if (0 < count($localizes)) {
            foreach ($localizes as $localize) {
                $object_name = $localize['object_name'] ?? '';
                $local_params = true === isset($localize['value']) && true === is_array($localize['value']) ?
                    $localize['value'] :
                    [];

                \wp_localize_script(
                    $handle,
                    $object_name,
                    $local_params
                );
            }
        }
    }

    /**
     * Enqueue style.
     *
     * @param string $handle
     * @param string $src
     * @param string[] $dependencies
     * @param string|bool|null $version
     * @param string $media
     *
     * @return void
     */
    public static function enqueueStyle($handle, string $src = '', $dependencies = [], $version = false, $media = 'all')
    {
        \wp_enqueue_style($handle, $src, $dependencies, $version, $media);
    }
}
