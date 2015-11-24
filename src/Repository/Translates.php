<?php

namespace App\Repository;

use \App\Inject;
use \App\Storage\MySQL as DB;


/**
 * Implements translates repository on top of MySQL.
 *
 * @todo Can do different implementation.
 * @todo For example get translates from another storage (cache).
 *
 * @package App\Repository
 */
class Translates
{
    use Inject\Storage\MySQL;


    /**
     * Creates new translates repository injecting its dependencies.
     */
    public function __construct()
    {
        $this->initMySQL();
    }

    /**
     * Get all languages.
     *
     * @return \PDOStatement
     */
    public function getLangs()
    {
        $sql = "SELECT * FROM `".DB::TBL_LANGS."`";
        $st = $this->MySQL->getConn()->prepare($sql);
        $st->execute();
        return $st->fetchAll();
    }

    /**
     * Gets all translates for all languages.
     *
     * @return array   All translates in required language.
     */
    public function getAllTranslates()
    {
        $languages = $this->getLangs();
        $out = [];
        foreach ($languages as $lang) {
            foreach ($this->getTranslatesByLang($lang['code'])
                as $key => $val
            ) {
                $out[] = [
                    'langCode' => $lang['code'],
                    'langId' => $lang['id'],
                    'key' => $key,
                    'val' => $val,
                ];
            }
        }
        return $out;
    }

    /**
     * Gets all translates of given language.
     *
     * @param int $lang   Code of required language.
     *
     * @return array   All translates in required language.
     */
    public function getTranslatesByLang($lang)
    {
        $sql = "SELECT `t1`.`id`,`t1`.`key`,t2.`value` FROM words t1 ".
               "LEFT JOIN ".
                    "(SELECT w.id, w.`key`,t.`value` ".
                    "FROM words w  ".
                    "INNER JOIN translates t ON w.id = t.word_id ".
                    "INNER JOIN langs l ON t.lang_id = l.id ".
                                      "AND l.`code`=? ".
                    "ORDER BY w.`key`) as t2 ".
               "ON t1.id = t2.id";
        $st = $this->MySQL->getConn()->prepare($sql);
        $st->execute([$lang]);
        $out = [];
        while ($r = $st->fetch()) {
            $out[$r['key']] = $r['value'];
        }
        return $out;
    }

    /**
     * Get translate of word in given language by its code.
     *
     * @param string $code   Code of required word.
     * @param string $lang   Language of required translate.
     *
     * @return string   Translate of word.
     */
    public function getTranslateByCode($code, $lang)
    {
        $sql = "SELECT `t`.`value` ".
               "FROM ".DB::TBL_WORDS." `w` ".
               "INNER JOIN ".DB::TBL_TRANSLATES." `t` ".
                     "ON `w`.`id`=`t`.`word_id` AND `w`.`key`=?".
               "INNER JOIN ".DB::TBL_LANGS." `l` ".
                     "ON `t`.`lang_id`=`l`.`id` AND `l`.`code`=? ".
               "LIMIT 1";
        $st = $this->MySQL->getConn()->prepare($sql);
        $st->execute([$code, $lang]);
        $r = $st->fetchColumn();
        return !empty($r) ? $r : '';
    }

    /**
     * Get translates by prefix of word from repository.
     *
     * @param string $lang          Language of required translate.
     * @param string $prefix        Prefix for key of word.
     * @param bool $excludePrefix   Exclude prefix from result's key list
     *                              or not.
     *
    * @return array   List of required translates.
     */
    public function getTranslatesByPrefix($lang, $prefix, $excludePrefix = true)
    {
        $sql = "SELECT `w`.`key`,`t`.`value` ".
               "FROM `".DB::TBL_WORDS."` `w` ".
               "INNER JOIN `".DB::TBL_TRANSLATES."` `t` ".
                     "ON `w`.`id`=`t`.`word_id` ".
               "INNER JOIN `".DB::TBL_LANGS."` `l` ".
                     "ON `t`.`lang_id`=`l`.`id` AND `l`.`code`=? ".
               "WHERE w.key like CONCAT(?,'%')";
        $st = $this->MySQL->getConn()->prepare($sql);
        $st->execute([$lang, $prefix]);
        $out = [];
        while ($r = $st->fetch()) {
            $key  = $excludePrefix
                ? str_replace($prefix, '', $r['key'])
                : $r['key'];
            $out[$key] = $r['value'];

        }
        return $out;
    }

    public function updateWordTranslate($oldKey, $newKey){
        $sql = "UPDATE `".DB::TBL_WORDS."` ".
               "SET `key`=? ".
               "WHERE `key`=?";
        $st = $this->MySQL->getConn()->prepare($sql);
        $st->execute([$newKey, $oldKey]);
    }
}
