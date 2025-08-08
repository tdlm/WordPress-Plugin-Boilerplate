# Getting Started with WordPress Plugin Boilerplate

This guide will help you quickly get started with the WordPress Plugin Boilerplate to build your own WordPress plugin.

## Prerequisites

Before you begin, ensure you have the following installed:

- **PHP 8.2 or higher**
- **Node.js 20 or higher**
- **Composer** (PHP package manager)
- **Git** (for version control)
- **Docker** (for local development environment)

## Quick Start

### 1. Clone the Boilerplate

```bash
git clone https://github.com/your-org/wordpress-plugin-boilerplate.git my-plugin
cd my-plugin
```

### 2. Setup Node.js Version

The project includes an `.nvmrc` file for consistent Node.js versioning:

```bash
# If using nvm (Node Version Manager)
nvm use

# Or install Node.js 20+ manually
```

### 3. Install Dependencies

Install PHP dependencies:
```bash
composer install
```

Install Node.js dependencies:
```bash
npm install
```

### 4. Start Local Development Environment

Start the WordPress development environment:
```bash
npm run env:start
```

This will:
- Start a local WordPress installation
- Load your plugin automatically
- Make it available at `http://localhost:8888`

### 5. Build Assets

For development with hot reloading:
```bash
npm run dev
```

For production build:
```bash
npm run build
```

## Customizing Your Plugin

### 1. Update Plugin Information

Edit the main plugin file `wordpress-plugin-boilerplate.php`:

```php
/**
 * Plugin Name: Your Plugin Name
 * Plugin URI: https://yourwebsite.com/plugin
 * Description: Your plugin description
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL v3
 * Text Domain: your-plugin-text-domain
 */
```

### 2. Update Namespace

Change the namespace in all PHP files from `WordPressPluginBoilerplate` to your plugin namespace:

```php
// In src/Plugin.php
namespace YourPluginNamespace;

// Update composer.json autoload section
"autoload": {
    "psr-4": {
        "YourPluginNamespace\\": "src/"
    }
}
```

### 3. Update Constants

Modify the constants in the main plugin file:

```php
define('YOUR_PLUGIN_VERSION', '1.0.0');
define('YOUR_PLUGIN_FILE', __FILE__);
define('YOUR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('YOUR_PLUGIN_URL', plugin_dir_url(__FILE__));
```

### 4. Customize CSS Classes

Update the CSS prefix in SCSS files:

```scss
// In assets/scss/frontend.scss and admin.scss
$plugin-prefix: 'your-plugin-name';
```

## Adding Your First Feature

### 1. Create a New Admin Page

Add a new admin page in `src/Admin/Admin.php`:

```php
public function add_admin_menu() {
    add_menu_page(
        'Your Plugin',
        'Your Plugin',
        'manage_options',
        'your-plugin',
        [$this, 'admin_page'],
        'dashicons-admin-generic',
        30
    );
}

public function admin_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p>Welcome to your plugin!</p>
    </div>
    <?php
}
```

### 2. Add Frontend Functionality

Add frontend features in `src/Frontend/Frontend.php`:

```php
public function __construct() {
    add_action('wp_footer', [$this, 'add_footer_content']);
    add_shortcode('your_plugin_shortcode', [$this, 'shortcode_callback']);
}

public function shortcode_callback($atts) {
    $atts = shortcode_atts([
        'title' => 'Default Title'
    ], $atts);
    
    return '<div class="your-plugin-content">' . esc_html($atts['title']) . '</div>';
}
```

### 3. Create API Endpoints

Add REST API endpoints in `src/Api/Api.php`:

```php
public function register_routes() {
    register_rest_route(
        'your-plugin/v1',
        '/data',
        [
            'methods' => 'GET',
            'callback' => [$this, 'get_data'],
            'permission_callback' => [$this, 'get_permission'],
        ]
    );
}

public function get_data(\WP_REST_Request $request) {
    return new \WP_REST_Response([
        'message' => 'Hello from your plugin!'
    ], 200);
}
```

## Development Workflow

### 1. Daily Development

1. Start the development environment:
   ```bash
   npm run env:start
   ```

2. Start asset compilation in watch mode:
   ```bash
   npm run dev
   ```

3. Make changes to your code
4. Test in the browser at `http://localhost:8888`
5. Commit your changes

### 2. Testing Your Plugin

Run JavaScript tests:
```bash
npm run test:unit
```

Run end-to-end tests:
```bash
npm run test:e2e
```

Run PHP tests:
```bash
composer test
```

### 3. Code Quality

Format your code:
```bash
npm run format
```

Lint JavaScript:
```bash
npm run lint:js
```

Lint CSS:
```bash
npm run lint:css
```

## Building for Production

### 1. Prepare Production Build

Build optimized assets:
```bash
npm run build
```

Install production dependencies:
```bash
composer install --no-dev
```

### 2. Create Plugin Package

Create a ZIP file excluding development files:
```bash
npm run plugin-zip
```

Or manually create a ZIP with these files:
- `src/` directory
- `assets/` directory
- `languages/` directory (if any)
- Main plugin file
- `readme.txt`
- `composer.json` (for dependencies)

### 3. Test Production Build

1. Install the ZIP file on a test WordPress site
2. Test all functionality
3. Verify no development files are included
4. Check for any missing dependencies

## Common Tasks

### Adding a New Setting

1. Add the setting to the admin form in `src/Admin/Admin.php`
2. Handle the setting in the sanitization method
3. Use the setting in your plugin logic

### Creating a Shortcode

1. Register the shortcode in `src/Frontend/Frontend.php`
2. Create the callback method
3. Add any necessary CSS/JS for the shortcode

### Adding AJAX Functionality

1. Add AJAX actions in your module classes
2. Handle the requests with proper nonce verification
3. Return JSON responses using `wp_send_json_success()` or `wp_send_json_error()`

### Creating Custom Post Types

1. Add post type registration in the appropriate module
2. Add any custom fields or meta boxes
3. Handle saving and displaying the custom data

## Troubleshooting

### Common Issues

**Plugin not loading:**
- Check that the main plugin file has the correct header
- Verify all required dependencies are installed
- Check for PHP errors in the error log

**Assets not loading:**
- Ensure assets are built (`npm run build`)
- Check file paths in the enqueue methods
- Verify file permissions

**Admin page not appearing:**
- Check user capabilities in `add_menu_page()`
- Verify the callback method exists
- Check for JavaScript errors

**AJAX not working:**
- Verify nonce is being sent and verified
- Check that the AJAX action is properly registered
- Ensure proper user capabilities

### Getting Help

1. Check the documentation in the `docs/` directory
2. Review the example code in the boilerplate
3. Check WordPress developer documentation
4. Search existing issues in the repository

## Next Steps

After setting up your basic plugin:

1. **Add Features**: Implement your plugin's core functionality
2. **Add Tests**: Write unit and integration tests
3. **Optimize**: Improve performance and user experience
4. **Document**: Add user and developer documentation
5. **Deploy**: Prepare for distribution or sale

## Best Practices

### Code Organization
- Keep related functionality together
- Use meaningful class and method names
- Follow WordPress coding standards
- Document complex logic

### Security
- Always validate and sanitize input
- Use WordPress nonces for forms and AJAX
- Check user capabilities
- Escape output properly

### Performance
- Minimize database queries
- Use appropriate caching
- Optimize asset loading
- Monitor memory usage

### User Experience
- Provide clear error messages
- Add helpful admin notices
- Use consistent styling
- Test on different devices and browsers
