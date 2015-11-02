<?php

namespace App;

/**
 * Represents the application.
 */
class Application
{
    use Inject\View;
    /**
     * Runs application as an enter point for HTTP request.
     */
    public function run(){
        $request = new Request();
        var_dump($request);
        $page = Page::create($request->page);
        //var_dump($_REQUEST);
        $this->initView();
        $this->View->renderPage(null);
    }
}
