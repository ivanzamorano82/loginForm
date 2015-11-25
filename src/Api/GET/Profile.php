<?php

namespace App\Api\GET;;

use \App\Inject;

/**
 * Implements a controller for API handler "profile" page which render person's
 * information.
 *
 * @implements \App\Controller
 */
class Profile implements \App\Controller
{
    use Inject\Current\User;


    /**
     * Creates new controller of API handler "profile" page and sets required
     * dependency.
     */
    public function __construct()
    {
        $this->initCurrentUser();
        $this->initUsersRepo();
    }

    /**
     * Runs controller of API handler "profile".
     *
     * @param \App\Request $req  HTTP request to "profile" page.
     *
     * @return array   Parameters for page template rendering.
     */
    public function run($req)
    {
        $user = $this->CurrentUser;
        $user->photo = USER_URL.$user->photo;
        return ['toRender' => ['user' => $user]];
    }
}
