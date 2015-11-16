<?php

namespace App\Inject\Repository;

use \App\Repository;


/**
 * Provides class field that contains instance of Translates repository.
 */
trait Translates
{
    /**
     * Injected Translates repository.
     *
     * @var \App\Repository\Translates
     */
    public $TranslatesRepo;


    /**
     * Init Translates repository instance.
     */
    public function initTranslatesRepo()
    {
        if (!isset($this->TranslatesRepo)) {
            $this->TranslatesRepo = new Repository\Translates();
        }
    }
}
