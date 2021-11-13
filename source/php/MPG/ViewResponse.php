<?php

namespace MPG;

use Capsule\Response;

class ViewResponse extends Response {

    public function __construct(int $statusCode, string $viewName, array $viewData = []) {

        return parent::__construct($statusCode, self::render($viewName, $viewData));
        
    }

    /**
     * Renders a view.
     * 
     * @return string View result.
     */
    private static function render(string $viewName, array $viewData) : string {

        extract($viewData);

        ob_start();
        require ABS_PATH . '/views/' . $viewName . '.view.php';
        $viewResult = (string) ob_get_contents();
        ob_end_clean();

        return $viewResult;

    }

}
