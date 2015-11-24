<?php

namespace App\Api\PUT;

use \App\Exception\Redirect;
use App\Exception\UsePage;
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
     * @throws UsePage
     *
     * @return array   Parameters for page template rendering.
     */
    public function run($req)
    {
        $action = $req->POST->String('action');
        if ($action && method_exists(__CLASS__, $action)) {
            $this->$action($req);
        } else {
            throw new UsePage(404);
        }

        return $this->success();
    }

    /**
     * @param \App\Request $req  HTTP request to handler.
     */
    public function saveKey($req)
    {
        $this->TranslatesRepo->updateWord(
            $req->POST->Int('id'), $req->POST->String('key')
        );
    }

    /**
     * @param \App\Request $req  HTTP request to handler.
     */
    public function saveVal($req)
    {
        $this->TranslatesRepo->updateTranslate(
            $req->POST->Int('id'), $req->POST->Int('langId'),
            $req->POST->String('val')
        );
    }
}
