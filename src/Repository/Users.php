<?php

namespace App\Repository;

use \App\Inject;
use \App\Model\User;
use \App\Storage\MySQL as DB;


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
               "SET `fio`=?,`login`=?,`loginHash`=?,`phone`=?,
                    `email`=?, `emailHash`=?,`pass`=?,`photo`=?";
        $st = $conn->prepare($sql);
        $st->execute([
            $user->fio, $user->login, $user->loginHash, $user->phone,
            $user->email, $user->emailHash, $user->pass, $user->photo
        ]);
        $user->id = $conn->lastInsertId();
    }

    /**
     * Gets user ID by given login hash.
     *
     * @param string $loginHash   Login hash by which the user searched.
     *
     * @return bool   ID of required user if it exists.
     */
    public function getUserIdByLoginHash($loginHash)
    {
        $sql = "SELECT `id` FROM `".DB::TBL_USERS."` ".
               "WHERE `loginHash`=? LIMIT 1";
        $st = $this->MySQL->getConn()->prepare($sql);
        $st->execute([$loginHash]);
        return $st->fetchColumn();
    }

    /**
     * Get user by given login hash.
     *
     * @param string $loginHash   Login hash by which the user searched.
     *
     * @return null|User   Required user if it exists.
     */
    public function getUserByLoginHash($loginHash)
    {
        $sql = "SELECT `id`,`login`,`loginHash`,`email`,`emailHash`,".
               "`pass`,`fio`,`phone`,`photo` ".
               "FROM `".DB::TBL_USERS."` ".
               "WHERE `loginHash`=? LIMIT 1";
        $st = $this->MySQL->getConn()->prepare($sql);
        $st->execute([$loginHash]);
        $r = $st->fetch();
        if (empty($r)) {
            return null;
        }
        $user = new User();
        foreach ([
            'id', 'login', 'loginHash', 'email', 'emailHash',
            'pass', 'fio', 'phone', 'photo'
        ] as $param) {
            $user->{$param} = $r[$param];
        }
        return $user;
    }

    /**
     * Gets user ID by given email hash.
     *
     * @param string $emailHash   Email hash by witch the user searched.
     *
     * @return bool   ID of required user if it exists.
     */
    public function getUserIdByEmailHash($emailHash)
    {
        $sql = "SELECT `id` FROM `".DB::TBL_USERS."` ".
               "WHERE `emailHash`=? LIMIT 1";
        $st = $this->MySQL->getConn()->prepare($sql);
        $st->execute([$emailHash]);
        return $st->fetchColumn();
    }

    /**
     * Get user by given email hash.
     *
     * @param string $emailHash   Email hash by which the user searched.
     *
     * @return null|User   Required user if it exists.
     */
    public function getUserByEmailHash($emailHash)
    {
        $sql = "SELECT `id`,`login`,`loginHash`,`email`,`emailHash`,".
                      "`pass`,`fio`,`phone`,`photo` ".
               "FROM `".DB::TBL_USERS."` ".
               "WHERE `emailHash`=? LIMIT 1";
        $st = $this->MySQL->getConn()->prepare($sql);
        $st->execute([$emailHash]);
        $r = $st->fetch();
        if (empty($r)) {
            return null;
        }
        $user = new User();
        foreach ([
            'id', 'login', 'loginHash', 'email', 'emailHash',
            'pass', 'fio', 'phone', 'photo'
        ] as $param) {
            $user->{$param} = $r[$param];
        }
        return $user;
    }

    /**
     * Save user's data into repository.
     *
     * @param User $user   User that must be stored.
     */
    public function saveUser($user)
    {
        $sql = "UPDATE `".DB::TBL_USERS."` ".
               "SET `fio`=?,`login`=?,`loginHash`=?,`phone`=?,".
                    "`email`=?, `emailHash`=?,`pass`=?,`photo`=? ".
               "WHERE `id`=?";
        $st = $this->MySQL->getConn()->prepare($sql);
        $st->execute([
            $user->fio, $user->login, $user->loginHash,
            $user->phone, $user->email, $user->emailHash,
            $user->pass, $user->photo, $user->id,
        ]);
    }
}
