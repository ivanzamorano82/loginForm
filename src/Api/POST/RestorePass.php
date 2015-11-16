<?php

namespace App\Api\POST;

use \App\Conf;
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
class RestorePass implements \App\Controller
{
    use Inject\Repository\Users;
    use Util\JsonResults;


    /**
     * Lists of validation rules to check form fields.
     *
     * @var array
     */
    public $rules = [
        'email' => ['required', 'email', 'existingEmail'],
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

        $validator->addCheckMethod('existingEmail', function ($email) {
            return empty($email) || $this->UsersRepo->getUserIdByEmailHash(
                StringEncryption::hashString($email)
            );
        });

        if ($validator->check()) {
            $newPass = Model\User::generatePassword();
            $user = $this->UsersRepo->getUserByEmailHash(
                StringEncryption::hashString($req->POST->String('email'))
            );
            $user->pass = Model\User::hashPassword($newPass);
            $this->UsersRepo->saveUser($user);
            $this->sendEmail($req->POST->String('email'), $newPass);
            return $this->success([
                'pass' => $newPass,
                'notification' => "New password is '$newPass'",
            ]);
        }

        return $this->error(
            $validator->getErrors(), ['post' => $req->POST->getAll()]
        );
    }

    /**
     * Sends new generated password to the given email.
     *
     * @param string $recipient   Recipient of email.
     * @param string $message     Message of email with new generated password.
     */
    public function sendEmail($recipient, $message){
//        $mail = new \PHPMailer(true);
//        $mail->CharSet = 'UTF-8';
//        $mail->setFrom('info@lf2.wi', 'lf2');
//        if (Conf::$isDebugMode) {
//            $mail->addAddress(Conf::$Email['debug']);
//        } else {
//            $mail->addAddress($recipient);
//        }
//        $mail->Subject = 'subject';
//        $mail->Body = $message;
//
//        $mail->isMail();
//        $mail->setFrom('from@lf2.wi', 'First Last');
//        $mail->addReplyTo('replyto@lf2', 'First Last');
//
//        $mail->send();
    }
}
