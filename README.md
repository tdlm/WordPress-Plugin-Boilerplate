# WordPress Plugin Boilerplate

A modern, well-structured WordPress plugin boilerplate designed for rapid development of professional WordPress plugins. Built with modern PHP 8.2+, WordPress 6.0+, and contemporary development practices.

## 🚀 Quick Start

### Prerequisites

- **PHP 8.2 or higher**
- **Node.js 20 or higher**
- **Composer** (PHP package manager)
- **Docker** (for local development)

### Installation

1. **Clone the boilerplate:**
   ```bash
   git clone https://github.com/your-org/wordpress-plugin-boilerplate.git my-plugin
   cd my-plugin
   ```

2. **Setup Node.js version:**
   ```bash
   # If using nvm (Node Version Manager)
   nvm use
   
   # Or install Node.js 20+ manually
   ```

3. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

4. **Start development environment:**
   ```bash
   npm run env:start
   ```

5. **Build assets:**
   ```bash
   npm run dev
   ```

6. **Access your plugin at:** `http://localhost:8888`

## 🏗️ Architecture

The boilerplate follows a modular architecture with clear separation of concerns:

```
wordpress-plugin-boilerplate/
├── build/                  # Built assets (generated)
├── docs/                  # Comprehensive documentation
├── src/                   # Source code (PHP, JS, SCSS)
│   ├── Admin/            # Admin functionality
│   ├── Api/              # REST API endpoints
│   ├── Frontend/         # Frontend functionality
│   ├── js/               # JavaScript source files
│   ├── scss/             # SCSS source files
│   ├── index.js          # JavaScript entry point
│   └── index.scss        # SCSS entry point
├── tests/                 # PHPUnit tests
├── .wp-env.json          # WordPress development environment
├── composer.json         # PHP dependencies
├── package.json          # Node.js dependencies
└── wordpress-plugin-boilerplate.php  # Main plugin file
```

## ✨ Features

### 🎯 Core Features
- **Modern PHP 8.2+** with PSR-4 autoloading
- **WordPress 6.0+** compatibility
- **Modular architecture** with clear separation of concerns
- **Great Scott Plugins WordPress Plugin** base framework
- **Local development environment** with `@wordpress/env`

### 🛠️ Development Features
- **Hot reloading** for assets during development
- **Build tools** for optimized production assets
- **Testing framework** with PHPUnit and Jest
- **Code quality tools** with linting and formatting
- **Comprehensive documentation**

### 🔒 Security Features
- **Nonce verification** for all forms and AJAX
- **Input sanitization** and validation
- **Capability checks** for user permissions
- **SQL injection prevention** with prepared statements
- **XSS protection** with proper escaping

### 🎨 Frontend Features
- **SCSS support** with BEM methodology
- **Responsive design** with mobile-first approach
- **JavaScript framework** with jQuery integration
- **AJAX utilities** for seamless communication
- **Component library** with reusable UI elements

### 🔧 Admin Features
- **Settings management** with WordPress Settings API
- **Admin interface** with WordPress admin integration
- **Form handling** with validation and sanitization
- **Tabbed interface** for organized settings
- **User feedback** with success/error messages

### 🌐 API Features
- **REST API endpoints** with proper authentication
- **Data validation** and sanitization
- **Error handling** with proper HTTP status codes
- **Rate limiting** support
- **CORS handling** for cross-origin requests

## 📚 Documentation

Comprehensive documentation is available in the `docs/` directory:

- **[Getting Started](docs/business/getting-started.md)** - Quick start guide
- **[Architecture](docs/technical/architecture.md)** - Technical architecture overview
- **[Development Guide](docs/technical/development.md)** - Development practices and workflows
- **[Features](docs/business/features.md)** - Complete feature overview
- **[API Reference](docs/technical/api.md)** - REST API documentation

## 🛠️ Development Workflow

### Daily Development
```bash
# Start the development environment
npm run env:start

# Start asset compilation in watch mode
npm run dev

# Make changes and test at http://localhost:8888
```

### Building for Production
```bash
# Build optimized assets
npm run build

# Install production dependencies
composer install --no-dev

# Create plugin package
npm run plugin-zip
```

### Testing
```bash
# Run JavaScript unit tests
npm run test:unit

# Run end-to-end tests
npm run test:e2e

# Run PHP unit tests
composer test
```

### Code Quality
```bash
# Format code
npm run format

# Lint JavaScript
npm run lint:js

# Lint CSS
npm run lint:css
```

## 🎯 Customization

### 1. Update Plugin Information
Edit the main plugin file header:
```php
/**
 * Plugin Name: Your Plugin Name
 * Plugin URI: https://yourwebsite.com/plugin
 * Description: Your plugin description
 * Version: 1.0.0
 * Author: Your Name
 * License: GPL v3
 * Text Domain: your-plugin-text-domain
 */
```

### 2. Update Namespace
Change the namespace in all PHP files:
```php
// From
namespace WordPressPluginBoilerplate;

// To
namespace YourPluginNamespace;
```

### 3. Update Constants
Modify constants in the main plugin file:
```php
define('YOUR_PLUGIN_VERSION', '1.0.0');
define('YOUR_PLUGIN_FILE', __FILE__);
define('YOUR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('YOUR_PLUGIN_URL', plugin_dir_url(__FILE__));
```

### 4. Customize CSS Classes
Update the CSS prefix in SCSS files:
```scss
$plugin-prefix: 'your-plugin-name';
```

## 🔧 Adding Features

### Admin Page
```php
// In src/Admin/Admin.php
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
```

### Shortcode
```php
// In src/Frontend/Frontend.php
add_shortcode('your_shortcode', [$this, 'shortcode_callback']);

public function shortcode_callback($atts) {
    $atts = shortcode_atts(['title' => 'Default'], $atts);
    return '<div class="your-content">' . esc_html($atts['title']) . '</div>';
}
```

### REST API Endpoint
```php
// In src/Api/Api.php
register_rest_route('your-plugin/v1', '/data', [
    'methods' => 'GET',
    'callback' => [$this, 'get_data'],
    'permission_callback' => [$this, 'get_permission'],
]);
```

## 🚀 Deployment

### Production Build
1. Run `npm run build` to compile assets
2. Run `composer install --no-dev` for production dependencies
3. Create a ZIP file excluding development files
4. Test thoroughly before deployment

### Version Management
- Update version numbers in the main plugin file
- Update versions in `composer.json` and `package.json`
- Create release notes and tag releases

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📋 Requirements

### Server Requirements
- **PHP**: 8.2 or higher
- **WordPress**: 6.0 or higher
- **MySQL**: 5.7 or higher (or MariaDB 10.3 or higher)

### Development Requirements
- **Node.js**: 20 or higher
- **Composer**: Latest version
- **Docker**: For local development environment

## 🔒 Security

The boilerplate includes comprehensive security measures:
- Input validation and sanitization
- Nonce verification for all forms
- Capability checks for user permissions
- SQL injection prevention
- XSS protection
- CSRF protection

## 📈 Performance

Built with performance in mind:
- Optimized asset loading
- Efficient database queries
- Caching support
- Lazy loading capabilities
- CDN integration support

## 🧪 Testing

Comprehensive testing framework:
- PHPUnit for PHP testing
- Jest for JavaScript testing
- WordPress integration tests
- End-to-end testing
- Code coverage reporting

## 📖 License

This project is licensed under the GPL v3 or later - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

- **Documentation**: Check the `docs/` directory
- **Issues**: Create an issue on GitHub
- **Discussions**: Use GitHub Discussions
- **Wiki**: Check the project wiki

## 🙏 Acknowledgments

- Built on the foundation of [Great Scott Plugins WordPress Plugin](https://github.com/great-scott-plugins/wordpress-plugin)
- Uses [@wordpress/env](https://github.com/WordPress/gutenberg/tree/trunk/packages/env) for local development
- Follows [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)

## 📊 Project Status

- **Version**: 1.0.0
- **WordPress Compatibility**: 6.0+
- **PHP Compatibility**: 8.2+
- **License**: GPL v3
- **Status**: Active Development

---

**Ready to build amazing WordPress plugins?** Start with this boilerplate and create professional, secure, and maintainable WordPress plugins in no time!
