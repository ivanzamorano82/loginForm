<?php

namespace App\Exception;


/**
 * Implements a throwable requirement of site user redirection
 * to specified URL.
 */
class Redirect extends \Exception
{
    /**
     * Alias of authorization page.
     */
    const LOGIN_PAGE = '/index';

    /**
     * Alias of root page in control panel.
     */
    const ROOT_PAGE = '/profile';

    /**
     * URL where site user must be redirected to.
     *
     * @var string
     */
    public $url;


    /**
     * Creates new throwable site user redirection requirement.
     *
     * @param string $url  URL where site user must be redirected to.
     */
    public function __construct($url = '/')
    {
        $this->url = $url;
    }
}
