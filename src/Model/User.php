<?php

namespace App\Model;


/**
 * Describes a user of system.
 */
class User
{
    /**
     * Unique ID of user in repository.
     *
     * @var int
     */
    public $id;

    /**
     * Name and last name of user.
     *
     * @var string
     */
    public $fio;

    /**
     * Login of user.
     *
     * @var int
     */
    public $login;

    /**
     * Phone of user if it exists.
     *
     * @var string
     */
    public $phone;

    /**
     * Email of user.
     *
     * @var string
     */
    public $email;

    /**
     * Password of user.
     *
     * @var string
     */
    public $pass;

    /**
     * Encrypts given account's password.
     *
     * @param string $password     Password string to be encrypted of.
     *
     * @return string       Encrypted data.
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
}
