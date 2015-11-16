<?php

namespace App\Controller;

use \App\Exception\Redirect;
use \App\Inject;


/**
 * Implements a controller for "index" page which render form for
 * authentication.
 *
 * @implements \App\Controller
 */
class Index implements \App\Controller
{
    use Inject\Current\User;


    /**
     * Creates new controller of "index" page and sets required dependency.
     */
    public function __construct()
    {
        $this->initCurrentUser();
    }

    /**
     * Runs controller of "index".
     *
     * @param \App\Request $req  HTTP request to "index" page.
     *
     * @throws Redirect   Redirect to login page if user has been authorized
     *                    already.
     *
     * @return array   Parameters for page template rendering.
     */
    public function run($req)
    {
        if ($this->CurrentUser !== null) {
            throw new Redirect(Redirect::PROFILE_PAGE);
        }
        return ['toRender' => []];
    }
}
