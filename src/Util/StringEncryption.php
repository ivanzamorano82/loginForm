<?php

namespace App\Util;


/**
 * Describes reversible XOR text encryption with random gamma.
 */
class StringEncryption
{

    /**
     * Salt for encoding and decoding of string.
     *
     * @var string
     */
    protected static $salt = 'ababagalamaga';

    /**
     * Perform Bitwise Xor for each symbol.
     *
     * @param string $str   The string to encrypt or to decrypt.
     * @param string $key   Key for encrypting. and decrypting.
     *
     * @return string   Converted string.
     */
    private static function strCode($str, $key = "")
    {
        $len = strlen($str);
        $gamma = '';
        $n = $len > 100 ? 8 : 2;
        while(strlen($gamma) < $len)
        {
            $gamma .= substr(pack('H*', sha1($key.$gamma.self::$salt)), 0, $n);
        }
        return $str^$gamma;
    }

    /**
     * Encodes given string.
     *
     * @param string $txt   The string to encode.
     * @param string $key   Key for encoding.
     *
     * @return string   Encoded string.
     */
    public static function encode($txt, $key)
    {
        return base64_encode(self::strCode($txt, $key));
    }

    /**
     * Encodes given string.
     *
     * @param string $txt   The string to decode.
     * @param string $key   Key for decoding used when ecoding.
     *
     * @return string   Decoded string.
     */
    public static function decode($txt, $key)
    {
        return self::strCode(base64_decode($txt), $key);
    }

    /**
     * Calculates the md5 hash of string.
     *
     * @param string $str   String from wich must be calculated hash.
     *
     * @return string   Calculated hash.
     */
    public static function hashString($str)
    {
        return md5($str.self::$salt);
    }
}
