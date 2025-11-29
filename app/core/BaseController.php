<?php
// app/core/BaseController.php

namespace App\Core;

class BaseController {
    /**
     * Loads a view file.
     *
     * @param string $view The name of the view file to load.
     * @param array $data Data to be made available to the view.
     */
    protected function view($view, $data = []) {
        // Construct the full path to the view file
        $viewPath = __DIR__ . '/../../views/' . $view . '.php';

        if (file_exists($viewPath)) {
            // Start output buffering
            ob_start();

            // Include the view file. The $data array will be available within it.
            require $viewPath;

            // End buffering and return the content
            return ob_get_clean();
        } else {
            showError("View '{$view}' not found.", 404);
        }
    }
}
