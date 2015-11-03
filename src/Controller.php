<?php

namespace App;


/**
 * Describes behaviour of application controller.
 *
 * Controller is an entity which contains business logic actions for site page
 * associated with this controller.
 */
interface Controller
{
    /**
     * Runs business logic actions of controller.
     *
     * @param Request $req          HTTP request to site page.
     *
     * @throws Exception\Redirect   Controller may throw requirement of redirect
     *                              to another site page or even external URL.
     * @throws Exception\UsePage    Controller may throw requirement of another
     *                              page creation and usage instead of current.
     *
     * @return array    Result of controller performed business logic actions.
     */
    public function run($req);
}
