<?php
// app/core/helpers.php

/**
 * Generates a URL for a given route.
 *
 * @param string $route The route name.
 * @param array $params Optional query parameters.
 * @return string The generated URL.
 */
function url($route, $params = []) {
    $url = '/index.php?route=' . $route;
    if (!empty($params)) {
        $url .= '&' . http_build_query($params);
    }
    return $url;
}

/**
 * Generates a CSRF token and stores it in the session.
 *
 * @return string The generated token.
 */
function csrf_token() {
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    return $token;
}

/**
 * Validates a CSRF token.
 *
 * @param string $token The token to validate.
 * @return bool True if the token is valid, false otherwise.
 */
function validate_csrf_token($token) {
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        // Token is valid, clear it to prevent reuse
        unset($_SESSION['csrf_token']);
        return true;
    }
    return false;
}
