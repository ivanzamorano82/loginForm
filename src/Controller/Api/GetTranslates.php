<?php

namespace App\Controller\Api;

use \App\Inject;


/**
 * Implements a controller for API handler "getTranslates".
 *
 * @implements \App\Controller
 */
class GetTranslates implements \App\Controller
{
    use Inject\Repository\Translates;
    use Inject\Current\Lang;


    /**
     * Creates new controller of API handler "getTranslates"
     * and sets required dependency.
     */
    public function __construct()
    {
        $this->initTranslatesRepo();
        $this->initCurrentLang();
    }

    /**
     * Runs controller of API handler "getTranslates".
     *
     * @param \App\Request $req  HTTP request to handler.
     *
     * @return array   Parameters for page template rendering.
     */
    public function run($req)
    {
        return [
            'toRender' => $this->TranslatesRepo
                ->getAllTranslates($this->CurrentLang)
        ];
    }
}