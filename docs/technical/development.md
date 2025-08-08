# Development Guide

This document covers development practices, workflows, and technical details for working with the WordPress Plugin Boilerplate.

## Development Environment Setup

### Required Tools

1. **PHP 8.2+** with extensions:
   - `curl`
   - `json`
   - `mbstring`
   - `xml`
   - `zip`

2. **Node.js 18+** with npm

3. **Composer** for PHP dependency management

4. **Docker** for local WordPress environment

5. **Git** for version control

### IDE/Editor Setup

Recommended VS Code extensions:
- PHP Intelephense
- WordPress Snippets
- SCSS IntelliSense
- ESLint
- Prettier

### Local Environment

The boilerplate uses `@wordpress/env` for local development:

```bash
# Start the environment
npm run env:start

# Stop the environment
npm run env:stop

# Destroy and recreate
npm run env:destroy

# Clean up
npm run env:clean
```

Environment configuration in `.wp-env.json`:
```json
{
    "core": "WordPress/WordPress#trunk",
    "plugins": ["."],
    "themes": ["https://downloads.wordpress.org/theme/twentytwentyfour.latest-stable.zip"],
    "port": 8888,
    "testsPort": 8889,
    "config": {
        "WP_DEBUG": true,
        "WP_DEBUG_LOG": true,
        "WP_DEBUG_DISPLAY": false,
        "SCRIPT_DEBUG": true
    }
}
```

## Code Organization

### Directory Structure

```
src/
├── Plugin.php              # Main plugin class
├── Admin/                  # Admin functionality
│   ├── Admin.php          # Main admin class
│   ├── Settings.php       # Settings management
│   └── MetaBoxes.php      # Custom meta boxes
├── Frontend/              # Frontend functionality
│   ├── Frontend.php       # Main frontend class
│   ├── Shortcodes.php     # Shortcode handlers
│   └── Widgets.php        # Custom widgets
├── Api/                   # REST API functionality
│   ├── Api.php           # Main API class
│   ├── Endpoints/        # API endpoints
│   └── Authentication.php # API authentication
└── Includes/             # Shared functionality
    ├── Database.php      # Database operations
    ├── Cache.php         # Caching utilities
    └── Helpers.php       # Helper functions
```

### Class Naming Conventions

- Use PascalCase for class names
- Use descriptive names that indicate purpose
- Follow WordPress naming conventions
- Use namespaces to avoid conflicts

```php
namespace YourPlugin\Admin;

class SettingsManager {
    // Class implementation
}
```

### Method Naming Conventions

- Use camelCase for method names
- Use descriptive names that indicate action
- Prefix private methods with underscore (optional)

```php
public function get_user_settings() {
    // Public method
}

private function _validate_settings($settings) {
    // Private method
}
```

## WordPress Integration

### Hook Registration

Register hooks in the constructor or init method:

```php
public function __construct() {
    add_action('init', [$this, 'init']);
    add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    add_filter('the_content', [$this, 'filter_content']);
}
```

### Action Hooks

Use action hooks for functionality that doesn't return values:

```php
// Registering an action
add_action('wp_footer', [$this, 'add_footer_content']);

// Triggering an action
do_action('your_plugin_before_save', $data);
```

### Filter Hooks

Use filter hooks for functionality that modifies data:

```php
// Registering a filter
add_filter('the_content', [$this, 'modify_content']);

// Applying a filter
$modified_data = apply_filters('your_plugin_data', $data);
```

### AJAX Handling

Register AJAX actions for both logged-in and non-logged-in users:

```php
add_action('wp_ajax_your_action', [$this, 'handle_ajax']);
add_action('wp_ajax_nopriv_your_action', [$this, 'handle_ajax']);

public function handle_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'your_nonce')) {
        wp_die('Security check failed');
    }
    
    // Process request
    $result = $this->process_request($_POST);
    
    // Send response
    wp_send_json_success($result);
}
```

## Asset Management

### JavaScript Development

Frontend JavaScript structure:

```javascript
const YourPlugin = {
    init: function() {
        this.bindEvents();
        this.initComponents();
    },
    
    bindEvents: function() {
        $(document).on('click', '.your-trigger', this.handleClick);
    },
    
    handleClick: function(e) {
        e.preventDefault();
        // Handle click event
    }
};

$(document).ready(function() {
    YourPlugin.init();
});
```

Admin JavaScript structure:

```javascript
const YourPluginAdmin = {
    init: function() {
        this.bindEvents();
        this.initTabs();
    },
    
    bindEvents: function() {
        $('.your-admin-trigger').on('click', this.handleAdminAction);
    }
};

$(document).ready(function() {
    YourPluginAdmin.init();
});
```

### SCSS Development

Use BEM methodology with plugin prefix:

```scss
.your-plugin {
    &-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    &-button {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        
        &--primary {
            background-color: #0073aa;
            color: white;
        }
        
        &--secondary {
            background-color: #f0f0f0;
            color: #333;
        }
    }
}
```

### Asset Enqueuing

Enqueue assets properly in WordPress:

```php
public function enqueue_scripts() {
    wp_enqueue_script(
        'your-plugin-frontend',
        plugin_dir_url(__FILE__) . 'assets/js/frontend.js',
        ['jquery'],
        $this->version,
        true
    );
    
    wp_localize_script(
        'your-plugin-frontend',
        'yourPluginData',
        [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('your_nonce'),
        ]
    );
}
```

## Database Operations

### Custom Tables

Create custom tables on plugin activation:

```php
public function create_tables() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'your_plugin_table';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        content text NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
```

### Database Queries

Use WordPress database functions:

```php
// Insert data
$wpdb->insert(
    $wpdb->prefix . 'your_table',
    [
        'title' => $title,
        'content' => $content,
    ],
    ['%s', '%s']
);

// Select data
$results = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}your_table WHERE status = %s",
        'active'
    )
);

// Update data
$wpdb->update(
    $wpdb->prefix . 'your_table',
    ['status' => 'inactive'],
    ['id' => $id],
    ['%s'],
    ['%d']
);
```

## Security Best Practices

### Input Validation

Always validate and sanitize user input:

```php
// Validate input
$title = sanitize_text_field($_POST['title']);
$content = wp_kses_post($_POST['content']);
$email = sanitize_email($_POST['email']);

// Validate data types
if (!is_numeric($_POST['id'])) {
    wp_die('Invalid ID');
}
```

### Nonce Verification

Use nonces for all forms and AJAX requests:

```php
// Create nonce
wp_nonce_field('your_action', 'your_nonce');

// Verify nonce
if (!wp_verify_nonce($_POST['your_nonce'], 'your_action')) {
    wp_die('Security check failed');
}
```

### Capability Checks

Check user capabilities before performing actions:

```php
if (!current_user_can('manage_options')) {
    wp_die('Insufficient permissions');
}
```

### SQL Injection Prevention

Use prepared statements:

```php
$results = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}table WHERE user_id = %d AND status = %s",
        $user_id,
        $status
    )
);
```

## Error Handling

### Exception Handling

Use try-catch blocks for error handling:

```php
try {
    $result = $this->process_data($data);
    return $result;
} catch (Exception $e) {
    error_log('Plugin error: ' . $e->getMessage());
    return false;
}
```

### User Feedback

Provide meaningful error messages:

```php
if (!$result) {
    wp_send_json_error([
        'message' => 'Failed to process data. Please try again.'
    ]);
} else {
    wp_send_json_success([
        'message' => 'Data processed successfully.'
    ]);
}
```

## Testing

### PHP Unit Tests

Create tests in the `tests/` directory:

```php
class YourPluginTest extends WP_UnitTestCase {
    public function setUp(): void {
        parent::setUp();
        // Setup test data
    }
    
    public function test_plugin_initialization() {
        $plugin = new YourPlugin();
        $this->assertInstanceOf(YourPlugin::class, $plugin);
    }
    
    public function test_data_processing() {
        $data = ['test' => 'value'];
        $result = $this->plugin->process_data($data);
        $this->assertTrue($result);
    }
}
```

### JavaScript Tests

Create tests for JavaScript functionality:

```javascript
describe('YourPlugin', function() {
    beforeEach(function() {
        // Setup test environment
    });
    
    it('should initialize properly', function() {
        expect(YourPlugin).toBeDefined();
    });
    
    it('should handle click events', function() {
        // Test click handling
    });
});
```

## Performance Optimization

### Caching

Use WordPress transients for caching:

```php
// Get cached data
$cached_data = get_transient('your_plugin_data');
if (false === $cached_data) {
    $cached_data = $this->fetch_data();
    set_transient('your_plugin_data', $cached_data, HOUR_IN_SECONDS);
}
```

### Asset Optimization

Optimize asset loading:

```php
// Only load assets when needed
if (is_page('your-page')) {
    wp_enqueue_script('your-plugin-specific');
}
```

### Database Optimization

Optimize database queries:

```php
// Use appropriate indexes
// Limit query results
// Cache frequently accessed data
```

## Deployment

### Production Build

1. Build optimized assets:
   ```bash
   npm run build
   ```

2. Install production dependencies:
   ```bash
   composer install --no-dev
   ```

3. Create deployment package:
   ```bash
   npm run plugin-zip
   ```

### Version Management

Update version numbers consistently:

1. Main plugin file header
2. `composer.json`
3. `package.json`
4. Any version constants

### Release Process

1. Update version numbers
2. Build production assets
3. Run tests
4. Create release notes
5. Tag release in Git
6. Deploy to production

## Debugging

### WordPress Debug

Enable WordPress debugging in `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### Browser Developer Tools

Use browser developer tools for:
- JavaScript debugging
- Network request monitoring
- CSS inspection
- Console logging

### Logging

Use WordPress logging functions:

```php
error_log('Plugin debug: ' . print_r($data, true));
```

## Code Quality

### Coding Standards

Follow WordPress coding standards:
- Use WordPress naming conventions
- Follow PSR-12 for modern PHP
- Use proper indentation
- Add meaningful comments

### Code Review

Before committing:
1. Run linting tools
2. Check for security issues
3. Verify functionality
4. Update documentation

### Documentation

Document your code:
- Add PHPDoc comments
- Update README files
- Document API endpoints
- Provide usage examples
