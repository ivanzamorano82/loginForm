<?php

namespace App\Controller;

use \App\Inject;


/**
 * Implements a controller for "index" page which render form for
 * authentication.
 *
 * @implements \App\Controller
 */
class Index implements \App\Controller
{
    use Inject\Repository\Translates;
    use Inject\Current\Lang;


    /**
     * Creates new controller of "index" page and sets required dependency.
     */
    public function __construct()
    {
        $this->initTranslatesRepo();
        $this->initCurrentLang();
    }

    /**
     * Runs controller of "index".
     *
     * @param \App\Request $req  HTTP request to "index" page.
     *
     * @todo   Allocate required translations for current page.
     *
     * @return array   Parameters for page template rendering.
     */
    public function run($req)
    {
        return ['toRender' => [
            'tr' => $this->TranslatesRepo->getAllTranslates($this->CurrentLang),
        ]];
    }
}
