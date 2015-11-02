<?php

namespace App;


/**
 * Describes view of application.
 */
interface View
{
    /**
     * Renders specified page and prints it immediately.
     *
     * @param \App\Page $page
     */
    public function renderPage($page);
}
