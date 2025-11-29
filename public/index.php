<?php
// public/index.php

// Start the session
session_start();

// Include the Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Include helper functions
require_once __DIR__ . '/../app/core/helpers.php';

/**
 * Renders an error page and terminates the script.
 *
 * @param string $message The error message to display.
 * @param int $httpCode The HTTP status code to set.
 */
function showError($message, $httpCode = 500) {
    http_response_code($httpCode);
    $data['message'] = $message;
    // We can't use the BaseController here, so we'll do a simple view include
    extract($data);
    require __DIR__ . '/../views/error.php';
    exit;
}

// Load database configuration and environment variables
require_once __DIR__ . '/../config/database.php';


// A simple router
$route = isset($_GET['route']) ? $_GET['route'] : 'login';

// Whitelist of allowed routes and their corresponding controllers
$routes = [
    'login' => 'App\\Controllers\\AuthController@login',
    'register' => 'App\\Controllers\\AuthController@register',
    'logout' => 'App\\Controllers\\AuthController@logout',
    'dashboard' => 'App\\Controllers\\DashboardController@index',
    'profile' => 'App\\Controllers\\UserController@profile',
    'users' => 'App\\Controllers\\UserController@index'
];

if (array_key_exists($route, $routes)) {
    list($controllerName, $methodName) = explode('@', $routes[$route]);

    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        $controller->$methodName();
    } else {
        showError("Controller '{$controllerName}' not found.", 404);
    }
} else {
    // Handle 404 - Route not found
    showError('Page not found.', 404);
}
