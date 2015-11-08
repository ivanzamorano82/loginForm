<?php

namespace App\Inject\Repository;

use App\Repository;


/**
 * Provides class field that contains instance of Users repository.
 */
trait Users
{
    /**
     * Injected Users repository.
     *
     * @var \App\Repository\Users
     */
    public $UsersRepo;


    /**
     * Init Users repository instance.
     */
    public function initUsersRepo()
    {
        if (!isset($this->UsersRepo)) {
            $this->UsersRepo = new Repository\Users();
        }
    }
}
