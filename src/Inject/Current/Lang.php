<?php

namespace App\Inject\Current;


/**
 * Provides class field that contains code of current language.
 */
trait Lang
{
    /**
     * Injected current language as its code.
     *
     * @var string
     */
    public $CurrentLang;


    /**
     * Init current language instance.
     *
     * @todo   Implement to define of current language.
     */
    public function initCurrentLang()
    {
        if (!isset($this->CurrentLang)) {
            $this->CurrentLang = 'ru';
        }
    }
}
