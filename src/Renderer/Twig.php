<?php

namespace App\Renderer;

use \App\Conf;


/**
 * Implements view renderer of application on top of Twig templates engine.
 *
 * @implements \App\View
 */
class Twig  implements \App\View
{
    /**
     * Twig engine for rendering templates.
     *
     * @var \Twig_Environment
     */
    protected $Twig;


    /**
     * Creates new Twig view renderer.
     *
     * @param string $path      Path to directory which contains Twig templates.
     * @param bool $autoReload  Whether to recompile templates if their
     *                          original source was changed.
     */
    public function __construct($path, $autoReload = false)
    {
        $this->Twig = new \Twig_Environment(
            new \Twig_Loader_Filesystem($path),
            [
                'cache' => $path.'/_cache',
                'auto_reload' => $autoReload,
                'debug' => Conf::$isDebugMode,
            ]
        );
        if (Conf::$isDebugMode) {
            $this->Twig->addGlobal('session', $_SESSION);
            $this->Twig->addExtension(new \Twig_Extension_Debug());
        }
    }

    /**
     * Renders specified page and prints it immediately.
     * Sets HTTP status response code accordingly to code in passed page.
     *
     * @param \App\Page $page  Page to render.
     */
    public function renderPage($page)
    {
        //if (isset($page->status)) {
        //    http_response_code($page->status);
        //}
        //$template = 'pages/'.$page->alias.'.twig';
        //echo $this->Twig->loadTemplate($template)->render($page->toRender);
        $template = 'test.twig';
        echo $this->Twig->loadTemplate($template)->render(['xxx' => 'эУРА']);
    }

    /**
     * Flushes whole templates cache.
     */
    public function flushCache()
    {
        $this->Twig->clearCacheFiles();
    }
}
