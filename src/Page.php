<?php

namespace App;


/**
 * Describes a page of  application site.
 */
class Page
{
    /**
     * Represents type of page without required authentication.
     */
    const AUTH_NO = 0;

    /**
     * Represents type of page with optional authentication.
     */
    const AUTH_YES = 1;

    /**
     * Represents type of page with required authentication.
     */
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
     * Corresponds if page is hidden from direct access by site users
     * and only can be used in application internals.
     *
     * @var bool
     */
    public $isHidden = false;

    /**
     * Request parameters of page parsed from URI path.
     *
     * @var array|null
     */
    public $params;

    /**
     * Corresponds if page does not have its view and must not be rendered.
     *
     * @var bool
     */
    public $noRendering = false;

    /**
     * Corresponds type of required authentication. Apped types:
     * AUTH_NO, AUTH_YES, AUTH_REQUIRED;
     *
     * @var int
     */
    public $auth = self::AUTH_REQUIRED;

    /**
     * Corresponds if page is available only for admin.
     *
     * @var bool
     */
    public $forAdmin = false;

    /**
     * Corresponds if page return JSON format.
     *
     * @var bool
     */
    public $json = false;
    
    /**
     * Parameters that are required for page rendering.
     *
     * @var array
     */
    public $toRender = [];

    /**
     * Result of page performing (only for page returning JSON format).
     *
     * @var null|\App\Result\Json
     */
    public $result;

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
            'status', 'isHidden', 'noRendering', 'auth',
            'forAdmin', 'json', 'params',
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
     * @param array $optionalProps   Optional properties for page.
     *
     * @return Page     Dispatched by router site page.
     */
    public static function create($alias, $optionalProps = [])
    {
        if (!isset(self::$Router)) {
            self::$Router = new Router();
        }
        list($alias, $props) = self::$Router->dispatch($alias);
        $page = new self($alias, array_merge($props, $optionalProps));
        if ($page->json) {
            $page->result = new Result\Json($page->status);
        }
        return $page;
    }

    /**
     * Processes page of  application site.
     *
     * @param Request|null $req  HTTP request to site page.
     * @param bool $atFirstTime  Corresponds if page processing is called at
     *                           first time or not.
     *
     * @throws \Exception  If application is in debug mode then any uncaught
     *                     exception during page processing will be printed
     *                     to site user, otherwise 500 page will be used.
     *
     * @return Page  Result page of specified page processing with all required
     *               parameters to be rendered.
     */
    public function process($req = null, $atFirstTime = false)
    {
        try {
            if ($atFirstTime && $this->isHidden) {
                return self::create('404')->process($req);
            }

            if (empty($this->noRendering) && empty($this->json)) {
                $this->toRender['src'] = [
                    'ver' => Conf::getVersion(),
                    'suf' => Conf::$isDebugMode ? 'd' : '',
                ];
            }

            if ($this->auth !== self::AUTH_NO) {
                $this->initAuthorizedAccount();
                if ($this->auth === self::AUTH_REQUIRED) {
                    if ($this->AuthorizedAccount === null
                        || ($this->forAdmin && $this->AuthorizedAccount->type
                                               === Account::TYPE_EMPLOYEE)
                    ) {
                        return self::create('404')->process($req);
                    }
                    if ($this->AuthorizedAccount->type
                        === Account::TYPE_ADMIN
                    ) {
                        $this->toRender['isAdmin'] = true;
                    }
                }
            }

            $controllerName =
                '\App\Controller\\'.self::makeClassName($this->alias);
            if (class_exists($controllerName)) {
                /** @var Controller $controller */
                $controller = new $controllerName($this);
                try {
                    $result = $controller->run($req);
                } catch (Exception\Redirect $redirect) {
                    if (!$this->json) {
                        header('Location: '.$redirect->url);
                        $this->noRendering = true;
                    } else {
                        $this->result->setStatus(301);
                        $this->result->redirectUrl = $redirect->url;
                    }
                    return $this;
                } catch (Exception\UsePage $use) {
                    $optProps = $this->json ? ['json' => true] : [];
                    return self::create($use->page, $optProps)->process($req);
                }
                if (isset($result['toRender'])) {
                    $data  = array_merge(
                        $this->toRender, $result['toRender']
                    );
                    if (!$this->json) {
                        $this->toRender = $data;
                    } else {
                        $this->result->data = $data;
                    }
                }
            }
        } catch (\Exception $e) {
            //$this->initErrorLogger();
            //$this->ErrorLogger->err($e);
            if (Conf::$isDebugMode) {
                throw $e;
            }
            return self::create('500')->process();
        }

        return $this;
    }
}
