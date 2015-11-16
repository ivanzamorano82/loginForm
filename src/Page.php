<?php

namespace App;

use \App\Exception\Redirect;


/**
 * Describes a page of  application site.
 */
class Page
{
    use Inject\Current\User;


    /** Behaviour that page must not be rendered. */
    const AS_NOTHING = 0;
    /** Behaviour that page must be rendered as HTML. */
    const AS_HTML = 1;
    /** Behaviour that page must be rendered as JSON. */
    const AS_JSON = 2;

    /** Represents type of page without required authentication. */
    const AUTH_NO = 0;
    /*** Represents type of page with optional authentication. */
    const AUTH_YES = 1;
    /** Represents type of page with required authentication. */
    const AUTH_REQUIRED = 2;

    /**
     * Unique alias of page that identifies it.
     *
     * @var string
     */
    public $alias;

    /**
     * HTTP status response code that page must return.
     * By default 200 HTTP status response code is returned.
     *
     * @var int|null
     */
    public $status = 200;

    /**
     * Request parameters of page parsed from URI path.
     *
     * @var array|null
     */
    public $params;

    /**
     * Defines page rendering behaviour.
     *
     * @var int
     */
    public $render = self::AS_HTML;

    /**
     * Defines page authorization behaviour.
     *
     * @var int
     */
    public $auth = self::AUTH_REQUIRED;

    /**
     * Defines handler of page. If it is true than runs REST API methods,
     * otherwise runs corresponding controller.
     *
     * @var bool
     */
    public $api = false;

    /**
     * Parameters that are required for page rendering.
     *
     * @var array
     */
    public $toRender = [];

    /**
     * Concrete router instance which dispatches pages by its aliases.
     *
     * @var Router
     */
    public static $Router;


    /**
     * Creates new page of  application site with given parameters.
     *
     * @param string $alias  Alias of page.
     * @param array $props   Properties of page (field values) as array.
     */
    public function __construct($alias, $props = [])
    {
        $this->alias = $alias;
        foreach([
            'status', 'render', 'auth', 'api', 'params',
        ] as $field) {
            if (isset($props[$field])) {
                $this->{$field} = $props[$field];
            }
        }
    }

    /**
     * Creates new page of  application site dispatching it via router
     * by its alias.
     *
     * @param string $alias          Alias of page to create.
     *
     * @return Page     Dispatched by router site page.
     */
    public static function create($alias)
    {
        if (!isset(self::$Router)) {
            self::$Router = new Router();
        }
        list($alias, $props) = self::$Router->dispatch($alias);
        return new self($alias, $props);
    }

    /**
     * Processes page of  application site.
     *
     * @param Request|null $req  HTTP request to site page.
     *
     * @throws \Exception  If application is in debug mode then any uncaught
     *                     exception during page processing will be printed
     *                     to site user, otherwise 500 page will be used.
     *
     * @return Page  Result page of specified page processing with all required
     *               parameters to be rendered.
     */
    public function process($req = null)
    {
        try {
            if ($this->auth !== self::AUTH_NO) {
                $this->initCurrentUser();
                if ($this->auth === self::AUTH_REQUIRED) {
                    if ($this->CurrentUser === null) {
                        header('Location: '.Redirect::LOGIN_PAGE);
                        $this->render = self::AS_NOTHING;
                        return $this;
                    }
                }
            }

            $namespace = $this->api
                ? '\App\Api\\'.$req->method.'\\'
                : '\App\Controller\\';
            $controllerName = $namespace.
                              $this->makeClassName($this->alias, $this->api);
            if (class_exists($controllerName)) {
                /** @var Controller $controller */
                $controller = new $controllerName($this);
                try {
                    $result = $controller->run($req);
                } catch (Exception\Redirect $redirect) {
                    if ($this->render === self::AS_HTML) {
                        header('Location: '.$redirect->url);
                        $this->render = self::AS_NOTHING;
                    } elseif ($this->render === self::AS_JSON) {
                        $this->status = 301;
                        $this->toRender['redirectUrl'] = $redirect->url;
                    }
                    return $this;
                } catch (Exception\UsePage $use) {
                    return self::create($use->page)->process($req);
                }
                if (isset($result['toRender'])) {
                    $this->toRender = $result['toRender'];
                }
            }
        } catch (\Exception $e) {
            if (Conf::$isDebugMode) {
                throw $e;
            }
            return self::create('500')->process();
        }

        return $this;
    }

    private function makeClassName($alias, $api = false)
    {
        if (!$api) {
            $parts = explode('/', trim($alias, '/'));
            $newParts = [];
            foreach ($parts as $part) {
                $newParts[] = ucfirst($part);
            }
            return implode('\\', $newParts);
        } else {
            preg_match('/\.(\w+)$/', $alias, $matches);
            return ucfirst($matches[1]);
        }

    }
}
