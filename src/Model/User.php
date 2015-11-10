<?php

namespace App\Model;
use App\Util\StringEncryption;


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
     * @var string
     */
    public $login;

    /**
     * Login hash in md5 format.
     *
     * @var string
     */
    public $loginHash;

    /**
     * Phone of user if it exists.
     *
     * @var string
     */
    public $phone = '';

    /**
     * Email of user.
     *
     * @var string
     */
    public $email;

    /**
     * Email hash in md5 format.
     *
     * @var string
     */
    public $emailHash;

    /**
     * Password of user.
     *
     * @var string
     */
    public $pass;

    /**
     * Photo of user if it exists.
     *
     * @var null|string
     */
    public $photo;


    /**
     * Creates new user.
     *
     * @param string $login
     * @param string $email
     * @param string $pass
     * @param string $fio
     * @param null|string $phone
     * @param null|string $photo
     */
    public function create(
        $login, $email, $pass, $fio, $phone, $photo
    ) {
        $this->login = $login;
        $this->email = $email;
        $this->fio = $fio;
        $this->phone = $phone;
        $this->photo = $photo;
        $this->pass = $this->hashPassword($pass);
        $this->encryptData($pass);
        $this->loginHash = StringEncryption::hashString($login);
        $this->emailHash = StringEncryption::hashString($email);
    }

    /**
     * Encrypts string data of user.
     *
     * @param string $key   Key for encryption of string.
     */
    public function encryptData($key)
    {
        foreach (['fio', 'login', 'phone', 'email'] as $param) {
            $this->{$param} = StringEncryption::encode($this->{$param}, $key);
        }
    }

    /**
     * Decrypts string data of user.
     *
     * @param string $key   Key for encryption of string.
     */
    public function decryptData($key)
    {
        foreach (['fio', 'login', 'phone', 'email'] as $param) {
            $this->{$param} = StringEncryption::decode($this->{$param}, $key);
        }
    }
    
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
