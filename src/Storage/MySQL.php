<?php

namespace App\Storage;

use \App\ExternalResources;


/**
 * Provides tools to work with MySQL databases of application.
 */
class MySQL implements ExternalResources
{
    // Application Tables
    /** Contains available languages in system */
    const TBL_LANGS = 'langs';
    /** Contains available options of system */
    const TBL_OPTIONS = 'options';
    /** Contains words of system */
    const TBL_WORDS = 'words';
    /* Contains translates of words*/
    const TBL_TRANSLATES = 'translates';
    /** Contains registered users */
    const TBL_USERS = 'users';


    /**
     * Contains connections to databases. Evaluates lazy.
     *
     * @var \PDO[]
     */
    protected $conn;

    /**
     * Access credentials to databases.
     *
     * @var array
     */
    protected $crdnt;


    /**
     * Creates new storage of MySQL databases.
     *
     * @param array $crdnt      Access credentials to databases.
     */
    public function __construct($crdnt)
    {
        $this->crdnt = $crdnt;
    }

    /**
     * Returns connection to specified database.
     * Reuses early created connections.
     *
     * @param string $alias    Alias of database to connect to.
     * @param bool $reconnect  If true, new connection will be created
     *                         instead of reusing old connection.
     *
     * @throws \PDOException  In case of connection failure.
     *
     * @return \PDO      Connection object.
     */
    public function getConn($alias = 'lf', $reconnect = false)
    {
        if (!isset($this->conn[$alias]) || $reconnect) {
            $crdnt = [
                'mysql:host='.$this->crdnt['host'].';'.
                'dbname='.$this->crdnt['name'][$alias].';charset=utf8',
                $this->crdnt['user'], $this->crdnt['pass'],
            ];
            $this->conn[$alias] = new \PDO($crdnt[0], $crdnt[1], $crdnt[2], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
            ]);
        }
        return $this->conn[$alias];
    }

    /**
     * Closes all connections to databases that were established before.
     */
    public function closeAll()
    {
        $this->conn = null;
    }

    /**
     * Closes connections to specified database if it was established earlier.
     *
     * @param string $alias   Alias of database close connection to.
     */
    public function close($alias = 'lf')
    {
        unset($this->conn[$alias]);
    }

    /**
     * Destructor to close all connections to databases.
     */
    public function __destruct()
    {
        $this->closeAll();
    }
}
