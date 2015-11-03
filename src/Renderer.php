<?php

namespace App;


/**
 * Describes renderer of application.
 */
interface Renderer
{
    /**
     * Renders specified page.
     *
     * @param \App\Page $page
     */
    public function renderPage($page);
}
