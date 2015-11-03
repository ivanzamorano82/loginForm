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
        //var_dump($request);
        $page = Page::create($request->page);
        $page = $page->process($request);
        //var_dump($page);
        if ($page->render == Page::AS_HTML) {
            $this->initHtmlRenderer();
            $this->HtmlRenderer->renderPage($page);
        } elseif ($page->render == Page::AS_JSON) {
            $this->initJsonRenderer();
            $this->JsonRenderer->renderPage($page);
        }
    }
}
