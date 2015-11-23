<?php

namespace App\Api\GET;

use \App\Inject;


/**
 * Implements a controller for API handler "getAllTranslates".
 *
 * @implements \App\Controller
 */
class AllTranslates implements \App\Controller
{
    use Inject\Repository\Translates;
    use Inject\Current\Lang;


    /**
     * Creates new controller of API handler "getAllTranslates"
     * and sets required dependency.
     */
    public function __construct()
    {
        $this->initTranslatesRepo();
        $this->initCurrentLang();
    }

    /**
     * Runs controller of API handler "getAllTranslates".
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