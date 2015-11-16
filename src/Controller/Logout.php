<?php

namespace App\Controller;

use \App\Exception\Redirect;
use \App\Inject;


/**
 * Implements a controller for "logout" page which render form for
 * authentication.
 *
 * @implements \App\Controller
 */
class Logout implements \App\Controller
{
    use Inject\Repository\Sessions;


    /**
     * Creates new controller of "logout" page and sets required dependency.
     */
    public function __construct()
    {
        $this->initSessionsRepo();
    }

    /**
     * Runs controller of "logout".
     *
     * @param null|\App\Request $req   Not really used.
     *                                 For interface implementation only.
     *
     * @throws Redirect   Redirect to login page.
     *
     * @return array   Not really used. For interface implementation only.
     */
    public function run($req = null)
    {
        $this->SessionsRepo->deleteAuthorization();
        throw new Redirect(Redirect::LOGIN_PAGE);
    }
}
