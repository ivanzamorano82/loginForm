<?php

namespace App\Repository;


/**
 * Implements session repository via PHP sessions mechanism.
 *
 * @implements Repository\Sessions
 */
class Sessions
{
    /**
     * Creates new sessions repository and initializes PHP session
     * if has not been initialized yet.
     */
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Gives user in repository "authorized" status and stores user ID.
     *
     * @param int $userId   ID of authorized user.
     * @param int $login    Login of authorized user.
     */
    public function setAuthorization($userId, $login)
    {
        $_SESSION['user'] = [
            'logged' => true,
            'userId' => $userId,
            'login' => $login,
        ];
    }

    /**
     * Checks in repository if site visitor has "authorized" status.
     *
     * @return bool  Returns true if status exists, otherwise false.
     */
    public function isAuthorized()
    {
        return ($_SESSION['user']['logged'] === true);
    }

    /**
     * Removes from repository "authorized" status and info about current user.
     */
    public function deleteAuthorization()
    {
        unset($_SESSION['user']);
    }

    /**
     * Returns from repository ID of authorized user.
     *
     * @return null|int   User ID.
     */
    public function getAuthorizedUser()
    {
        return (isset($_SESSION['user']))
            ? $_SESSION['user']
            : null;
    }

    /**
     *  Closes PHP session.
     */
    public function __destruct(){
        session_write_close();
    }
}
