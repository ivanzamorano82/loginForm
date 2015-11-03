<?php

namespace App\Exception;


/**
 * Implements a throwable requirement to use specified page instead of current.
 */
class UsePage extends \Exception
{
    /**
     * Alias of page that must be used instead of current page.
     *
     * @var string
     */
    public $page;


    /**
     * Creates new throwable requirement of another page usage.
     *
     * @param string $page      Alias of page that must be used
     *                          instead of current page.
     */
    public function __construct($page = '')
    {
        $this->page = $page;
    }
}
