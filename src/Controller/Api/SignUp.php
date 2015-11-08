<?php

namespace App\Controller\Api;

use App\Inject;
use App\Model;
use App\Util\Validator;


/**
 * Implements a controller for API handler "signup".
 *
 * @implements \App\Controller
 */
class SignUp implements \App\Controller
{
    use Inject\Repository\Users;


    /**
     * Lists of validation rules to check form fields.
     *
     * @var array
     */
    public $rules = [
        'fio' => ['required', 'alphabet', 'length(10)'],
        'login' => [
            'required', 'alphaNumeric(en)', 'length(10)', 'availableLogin',
        ],
        'email' => ['required', 'email', 'length(10)', 'availableEmail'],
        'pass' => ['required', 'range(6,15)'],
        'repeat_pass' => ['required', 'matchWith(pass)'],
        'phone' => ['phone'],
        'photo' => ['fileSizeB64(1,image)']
    ];

    /**
     * Creates new controller of API handler "signUp"
     * and sets required dependency.
     */
    public function __construct()
    {
        $this->initUsersRepo();
    }

    /**
     * Runs controller of API handler "signUp".
     *
     * @param \App\Request $req  HTTP request to handler.
     *
     * @return array   Parameters for page template rendering.
     */
    public function run($req)
    {
        $validator = new Validator($this->rules, $req->POST);

        $validator->addCheckMethod('availableLogin', function ($login) {
            return !$this->UsersRepo->getUserIdByLogin($login);
        }, 'Такой логин уже существует');

        $validator->addCheckMethod('availableEmail', function ($email) {
            return !$this->UsersRepo->getUserIdByEmail($email);
        }, 'Такой email уже существует');

        if ($result = $validator->check()) {
            $user = new Model\User();
            $user->fio = $req->POST->String('fio');
            $user->login = $req->POST->String('login');
            $user->email = $req->POST->String('email');
            $user->phone = $req->POST->String('phone');
            $user->pass = $user->hashPassword($req->POST->String('pass'));

            $this->UsersRepo->addNewUser($user);
        }

        return ['toRender' => [
            'status' => $result ? 'success' : 'failure',
            'errors' => $validator->getErrors(),
            'post' => $req->POST->getAll(),
            'addedUserId' => isset($user) ? $user->id : null,
        ]];
    }
}
