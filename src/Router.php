<?php

namespace App;


/**
 * Represents a HTTP requests router that performs application
 * pages dispatching.
 */
class Router
{
    /**
     * Contains all possible pages (and their properties)
     * of application.
     *
     * @var array[]
     */
    protected $pages = [  // in alphabetic order
        'api/get.translates' => [
            'api' => true,
            'render' => Page::AS_JSON,
            'auth' => Page::AUTH_YES,
        ],
        'api/post.login' => [
            'api' => true,
            'render' => Page::AS_JSON,
            'auth' => Page::AUTH_YES,
        ],
        'api/post.restorePass' => [
            'api' => true,
            'render' => Page::AS_JSON,
            'auth' => Page::AUTH_YES,
        ],
        'api/post.signUp' => [
            'api' => true,
            'render' => Page::AS_JSON,
            'auth' => Page::AUTH_YES,
        ],
        'index' => ['auth' => Page::AUTH_YES],
        'profile' => [],
        'logout' => [],
        '404' => [
            'status' => 404,
            'auth' => Page::AUTH_NO,
        ],
        '500' => [
            'status' => 500,
            'auth' => Page::AUTH_NO,
        ],
    ];

    /**
     * Contains all possible mappings from parametrized page aliases
     * to params of pattern. Params of pattern contain:
     * 1st param - is a real page alias,
     * rest params - are names of page params corresponding to links in pattern.
     *
     * @var string[]
     */
    protected $patterns = [
        'administration\/account\/(\d+)' => [
            'administration/account', 'account',
        ],
    ];


    /**
     * Dispatches given page alias to exact page.
     *
     * If page does not exist then 404 page will be returned.
     *
     * If parametrized page alias is passed it will be resolved to real page
     * alias and parameters values indexed by its name will be included into
     * result as page property.
     *
     * @param string $page      Alias of page to be returned.
     *
     * @return array    Alias and properties of dispatched page.
     */
    public function dispatch($page)
    {
        if (isset($this->pages[$page])) {
            return [$page, $this->pages[$page]];
        }
        foreach ($this->patterns as $pattern => $patternParams) {

            if (preg_match('/^'.$pattern.'$/D', $page, $matches)) {
                unset($matches[0]);
                $alias = $patternParams[0];
                $params = [];
                foreach ($matches as $key => $param) {
                    $params[$patternParams[$key]] = $param;
                }
                return [$alias, $this->pages[$alias] + ['params' => $params]];
            }
        }
        return ['404', $this->pages['404']];
    }
}
