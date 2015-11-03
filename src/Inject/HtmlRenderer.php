<?php

namespace App\Inject;

use \App\Conf;
use \App\Renderer;


/**
 * Provides class field that contains instance of application html renderer.
 */
trait HtmlRenderer
{
    /**
     * Injected application html renderer instance.
     *
     * @var \App\Renderer\Twig
     */
    public $HtmlRenderer;


    /**
     * Init application html renderer instance if it is not set yet.
     */
    public function initHtmlRenderer()
    {
        if (!isset($this->HtmlRenderer)) {
            $this->HtmlRenderer = new Renderer\Twig(
                PROJECT_ROOT.'/templates', Conf::$isDebugMode
            );
        }
    }
}
