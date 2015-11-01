<?php

namespace App;

use \App\Util\Params;


/**
 * Describes HTTP request to application.
 */
class Request
{
    /**
     * Host that HTTP request was made to.
     *
     * @var string
     */
    public $host;

    /**
     * Alias of page that was requested.
     *
     * @var string
     */
    public $page;

    /**
     * IP address where request was made from.
     *
     * @var string
     */
    public $IP;

    /**
     * Contains GET parameters of HTTP request.
     *
     * @var \App\Util\Params
     */
    public $GET;

    /**
     * Contains POST parameters of HTTP request.
     *
     * @var \App\Util\Params
     */
    public $POST;

    /**
     * Contains FILES parameters of HTTP request.
     *
     * @var \App\Util\Params
     */
    public $FILES;


    /**
     * Creates new application HTTP request.
     */
    public function __construct() {
        $this->host = $_SERVER['HTTP_HOST'];
        $this->page = self::parseUriPath($_SERVER['REQUEST_URI']);
        $this->GET = new Params($_GET);
        $this->POST = new Params($_POST);
        $this->FILES = new Params($_FILES);
        $this->IP = $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Performs parsing of URI path from full URI string.
     *
     * @param string $uri   Full URI string of HTTP request.
     *
     * @return string   URI path of HTTP request.
     */
    public static function parseUriPath($uri)
    {
        $uriPath = trim(reset(explode('?', $uri)), '/');
        return ($uriPath === '') ? 'index' : $uriPath;
    }
}
