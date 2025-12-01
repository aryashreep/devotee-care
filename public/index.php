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
function showError($message, $httpCode = 500)
{
    http_response_code($httpCode);
    $data['message'] = $message;
    // We can't use the BaseController here, so we'll do a simple view include
    extract($data);
    require __DIR__ . '/../views/error.php';
    exit;
}

// Load database configuration and environment variables
require_once __DIR__ . '/../config/database.php';


// --- START: Improved Router ---
// Get the requested URI and remove query string
$requestUri = strtok($_SERVER['REQUEST_URI'], '?');
// Remove the base path if the app is in a subdirectory (optional, but good practice)
$basePath = ''; // If your app is at http://localhost/yourapp, set this to '/yourapp'
$route = trim(str_replace($basePath, '', $requestUri), '/');
// Default to 'login' if the route is empty
if (empty($route)) {
    $route = 'login';
}

// Redirect to login if user is not authenticated and the route is not public
$publicRoutes = ['login', 'register'];
if (!isset($_SESSION['user_id']) && !in_array($route, $publicRoutes)) {
    header('Location: ' . url('login'));
    exit;
}
// --- END: Improved Router ---

// Whitelist of allowed routes and their corresponding controllers
$routes = [
    'login' => 'App\\Controllers\\AuthController@login',
    'register' => 'App\\Controllers\\AuthController@register',
    'logout' => 'App\\Controllers\\AuthController@logout',
    'dashboard' => 'App\\Controllers\\DashboardController@index',
    'profile' => 'App\\Controllers\\UserController@profile',
    'users' => 'App\\Controllers\\UserController@index',
    'user/delete/(\d+)' => 'App\\Controllers\\UserController@delete',
    'user/edit/(\d+)' => 'App\\Controllers\\UserController@edit',
    'user/view/(\d+)' => 'App\\Controllers\\UserController@viewUser',

    // Bhakti Sadan
    'bhakti-sadan' => 'App\\Controllers\\BhaktiSadanController@index',
    'bhakti-sadan/create' => 'App\\Controllers\\BhaktiSadanController@create',
    'bhakti-sadan/edit/(\d+)' => 'App\\Controllers\\BhaktiSadanController@edit',
    'bhakti-sadan/delete/(\d+)' => 'App\\Controllers\\BhaktiSadanController@delete',

    // Lookups
    'lookup/educations' => 'App\\Controllers\\LookupController@index@educations@Education',
    'lookup/educations/create' => 'App\\Controllers\\LookupController@create@educations@Education',
    'lookup/educations/edit/(\d+)' => 'App\\Controllers\\LookupController@edit@educations@Education',
    'lookup/educations/delete/(\d+)' => 'App\\Controllers\\LookupController@delete@educations@Education',

    'lookup/professions' => 'App\\Controllers\\LookupController@index@professions@Profession',
    'lookup/professions/create' => 'App\\Controllers\\LookupController@create@professions@Profession',
    'lookup/professions/edit/(\d+)' => 'App\\Controllers\\LookupController@edit@professions@Profession',
    'lookup/professions/delete/(\d+)' => 'App\\Controllers\\LookupController@delete@professions@Profession',

    'lookup/blood_groups' => 'App\\Controllers\\LookupController@index@blood_groups@Blood Group',
    'lookup/blood_groups/create' => 'App\\Controllers\\LookupController@create@blood_groups@Blood Group',
    'lookup/blood_groups/edit/(\d+)' => 'App\\Controllers\\LookupController@edit@blood_groups@Blood Group',
    'lookup/blood_groups/delete/(\d+)' => 'App\\Controllers\\LookupController@delete@blood_groups@Blood Group',

    'lookup/languages' => 'App\\Controllers\\LookupController@index@languages@Language',
    'lookup/languages/create' => 'App\\Controllers\\LookupController@create@languages@Language',
    'lookup/languages/edit/(\d+)' => 'App\\Controllers\\LookupController@edit@languages@Language',
    'lookup/languages/delete/(\d+)' => 'App\\Controllers\\LookupController@delete@languages@Language',

    'lookup/sevas' => 'App\\Controllers\\LookupController@index@sevas@Seva',
    'lookup/sevas/create' => 'App\\Controllers\\LookupController@create@sevas@Seva',
    'lookup/sevas/edit/(\d+)' => 'App\\Controllers\\LookupController@edit@sevas@Seva',
    'lookup/sevas/delete/(\d+)' => 'App\\Controllers\\LookupController@delete@sevas@Seva',
];

$routeFound = false;
foreach ($routes as $path => $handler) {
    $pattern = '#^' . $path . '$#';
    if (preg_match($pattern, $route, $matches)) {
        array_shift($matches);
        $params = $matches;

        list($controllerName, $methodName, $lookupType, $lookupName) = array_pad(explode('@', $handler), 4, null);

        if (class_exists($controllerName)) {
            $controller = null;
            if ($controllerName === 'App\\Controllers\\LookupController') {
                $controller = new $controllerName($lookupType, $lookupName);
            } else {
                $controller = new $controllerName();
            }

            call_user_func_array([$controller, $methodName], $params);
            $routeFound = true;
            break;
        } else {
            showError("Controller '{$controllerName}' not found.", 404);
        }
    }
}

if (!$routeFound) {
    // Handle 404 - Route not found
    showError('Page not found.', 404);
}
