# WordPress Plugin Boilerplate Documentation

This documentation explains the structure and purpose of each component in the WordPress Plugin Boilerplate.

## Overview

The WordPress Plugin Boilerplate is designed to provide a solid foundation for building modern WordPress plugins. It follows WordPress coding standards and best practices while providing a clean, maintainable structure that can be easily extended.

## Project Structure

```
wordpress-plugin-boilerplate/
├── assets/                 # Frontend assets (CSS, JS, images)
│   ├── css/               # Compiled CSS files
│   ├── js/                # JavaScript files
│   └── scss/              # SCSS source files
├── docs/                  # Documentation
├── src/                   # PHP source code
│   ├── Admin/            # Admin functionality
│   ├── Api/              # REST API endpoints
│   └── Frontend/         # Frontend functionality
├── tests/                 # PHPUnit tests
├── vendor/                # Composer dependencies
├── .wp-env.json          # WordPress development environment config
├── composer.json         # PHP dependencies
├── package.json          # Node.js dependencies and scripts
├── README.md             # Project overview
└── wordpress-plugin-boilerplate.php  # Main plugin file
```

## Core Components

### Main Plugin File (`wordpress-plugin-boilerplate.php`)

This is the entry point of your plugin. It contains:
- Plugin header information (name, description, version, etc.)
- Security checks to prevent direct access
- Plugin constants definition
- Autoloader inclusion
- Plugin initialization
- Activation/deactivation hooks

### Source Code (`src/`)

The `src/` directory contains all the PHP classes organized by functionality:

#### Plugin Class (`src/Plugin.php`)
The main plugin class that extends the Great Scott Plugins WordPress Plugin base class. It handles:
- Plugin initialization
- Hook registration
- Asset enqueuing
- Component initialization

#### Admin Module (`src/Admin/`)
Contains all admin-related functionality:
- Admin menu creation
- Settings pages
- Admin forms and validation
- Admin-specific AJAX handlers

#### Frontend Module (`src/Frontend/`)
Contains all frontend functionality:
- Frontend hooks and filters
- Public-facing features
- Frontend AJAX handlers
- Shortcodes and widgets

#### API Module (`src/Api/`)
Contains REST API functionality:
- Custom REST API endpoints
- API authentication
- Data validation and sanitization
- API response formatting

### Assets (`assets/`)

The assets directory contains all frontend resources:

#### CSS Files (`assets/css/`)
- `frontend.css` - Styles for public-facing pages
- `admin.css` - Styles for WordPress admin pages

#### JavaScript Files (`assets/js/`)
- `frontend.js` - Frontend JavaScript functionality
- `admin.js` - Admin JavaScript functionality

#### SCSS Files (`assets/scss/`)
- `frontend.scss` - SCSS source for frontend styles
- `admin.scss` - SCSS source for admin styles

### Configuration Files

#### `composer.json`
Defines PHP dependencies and autoloading:
- `great-scott-plugins/wordpress-plugin` - Base plugin framework
- `wordpress/env` - Local development environment
- `wordpress/scripts` - Build tools

#### `package.json`
Defines Node.js dependencies and build scripts:
- `@wordpress/scripts` - WordPress build tools
- `@wordpress/env` - Local development environment
- Various npm scripts for development and building

#### `.wp-env.json`
Configures the local WordPress development environment:
- WordPress version
- Plugin loading
- Theme loading
- Port configuration
- Debug settings

## Development Workflow

### Local Development
1. Run `composer install` to install PHP dependencies
2. Run `npm install` to install Node.js dependencies
3. Run `npm run env:start` to start the local WordPress environment
4. Access your plugin at `http://localhost:8888`

### Building Assets
- `npm run build` - Build production assets
- `npm run dev` - Start development mode with hot reloading
- `npm run format` - Format code using WordPress standards
- `npm run lint:js` - Lint JavaScript files
- `npm run lint:css` - Lint CSS files

### Testing
- `npm run test:unit` - Run JavaScript unit tests
- `npm run test:e2e` - Run end-to-end tests
- `composer test` - Run PHP unit tests

## Best Practices

### Code Organization
- Follow PSR-4 autoloading standards
- Use namespaces for all classes
- Keep classes focused on single responsibilities
- Use dependency injection where appropriate

### WordPress Standards
- Follow WordPress coding standards
- Use WordPress hooks and filters
- Sanitize and validate all user input
- Use WordPress nonces for security
- Follow WordPress naming conventions

### Security
- Always validate and sanitize user input
- Use WordPress nonces for AJAX requests
- Check user capabilities before performing actions
- Escape output using appropriate WordPress functions
- Use prepared statements for database queries

### Performance
- Enqueue scripts and styles properly
- Use WordPress transients for caching
- Minimize database queries
- Optimize asset loading
- Use lazy loading where appropriate

## Customization

### Adding New Features
1. Create new classes in the appropriate `src/` subdirectory
2. Register hooks in the main Plugin class
3. Add any necessary assets to the `assets/` directory
4. Update documentation

### Styling
- Modify SCSS files in `assets/scss/`
- Use the provided CSS classes and utilities
- Follow the established design system
- Ensure responsive design

### JavaScript
- Extend the existing JavaScript objects
- Use the provided AJAX utilities
- Follow WordPress JavaScript standards
- Handle errors gracefully

## Deployment

### Production Build
1. Run `npm run build` to compile assets
2. Run `composer install --no-dev` to install production dependencies
3. Create a ZIP file excluding development files
4. Test the plugin thoroughly before deployment

### Version Management
- Update version numbers in the main plugin file
- Update version numbers in `composer.json` and `package.json`
- Create release notes
- Tag releases in version control

## Support and Contributing

For questions, issues, or contributions:
1. Check the documentation first
2. Search existing issues
3. Create a new issue with detailed information
4. Follow the contribution guidelines

## License

This boilerplate is licensed under the GPL v3 or later, same as WordPress.
