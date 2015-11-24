<?php

namespace App\Api\PUT;

use \App\Exception\Redirect;
use \App\Inject;
use \App\Model;
use \App\Util;
use \App\Util\Validator;


/**
 * Implements a controller for API handler "SetTranslate".
 *
 * @implements \App\Controller
 */
class SetTranslate implements \App\Controller
{
    use Inject\Repository\Translates;
    use Util\JsonResults;


    /**
     * Creates new controller of API handler "SetTranslate"
     * and sets required dependency.
     */
    public function __construct()
    {
        //$this->initUsersRepo();
        $this->initTranslatesRepo();
    }

    /**
     * Runs controller of API handler "SetTranslate".
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
        $this->TranslatesRepo->updateWordTranslate(
            $req->POST->String('oldKey'), $req->POST->String('key')
        );
        return $this->success($req->POST->getAll());
    }
}
