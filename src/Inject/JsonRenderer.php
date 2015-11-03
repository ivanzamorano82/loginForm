<?php

namespace App\Inject;

use \App\Renderer;


/**
 * Provides class field that contains instance of application json renderer.
 */
trait JsonRenderer
{
    /**
     * Injected application json renderer instance.
     *
     * @var \App\Renderer\Json
     */
    public $JsonRenderer;


    /**
     * Init application json renderer instance if it is not set yet.
     */
    public function initJsonRenderer()
    {
        if (!isset($this->JsonRenderer)) {
            $this->JsonRenderer = new Renderer\Json();
        }
    }
}
