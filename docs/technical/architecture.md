# Plugin Architecture

This document explains the technical architecture and design patterns used in the WordPress Plugin Boilerplate.

## Architecture Overview

The plugin follows a modular architecture with clear separation of concerns:

```
┌─────────────────────────────────────────────────────────────┐
│                    WordPress Core                           │
├─────────────────────────────────────────────────────────────┤
│                 Plugin Boilerplate                          │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐        │
│  │    Admin    │  │   Frontend  │  │     API     │        │
│  │   Module    │  │   Module    │  │   Module    │        │
│  └─────────────┘  └─────────────┘  └─────────────┘        │
│           │              │              │                  │
│           └──────────────┼──────────────┘                  │
│                          │                                 │
│  ┌─────────────────────────────────────────────────────┐   │
│  │              Core Plugin Class                      │   │
│  │           (Plugin.php)                              │   │
│  └─────────────────────────────────────────────────────┘   │
│                          │                                 │
│  ┌─────────────────────────────────────────────────────┐   │
│  │           Great Scott Plugins Base                  │   │
│  │         (WordPress Plugin Library)                  │   │
│  └─────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

## Design Patterns

### 1. Dependency Injection

The plugin uses dependency injection to manage dependencies between components:

```php
class Plugin extends BasePlugin {
    private $admin;
    private $frontend;
    private $api;

    public function __construct() {
        parent::__construct();
        
        // Initialize components with dependencies
        $this->admin = new Admin\Admin();
        $this->frontend = new Frontend\Frontend();
        $this->api = new Api\Api();
    }
}
```

### 2. Service Container Pattern

Components are organized as services that can be easily swapped or extended:

```php
// Each module is a service
class Admin {
    public function __construct() {
        $this->init_hooks();
    }
    
    private function init_hooks() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'init_settings']);
    }
}
```

### 3. Hook and Filter Pattern

The plugin extensively uses WordPress hooks and filters for extensibility:

```php
// Registering hooks
add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);

// Using filters for customization
$data = apply_filters('plugin_boilerplate_data', $data);
```

## Class Structure

### Main Plugin Class

The main `Plugin` class serves as the entry point and orchestrator:

```php
class Plugin extends BasePlugin {
    // Properties
    private $version;
    private $plugin_name;
    
    // Methods
    public function __construct();
    private function init_hooks();
    public function init();
    private function init_components();
    public function enqueue_scripts();
    public function enqueue_admin_scripts();
}
```

### Module Classes

Each module follows a consistent structure:

```php
class ModuleName {
    // Constructor
    public function __construct() {
        $this->init_hooks();
    }
    
    // Hook initialization
    private function init_hooks() {
        // Register WordPress hooks
    }
    
    // Public methods for WordPress callbacks
    public function callback_method();
    
    // Private helper methods
    private function helper_method();
}
```

## Namespace Structure

The plugin uses PSR-4 autoloading with clear namespace organization:

```
WordPressPluginBoilerplate\
├── Plugin.php              # Main plugin class
├── Admin\                  # Admin functionality
│   └── Admin.php
├── Frontend\               # Frontend functionality
│   └── Frontend.php
├── Api\                    # REST API functionality
│   └── Api.php
└── Tests\                  # Test classes
    ├── Admin\
    ├── Frontend\
    └── Api\
```

## Asset Management

### JavaScript Architecture

Frontend JavaScript follows a modular pattern:

```javascript
const PluginBoilerplate = {
    init: function() {
        this.bindEvents();
        this.initComponents();
    },
    
    bindEvents: function() {
        // Event binding
    },
    
    initComponents: function() {
        // Component initialization
    }
};
```

### CSS Architecture

CSS follows BEM methodology with plugin-specific prefixes:

```scss
.plugin-boilerplate {
    &-container { }
    &-section { }
    &-button {
        &--primary { }
        &--secondary { }
    }
}
```

## Security Patterns

### 1. Nonce Verification

All AJAX requests use WordPress nonces:

```php
if (!wp_verify_nonce($_POST['nonce'], 'plugin_boilerplate_nonce')) {
    wp_die('Security check failed');
}
```

### 2. Capability Checks

Admin actions check user capabilities:

```php
if (!current_user_can('manage_options')) {
    return;
}
```

### 3. Input Sanitization

All user input is sanitized:

```php
$data = sanitize_text_field($_POST['data']);
```

## Error Handling

### 1. Graceful Degradation

The plugin handles errors gracefully:

```php
try {
    $result = $this->process_data($data);
} catch (Exception $e) {
    error_log('Plugin error: ' . $e->getMessage());
    return false;
}
```

### 2. User Feedback

Users receive appropriate feedback:

```php
wp_send_json_success([
    'message' => 'Operation completed successfully'
]);
```

## Performance Considerations

### 1. Lazy Loading

Components are loaded only when needed:

```php
if (is_admin()) {
    new Admin\Admin();
}
```

### 2. Asset Optimization

Scripts and styles are enqueued efficiently:

```php
wp_enqueue_script(
    'plugin-boilerplate-frontend',
    plugin_dir_url(__FILE__) . 'assets/js/frontend.js',
    ['jquery'],
    $this->version,
    true
);
```

### 3. Caching

Data is cached when appropriate:

```php
$cached_data = wp_cache_get('plugin_data');
if (false === $cached_data) {
    $cached_data = $this->fetch_data();
    wp_cache_set('plugin_data', $cached_data, '', 3600);
}
```

## Testing Strategy

### 1. Unit Tests

Each class has corresponding unit tests:

```php
class PluginTest extends WP_UnitTestCase {
    public function test_plugin_initialization() {
        $plugin = new Plugin();
        $this->assertInstanceOf(Plugin::class, $plugin);
    }
}
```

### 2. Integration Tests

Tests verify WordPress integration:

```php
class AdminIntegrationTest extends WP_UnitTestCase {
    public function test_admin_menu_creation() {
        // Test admin menu creation
    }
}
```

## Extension Points

### 1. Action Hooks

The plugin provides action hooks for extension:

```php
do_action('plugin_boilerplate_init', $this);
do_action('plugin_boilerplate_admin_init', $this->admin);
```

### 2. Filter Hooks

Data can be modified through filters:

```php
$data = apply_filters('plugin_boilerplate_data', $data);
$settings = apply_filters('plugin_boilerplate_settings', $settings);
```

### 3. Override Methods

Classes can be extended:

```php
class CustomPlugin extends Plugin {
    protected function init_components() {
        // Custom component initialization
        parent::init_components();
    }
}
```

## Best Practices

### 1. Code Organization
- Keep classes focused on single responsibilities
- Use meaningful method and variable names
- Follow WordPress coding standards
- Document complex logic

### 2. Error Handling
- Always validate input
- Provide meaningful error messages
- Log errors appropriately
- Handle edge cases

### 3. Performance
- Minimize database queries
- Use appropriate caching strategies
- Optimize asset loading
- Monitor memory usage

### 4. Security
- Validate and sanitize all input
- Use WordPress security functions
- Check user capabilities
- Protect against common vulnerabilities
