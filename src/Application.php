<?php

namespace App;

/**
 * Represents the application.
 */
class Application
{
    use Inject\HtmlRenderer;
    use Inject\JsonRenderer;

    /**
     * Runs application as an enter point for HTTP request.
     */
    public function run(){
        $request = new Request();
        //print_r($request);
        //print_r($_SERVER);
        $page = Page::create($request->page);
        $page = $page->process($request);
        //print_r($page);
        if ($page->render == Page::AS_HTML) {
            $this->initHtmlRenderer();
            $this->HtmlRenderer->renderPage($page);
        } elseif ($page->render == Page::AS_JSON) {
            $this->initJsonRenderer();
            $this->JsonRenderer->renderPage($page);
        }
    }
}
