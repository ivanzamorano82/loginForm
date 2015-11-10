<?php

namespace App\Api\POST;

use App\Inject;
use App\Model;
use App\Util\StringEncryption;
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
        'fio' => ['required', 'alphabet', 'length(30)'],
        'login' => [
            'required', 'alphaNumeric(en)', 'length(20)', 'availableLogin',
        ],
        'email' => ['required', 'email', 'length(100)', 'availableEmail'],
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
            return !$this->UsersRepo->getUserIdByLoginHash(
                StringEncryption::hashString($login)
            );
        });

        $validator->addCheckMethod('availableEmail', function ($email) {
            return !$this->UsersRepo->getUserIdByEmailHash(
                StringEncryption::hashString($email)
            );
        });

        if ($result = $validator->check()) {
            $user = new Model\User();

            $files = $validator->getDownloadFiles();

            $user->create(
                $req->POST->String('login'),
                $req->POST->String('email'),
                $req->POST->String('pass'),
                $req->POST->String('fio'),
                $req->POST->String('phone'),
                isset($files[0]['file_name']) ? $files[0]['file_name'] : null
            );

            $this->UsersRepo->addNewUser($user);
            if (!empty($files)) {
                $this->downloadFiles($files);
            }
        }

        return ['toRender' => [
            'status' => $result ? 'success' : 'failure',
            'errors' => $validator->getErrors(),
            'post' => $req->POST->getAll(),
            'addedUserId' => isset($user) ? $user->id : null,
        ]];
    }

    /**
     * Downloads files prepared for downloading in base64 format and saves
     * into storage place.
     *
     * @param array $files   List of required files.
     *
     * @todo Make image resizing to required sizes.
     */
    private function downloadFiles($files){
        foreach ($files as $file) {
            if (isset($file)) {
                file_put_contents(
                    USERS_FOLDER.$file['file_name'], $file['data']
                );
            }
        }
    }
}
