<?php

namespace App\Controller;

use App\Exception\Redirect;
use App\Exception\UsePage;


/**
 * Implements a controller for "index" page which render form for
 * authentication.
 *
 * @implements \App\Controller
 */
class Index implements \App\Controller
{
    /**
     * Creates new controller of "index" page and sets required dependency.
     */
    public function __construct()
    {
        // Does nothing.
    }

    /**
     * Runs controller of "index".
     *
     * @param \App\Request $req  HTTP request to "index" page.
     *
     * @return array   Parameters for page template rendering.
     */
    public function run($req)
    {
        return ['toRender' => [
            'xxx' => 'index',
        ]];
    }
}
