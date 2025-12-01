<?php
// tests/helpers.php

if (!function_exists('showError')) {
    function showError($message, $code = 500)
    {
        // In a test environment, we just want to know that the function was called.
        // We can throw an exception to make the test fail with a clear message.
        throw new \Exception("showError called with message: {$message}");
    }
}
