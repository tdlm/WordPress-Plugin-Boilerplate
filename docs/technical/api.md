# REST API Documentation

This document provides comprehensive documentation for the REST API endpoints included in the WordPress Plugin Boilerplate.

## Overview

The plugin provides a REST API that follows WordPress REST API standards and best practices. All endpoints are namespaced under `/wp-json/wordpress-plugin-boilerplate/v1/`.

## Base URL

```
https://your-site.com/wp-json/wordpress-plugin-boilerplate/v1/
```

## Authentication

### Nonce Authentication

For logged-in users, use WordPress nonces:

```javascript
const nonce = wpPluginBoilerplate.nonce;
```

### Application Passwords

For external applications, use WordPress application passwords:

```bash
curl -X GET \
  -H "Authorization: Basic base64(username:application_password)" \
  https://your-site.com/wp-json/wordpress-plugin-boilerplate/v1/example
```

## Endpoints

### GET /example

Retrieves example data from the plugin.

#### Request

```bash
GET /wp-json/wordpress-plugin-boilerplate/v1/example
```

#### Response

```json
{
  "message": "Hello from Plugin Boilerplate API!",
  "timestamp": 1703123456,
  "version": "1.0.0"
}
```

#### Parameters

None

#### Permissions

- **Public**: No authentication required

### POST /example

Creates new example data.

#### Request

```bash
POST /wp-json/wordpress-plugin-boilerplate/v1/example
Content-Type: application/json

{
  "data": "Example data to process"
}
```

#### Response

```json
{
  "message": "Data created successfully.",
  "result": "API Processed: Example data to process"
}
```

#### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `data` | string | Yes | The data to process |

#### Permissions

- **Admin**: Requires `manage_options` capability

## Error Handling

### Error Response Format

All error responses follow this format:

```json
{
  "code": "rest_error_code",
  "message": "Human readable error message",
  "data": {
    "status": 400
  }
}
```

### Common Error Codes

| Code | Status | Description |
|------|--------|-------------|
| `rest_missing_data` | 400 | Required data is missing |
| `rest_invalid_data` | 400 | Data format is invalid |
| `rest_forbidden` | 403 | User lacks required permissions |
| `rest_not_found` | 404 | Resource not found |
| `rest_server_error` | 500 | Internal server error |

## Rate Limiting

API requests are subject to rate limiting:

- **Public endpoints**: 100 requests per minute
- **Authenticated endpoints**: 1000 requests per minute
- **Admin endpoints**: 5000 requests per minute

Rate limit headers are included in responses:

```
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1703123456
```

## CORS Support

The API supports Cross-Origin Resource Sharing (CORS) for frontend applications:

```php
// CORS headers are automatically added
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Headers: Content-Type, Authorization
```

## Usage Examples

### JavaScript (jQuery)

```javascript
// GET request
$.ajax({
    url: '/wp-json/wordpress-plugin-boilerplate/v1/example',
    method: 'GET',
    success: function(response) {
        console.log(response.message);
    },
    error: function(xhr, status, error) {
        console.error('API Error:', error);
    }
});

// POST request
$.ajax({
    url: '/wp-json/wordpress-plugin-boilerplate/v1/example',
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': wpPluginBoilerplate.nonce
    },
    data: JSON.stringify({
        data: 'Example data'
    }),
    success: function(response) {
        console.log(response.message);
    },
    error: function(xhr, status, error) {
        console.error('API Error:', error);
    }
});
```

### JavaScript (Fetch)

```javascript
// GET request
fetch('/wp-json/wordpress-plugin-boilerplate/v1/example')
    .then(response => response.json())
    .then(data => {
        console.log(data.message);
    })
    .catch(error => {
        console.error('API Error:', error);
    });

// POST request
fetch('/wp-json/wordpress-plugin-boilerplate/v1/example', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': wpPluginBoilerplate.nonce
    },
    body: JSON.stringify({
        data: 'Example data'
    })
})
.then(response => response.json())
.then(data => {
    console.log(data.message);
})
.catch(error => {
    console.error('API Error:', error);
});
```

### PHP

```php
// GET request
$response = wp_remote_get('/wp-json/wordpress-plugin-boilerplate/v1/example');
if (!is_wp_error($response)) {
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    echo $data['message'];
}

// POST request
$response = wp_remote_post('/wp-json/wordpress-plugin-boilerplate/v1/example', [
    'headers' => [
        'Content-Type' => 'application/json',
        'X-WP-Nonce' => wp_create_nonce('wp_rest')
    ],
    'body' => json_encode([
        'data' => 'Example data'
    ])
]);
if (!is_wp_error($response)) {
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    echo $data['message'];
}
```

### cURL

```bash
# GET request
curl -X GET \
  https://your-site.com/wp-json/wordpress-plugin-boilerplate/v1/example

# POST request
curl -X POST \
  -H "Content-Type: application/json" \
  -H "X-WP-Nonce: your_nonce_here" \
  -d '{"data": "Example data"}' \
  https://your-site.com/wp-json/wordpress-plugin-boilerplate/v1/example
```

## Adding Custom Endpoints

### Registering New Endpoints

Add new endpoints in `src/Api/Api.php`:

```php
public function register_routes() {
    // Existing endpoints...
    
    register_rest_route(
        'wordpress-plugin-boilerplate/v1',
        '/custom',
        [
            'methods' => 'GET',
            'callback' => [$this, 'get_custom_data'],
            'permission_callback' => [$this, 'get_permission'],
        ]
    );
}

public function get_custom_data(\WP_REST_Request $request) {
    $data = [
        'custom_field' => 'Custom value',
        'timestamp' => current_time('timestamp'),
    ];
    
    return new \WP_REST_Response($data, 200);
}
```

### Validation and Sanitization

```php
public function register_routes() {
    register_rest_route(
        'wordpress-plugin-boilerplate/v1',
        '/custom',
        [
            'methods' => 'POST',
            'callback' => [$this, 'create_custom_data'],
            'permission_callback' => [$this, 'post_permission'],
            'args' => [
                'title' => [
                    'required' => true,
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'validate_callback' => function($param) {
                        return !empty($param) && strlen($param) <= 255;
                    }
                ],
                'content' => [
                    'required' => false,
                    'type' => 'string',
                    'sanitize_callback' => 'wp_kses_post',
                ]
            ]
        ]
    );
}
```

### Custom Response Format

```php
public function get_custom_data(\WP_REST_Request $request) {
    try {
        $data = $this->fetch_custom_data();
        
        return new \WP_REST_Response([
            'success' => true,
            'data' => $data,
            'message' => 'Data retrieved successfully'
        ], 200);
        
    } catch (Exception $e) {
        return new \WP_Error(
            'custom_error',
            'Failed to retrieve data',
            ['status' => 500]
        );
    }
}
```

## Testing Endpoints

### Using Postman

1. Import the collection
2. Set the base URL to your WordPress site
3. Update authentication headers
4. Test each endpoint

### Using WordPress REST API Tester

1. Install the "REST API Tester" plugin
2. Navigate to Tools > REST API Tester
3. Test your endpoints

### Using cURL

```bash
# Test GET endpoint
curl -X GET \
  https://your-site.com/wp-json/wordpress-plugin-boilerplate/v1/example

# Test POST endpoint
curl -X POST \
  -H "Content-Type: application/json" \
  -H "X-WP-Nonce: your_nonce" \
  -d '{"data": "test"}' \
  https://your-site.com/wp-json/wordpress-plugin-boilerplate/v1/example
```

## Security Considerations

### Input Validation

Always validate and sanitize input:

```php
$title = sanitize_text_field($request->get_param('title'));
$content = wp_kses_post($request->get_param('content'));
```

### Permission Checks

Check user capabilities:

```php
public function post_permission() {
    return current_user_can('manage_options');
}
```

### Nonce Verification

Verify nonces for authenticated requests:

```php
if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
    return new \WP_Error('invalid_nonce', 'Invalid nonce', ['status' => 403]);
}
```

## Performance Optimization

### Caching

Implement caching for frequently accessed data:

```php
public function get_cached_data(\WP_REST_Request $request) {
    $cache_key = 'api_custom_data';
    $cached_data = wp_cache_get($cache_key);
    
    if (false === $cached_data) {
        $cached_data = $this->fetch_expensive_data();
        wp_cache_set($cache_key, $cached_data, '', 3600);
    }
    
    return new \WP_REST_Response($cached_data, 200);
}
```

### Pagination

Implement pagination for large datasets:

```php
public function get_paginated_data(\WP_REST_Request $request) {
    $page = $request->get_param('page') ?: 1;
    $per_page = $request->get_param('per_page') ?: 10;
    
    $data = $this->get_data_with_pagination($page, $per_page);
    
    return new \WP_REST_Response($data, 200);
}
```

## Troubleshooting

### Common Issues

1. **404 Error**: Check endpoint registration and URL
2. **403 Error**: Verify permissions and nonce
3. **500 Error**: Check server logs and error handling
4. **CORS Issues**: Verify CORS headers are set correctly

### Debug Mode

Enable WordPress debug mode to see detailed error messages:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

### Logging

Add logging to track API usage:

```php
error_log('API Request: ' . $request->get_route());
```

## Best Practices

1. **Always validate input** before processing
2. **Use appropriate HTTP status codes**
3. **Implement proper error handling**
4. **Add rate limiting for public endpoints**
5. **Cache expensive operations**
6. **Document all endpoints thoroughly**
7. **Test thoroughly before deployment**
8. **Monitor API usage and performance**
