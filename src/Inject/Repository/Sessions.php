<?php

namespace App\Inject\Repository;

use App\Repository;


/**
 * Provides class field that contains instance of session repository
 * and can retrieve it from IoC-container.
 */
trait Sessions
{
    /**
     * Injected sessions repository.
     *
     * @var \App\Repository\Sessions
     */
    public $SessionsRepo;


    /**
     * Init Sessions repository instance.
     */
    public function initSessionsRepo()
    {
        if (!isset($this->SessionsRepo)) {
            $this->SessionsRepo = new Repository\Sessions();
        }
    }
}
