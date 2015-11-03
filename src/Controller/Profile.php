<?php

namespace App\Controller;


/**
 * Implements a controller for "profile" page which render person's information.
 *
 * @implements \App\Controller
 */
class Profile implements \App\Controller
{
    /**
     * Creates new controller of "profile" page and sets required dependency.
     */
    public function __construct()
    {
        // Does nothing.
    }

    /**
     * Runs controller of "profile".
     *
     * @param \App\Request $req  HTTP request to "profile" page.
     *
     * @return array   Parameters for page template rendering.
     */
    public function run($req)
    {
        return ['toRender' => [
            'xxx' => 'профиль',
        ]];
    }
}


