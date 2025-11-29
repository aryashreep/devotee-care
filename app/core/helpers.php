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
