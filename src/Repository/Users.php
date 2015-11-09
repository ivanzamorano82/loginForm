<?php

namespace App\Repository;

use App\Inject;
use App\Storage\MySQL as DB;


/**
 * Implements users repository on top of MySQL.
 *
 * @todo Can do different implementation.
 * @todo For example get users from another storage (cache).
 *
 * @package App\Repository
 */
class Users
{
    use Inject\Storage\MySQL;


    /**
     * Creates new users repository injecting its dependencies.
     */
    public function __construct()
    {
        $this->initMySQL();
    }

    /**
     * Adds new user to repository and update user ID.
     *
     * @param \App\Model\User $user   User that must be added.
     *
     * @return array   All users in required language.
     */
    public function addNewUser($user)
    {
        $conn = $this->MySQL->getConn();
        $sql = "INSERT INTO `".DB::TBL_USERS."` ".
               "SET `fio`=?,`login`=?,`phone`=?,
                    `email`=?, `pass`=?";
        $st = $conn->prepare($sql);
        $st->execute([
            $user->fio, $user->login, $user->phone,
            $user->email, $user->pass,
        ]);
        $user->id = $conn->lastInsertId();
    }

    /**
     * Checks for existing of user by given login.
     *
     * @param string $login   Login by which the user searched.
     *
     * @return bool   ID of required user if it exists.
     */
    public function getUserIdByLogin($login){
        $sql = "SELECT `id` FROM `".DB::TBL_USERS."` WHERE `login`=? LIMIT 1";
        $st = $this->MySQL->getConn()->prepare($sql);
        $st->execute([$login]);
        return $st->fetchColumn();
    }

    /**
     * Checks for existing of user by given email.
     *
     * @param string $email   Email by witch the user searched.
     *
     * @return bool   ID of required user if it exists.
     */
    public function getUserIdByEmail($email){
        $sql = "SELECT `id` FROM `".DB::TBL_USERS."` WHERE `email`=? LIMIT 1";
        $st = $this->MySQL->getConn()->prepare($sql);
        $st->execute([$email]);
        return $st->fetchColumn();
    }
}
