<?php

namespace App\Api\POST;

use App\Inject;
use App\Model;
use App\Util\StringEncryption;
use App\Util\Validator;


/**
 * Implements a controller for API handler "login".
 *
 * @implements \App\Controller
 */
class Login implements \App\Controller
{
    use Inject\Repository\Users;


    /**
     * Lists of validation rules to check form fields.
     *
     * @var array
     */
    public $rules = [
        'login' => ['required', 'existingLogin'],
        'pass' => ['required'],
    ];

    /**
     * Creates new controller of API handler "login"
     * and sets required dependency.
     */
    public function __construct()
    {
        $this->initUsersRepo();
    }

    /**
     * Runs controller of API handler "login".
     *
     * @param \App\Request $req  HTTP request to handler.
     *
     * @return array   Parameters for page template rendering.
     */
    public function run($req)
    {
        $validator = new Validator($this->rules, $req->POST);

        $validator->addCheckMethod('existingLogin', function ($login) {
            return $this->UsersRepo->getUserIdByLoginHash(
                StringEncryption::hashString($login)
            );
        });

        if ($result = $validator->check()) {

        }

        return ['toRender' => [
            'status' => $result ? 'success' : 'failure',
            'errors' => $validator->getErrors(),
            'post' => $req->POST->getAll(),
            'addedUserId' => isset($user) ? $user->id : null,
        ]];
    }
}
