<?php

namespace App\Inject;

use \App\Conf;
use \App\Renderer;


/**
 * Provides class field that contains instance of application view
 * and can retrieve it from IoC-container.
 */
trait View
{
    /**
     * Injected application view instance.
     *
     * @var \App\Renderer\Twig
     */
    public $View;


    /**
     * Retrieves application view instance from IoC-container
     * if it is not set yet.
     */
    public function initView()
    {
        if (!isset($this->View)) {
            $this->View = new Renderer\Twig(
                PROJECT_ROOT.'/templates', Conf::$isDebugMode
            );
        }
    }
}
