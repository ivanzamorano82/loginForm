<?php

namespace App\Api\GET;

use \App\Inject;


/**
 * Implements a controller for API handler "getLanguages".
 *
 * @implements \App\Controller
 */
class Languages implements \App\Controller
{
    use Inject\Repository\Translates;
    use Inject\Current\Lang;


    /**
     * Creates new controller of API handler "getLanguages"
     * and sets required dependency.
     */
    public function __construct()
    {
        $this->initTranslatesRepo();
        $this->initCurrentLang();
    }

    /**
     * Runs controller of API handler "getLanguages".
     *
     * @param \App\Request $req  HTTP request to handler.
     *
     * @return array   Parameters for page template rendering.
     */
    public function run($req)
    {
        return [
            'toRender' => $this->TranslatesRepo->getLangs(),
        ];
    }
}