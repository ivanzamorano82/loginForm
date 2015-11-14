<?php

namespace App;


/**
 * Describes configuration parameters of application and provides its
 * default values.
 */
class Conf
{
    /**
     * Corresponds if application is running under debug mode or not.
     *
     * @var bool
     */
    public static $isDebugMode = true;

    /**
     * Contains credentials and settings of MySQL databases used by application.
     *
     * @var array
     */
    public static $MySQL = [
        'name' => [
            'lf' => 'login_form',
        ],
        'host' => 'localhost',
        'user' => 'root',
        'pass' => 'root',
    ];

    /**
     * Contains predefined email address for usage in application.
     *
     * @var string[]
     */
    public static $Email = [
        'debug' => 'ivanzamorano82@gmail.com',
    ];
}
