<?php

namespace App\Controller\Api;


/**
 * Implements a controller for API handler "signup".
 *
 * @implements \App\Controller
 */
class Signup implements \App\Controller
{
    /**
     * Creates new controller of API handler "signUp"
     * and sets required dependency.
     */
    public function __construct()
    {
        // Does nothing.
    }

    /**
     * Runs controller of API handler "signUp".
     *
     * @param \App\Request $req  HTTP request to handler.
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