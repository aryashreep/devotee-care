<?php
// app/core/helpers.php

if (!function_exists('find_name_by_id')) {
    /**
     * Searches an array of associative arrays for a specific ID and returns the corresponding name.
     *
     * @param array $haystack The array to search.
     * @param mixed $needleId The ID to look for.
     * @param string $idKey The key for the ID in the haystack arrays.
     * @param string $nameKey The key for the name in the haystack arrays.
     * @return string|null The name if found, otherwise null.
     */
    function find_name_by_id(array $haystack, $needleId, string $idKey = 'id', string $nameKey = 'name'): ?string {
        if ($needleId === null) {
            return null;
        }
        foreach ($haystack as $item) {
            if (isset($item[$idKey]) && $item[$idKey] == $needleId) {
                return $item[$nameKey];
            }
        }
        return null;
    }
}

if (!function_exists('find_names_by_ids')) {
    /**
     * Searches an array of associative arrays for multiple IDs and returns an array of corresponding names.
     *
     * @param array $haystack The array to search (e.g., all languages).
     * @param array $needleIds An array of IDs to look for (e.g., user's language IDs).
     * @param string $idKey The key for the ID in the haystack arrays.
     * @param string $nameKey The key for the name in the haystack arrays.
     * @return array An array of names corresponding to the found IDs.
     */
    function find_names_by_ids(array $haystack, array $needleIds, string $idKey = 'id', string $nameKey = 'name'): array {
        $names = [];
        if (empty($needleIds)) {
            return $names;
        }
        $lookup = array_column($haystack, $nameKey, $idKey);
        foreach ($needleIds as $id) {
            if (isset($lookup[$id])) {
                $names[] = $lookup[$id];
            }
        }
        return $names;
    }
}

if (!function_exists('url')) {
    /**
     * Generates a relative URL for routing.
     *
     * @param string $path The path for the URL.
     * @return string The relative URL.
     */
    function url(string $path = ''): string {
        return '/' . trim($path, '/');
    }
}

if (!function_exists('asset')) {
    /**
     * Generates a full URL for a static asset.
     *
     * @param string $path The path to the asset.
     * @return string The full URL for the asset.
     */
    function asset(string $path = ''): string {
        $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
        return htmlspecialchars($scheme . '://' . $host . '/' . trim($path, '/'));
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Generates and stores a CSRF token in the session.
     *
     * @return string The CSRF token.
     */
    function csrf_token(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('validate_csrf_token')) {
    /**
     * Validates the submitted CSRF token.
     *
     * @return bool True if the token is valid, false otherwise.
     */
    function validate_csrf_token(): bool {
        if (isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            return true;
        }
        return false;
    }
}
