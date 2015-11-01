<?php

namespace App;

/**
 * Represents the application.
 */
class Application
{
    /**
     * Runs application as an enter point for HTTP request.
     */
    public function run(){
        $request = new Request();
        var_dump($request);
    }
}
