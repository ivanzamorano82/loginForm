<?php

namespace App\Api\POST;

use \App\Exception\Redirect;
use \App\Inject;
use \App\Model;
use \App\Util;
use \App\Util\StringEncryption;
use \App\Util\Validator;


/**
 * Implements a controller for API handler "login".
 *
 * @implements \App\Controller
 */
class Login implements \App\Controller
{
    use Inject\Repository\Users;
    use Inject\Repository\Sessions;
    use Util\JsonResults;


    /**
     * Lists of validation rules to check form fields.
     *
     * @var array
     */
    public $rules = [
        'login' => ['required', 'existingLogin'],
        'pass' => ['required', 'correctPassword'],
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
     * @throws Redirect   Redirect to profile page if authentication
     *                    was successful.
     *
     * @return array   Parameters for page template rendering.
     */
    public function run($req)
    {
        $validator = new Validator($this->rules, $req->POST);

        $user = $this->UsersRepo->getUserByLoginHash(
            StringEncryption::hashString($req->POST->String('login'))
        );

        $validator->addCheckMethod('existingLogin', function ($login) {
            return empty($login) || $this->UsersRepo->getUserIdByLoginHash(
                StringEncryption::hashString($login)
            );
        });

        $validator->addCheckMethod('correctPassword',
            function ($pass) use ($user) {
                return empty($pass) || password_verify($pass, $user->pass);
            }
        );

        if ($validator->check()) {
            $this->initSessionsRepo();
            $this->SessionsRepo->setAuthorization(
                $user->id, $req->POST->String('login')
            );
            throw new Redirect(Redirect::PROFILE_PAGE);
        }

        return $this->error(
            $validator->getErrors(), ['post' => $req->POST->getAll()]
        );
    }
}
