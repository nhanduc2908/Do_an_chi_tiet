<?php
/**
 * WebApp Frontend Entry Point
 * 
 * This file handles the initial request and loads the appropriate resources
 */

// -------------------- Error Reporting --------------------
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/error.log');

// -------------------- Define Paths --------------------
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);
define('VIEWS_PATH', ROOT_PATH . '/resources/views');

// -------------------- Session Configuration --------------------
session_start();

// -------------------- Load Configuration --------------------
$config = [
    'app_name' => 'WebApp',
    'app_env' => getenv('APP_ENV') ?: 'production',
    'debug' => getenv('APP_DEBUG') === 'true',
    'timezone' => 'Asia/Ho_Chi_Minh'
];

date_default_timezone_set($config['timezone']);

// -------------------- Router --------------------
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];
$path = str_replace(dirname($script_name), '', $request_uri);
$path = parse_url($path, PHP_URL_PATH);
$path = trim($path, '/');

// -------------------- Route Definitions --------------------
$routes = [
    '' => 'pages/welcome',
    '/' => 'pages/welcome',
    '/dashboard' => 'pages/dashboard/index',
    '/login' => 'pages/auth/login',
    '/register' => 'pages/auth/register',
    '/forgot-password' => 'pages/auth/forgot-password',
    '/verify-otp' => 'pages/auth/verify-otp',
    '/verify-key' => 'pages/auth/verify-key',
    '/users' => 'pages/users/index',
    '/users/create' => 'pages/users/create',
    '/evaluations' => 'pages/evaluations/index',
    '/reports' => 'pages/reports/index',
    '/audit' => 'pages/audit/index',
    '/devices' => 'pages/devices/index',
    '/devices/link' => 'pages/devices/link',
    '/devices/mirror' => 'pages/devices/mirror'
];

// -------------------- Handle Static Assets --------------------
$static_extensions = ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'eot'];
$path_parts = pathinfo($path);
$extension = $path_parts['extension'] ?? '';

if (in_array($extension, $static_extensions)) {
    $static_file = PUBLIC_PATH . '/' . $path;
    if (file_exists($static_file) && is_file($static_file)) {
        $mime_types = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2'
        ];
        
        header('Content-Type: ' . ($mime_types[$extension] ?? 'application/octet-stream'));
        header('Cache-Control: public, max-age=31536000');
        readfile($static_file);
        exit;
    }
}

// -------------------- Route Handling --------------------
$view_file = $routes[$path] ?? null;

if (!$view_file) {
    http_response_code(404);
    $view_file = 'errors/404';
}

// -------------------- Load View --------------------
$view_path = VIEWS_PATH . '/' . str_replace('/', DIRECTORY_SEPARATOR, $view_file) . '.blade.php';

if (!file_exists($view_path)) {
    http_response_code(404);
    $view_path = VIEWS_PATH . '/errors/404.blade.php';
    
    if (!file_exists($view_path)) {
        die('404 - Page Not Found');
    }
}

// -------------------- Simple Blade Parser (or include existing Laravel if available) --------------------
function render_view($view_path, $data = []) {
    extract($data);
    ob_start();
    include $view_path;
    return ob_get_clean();
}

// -------------------- Load Blade Template System --------------------
try {
    // Try to load Laravel's blade if available
    if (file_exists(ROOT_PATH . '/vendor/autoload.php')) {
        require_once ROOT_PATH . '/vendor/autoload.php';
        // Use Laravel's blade rendering
    }
    
    echo render_view($view_path, [
        'config' => $config,
        'user' => $_SESSION['user'] ?? null,
        'current_path' => $path
    ]);
    
} catch (Exception $e) {
    if ($config['debug']) {
        die('Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
    } else {
        http_response_code(500);
        include VIEWS_PATH . '/errors/500.blade.php';
    }
}