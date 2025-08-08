=== WordPress Plugin Boilerplate ===
Contributors: wordpress-plugin-boilerplate
Tags: boilerplate, development, framework, plugin
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 8.2
Stable tag: 1.0.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

A modern, well-structured WordPress plugin boilerplate designed for rapid development of professional WordPress plugins.

== Description ==

The WordPress Plugin Boilerplate is a comprehensive foundation for building modern WordPress plugins. It provides a solid architecture, development tools, and best practices to help you create professional, secure, and maintainable plugins quickly.

= Key Features =

* **Modern PHP 8.2+** with PSR-4 autoloading
* **WordPress 6.0+** compatibility
* **Modular architecture** with clear separation of concerns
* **Great Scott Plugins WordPress Plugin** base framework
* **Local development environment** with @wordpress/env
* **Hot reloading** for assets during development
* **Build tools** for optimized production assets
* **Testing framework** with PHPUnit and Jest
* **Code quality tools** with linting and formatting
* **Comprehensive documentation**

= Architecture =

The boilerplate follows a modular architecture:

* **Admin Module** - Handles all WordPress admin functionality
* **Frontend Module** - Manages public-facing features
* **API Module** - Provides REST API endpoints
* **Core Plugin Class** - Orchestrates all components

= Development Features =

* **SCSS Support** with BEM methodology
* **Responsive Design** with mobile-first approach
* **JavaScript Framework** with jQuery integration
* **AJAX Utilities** for seamless communication
* **Component Library** with reusable UI elements
* **Settings Management** with WordPress Settings API
* **Form Handling** with validation and sanitization

= Security Features =

* **Nonce Verification** for all forms and AJAX
* **Input Sanitization** and validation
* **Capability Checks** for user permissions
* **SQL Injection Prevention** with prepared statements
* **XSS Protection** with proper escaping

= Getting Started =

1. Clone the boilerplate
2. Install dependencies with `composer install` and `npm install`
3. Start development environment with `npm run env:start`
4. Build assets with `npm run dev`
5. Customize for your plugin

= Documentation =

Comprehensive documentation is available in the `docs/` directory:

* Getting Started Guide
* Architecture Overview
* Development Guide
* Feature Documentation
* API Reference

= Requirements =

* PHP 8.2 or higher
* WordPress 6.0 or higher
* Node.js 18 or higher (for development)
* Composer (for PHP dependencies)

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wordpress-plugin-boilerplate` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Plugin Boilerplate screen to configure the plugin

== Frequently Asked Questions ==

= What is this boilerplate for? =

This boilerplate provides a solid foundation for building WordPress plugins with modern development practices, security features, and a clean architecture.

= Do I need to know PHP 8.2+ to use this? =

While PHP 8.2+ is recommended for the best experience, the boilerplate can be adapted for older PHP versions with some modifications.

= Can I use this for commercial plugins? =

Yes, this boilerplate is licensed under GPL v3, which allows for commercial use. However, any derivative works must also be licensed under GPL v3.

= How do I customize this for my plugin? =

1. Update the plugin header information
2. Change the namespace from `WordPressPluginBoilerplate` to your plugin namespace
3. Update constants and CSS prefixes
4. Add your specific functionality

= Is there documentation available? =

Yes, comprehensive documentation is included in the `docs/` directory, covering everything from getting started to advanced development practices.

== Screenshots ==

1. Admin settings page with organized tabs
2. Frontend display with responsive design
3. REST API endpoint testing
4. Development environment setup

== Changelog ==

= 1.0.0 =
* Initial release
* Modern PHP 8.2+ support
* WordPress 6.0+ compatibility
* Modular architecture
* Development environment setup
* Comprehensive documentation
* Security features
* Testing framework

== Upgrade Notice ==

= 1.0.0 =
Initial release of the WordPress Plugin Boilerplate.

== Development ==

This plugin is actively developed on GitHub. Contributions are welcome!

* [GitHub Repository](https://github.com/your-org/wordpress-plugin-boilerplate)
* [Issue Tracker](https://github.com/your-org/wordpress-plugin-boilerplate/issues)
* [Documentation](https://github.com/your-org/wordpress-plugin-boilerplate/tree/main/docs)

== Credits ==

Built on the foundation of [Great Scott Plugins WordPress Plugin](https://github.com/great-scott-plugins/wordpress-plugin).

Uses [@wordpress/env](https://github.com/WordPress/gutenberg/tree/trunk/packages/env) for local development.

Follows [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/).
