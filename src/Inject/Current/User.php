<?php

namespace App\Inject\Current;

use \App\Inject;

/**
 * Provides class field that contains instance of authorized account.
 */
trait User
{
    use Inject\Repository\Sessions;
    use Inject\Repository\Users;


    /**
     * Injected authorized account instance.
     *
     * @var \App\Model\User
     */
    public $CurrentUser;

    /**
     * Retrieves current account from IoC-container if it is not set yet.
     */
    public function initCurrentUser()
    {
        if (isset($this->AuthorizedUser)) {
            return;
        }
        $this->initSessionsRepo();
        $sessionInfo = $this->SessionsRepo->getAuthorizedUser();
        if ($sessionInfo !== null) {
            $this->initUsersRepo();
            $user = $this->UsersRepo->getUserById($sessionInfo['userId']);
            $user->decryptData($sessionInfo['login']);
            $this->CurrentUser = $user;
        }
    }
}
