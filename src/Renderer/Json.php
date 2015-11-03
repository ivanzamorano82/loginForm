<?php

namespace App\Renderer;

use \App\Renderer;


/**
 * Implements JSON renderer of application pages.
 *
 * @implements Renderer
 */
class Json implements Renderer
{
    /**
     * Renders specified page.
     * Sets HTTP status response code accordingly to code in passed page.
     *
     * @param \App\Page $page  Page to render.
     */
    public function renderPage($page)
    {
        if (isset($page->status)) {
            http_response_code($page->status);
        }
        header('Content-type: application/json', true);
        echo json_encode($page->toRender);
    }
}
