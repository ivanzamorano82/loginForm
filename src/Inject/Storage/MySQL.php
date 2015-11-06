<?php

namespace App\Inject\Storage;

use App\Conf;
use App\Storage;


/**
 * Provides class field that contains instance of MySQL storage.
 */
trait MySQL
{
    /**
     * Injected MySQL storage.
     *
     * @var \App\Storage\MySQL
     */
    public $MySQL;


    /**
     * Init MySQL instance.
     */
    public function initMySQL()
    {
        if (!isset($this->MySQL)) {
            $this->MySQL = new Storage\MySQL(Conf::$MySQL);
        }
    }
}
