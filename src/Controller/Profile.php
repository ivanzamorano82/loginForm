<?php

namespace App\Controller;

use \App\Inject;

/**
 * Implements a controller for "profile" page which render person's information.
 *
 * @implements \App\Controller
 */
class Profile implements \App\Controller
{
    use Inject\Current\User;


    /**
     * Creates new controller of "profile" page and sets required dependency.
     */
    public function __construct()
    {
        $this->initCurrentUser();
        $this->initUsersRepo();
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
            'user' => $this->CurrentUser,
        ]];
    }
}


