<?php

namespace App\Util;


/**
 * Implements helper functions for creating results of JSON scenarios.
 */
trait JsonResults
{
    /**
     * Creates new error result of JSON scenario.
     *
     * @param array $errors           List of errors.
     * @param array $additionalData   List of additional params.
     *
     * @return array  Result in required format.
     */
    public static function error($errors, $additionalData = null)
    {
        $out = ['toRender' => [
            'status' => 'failure',
            'errors' => $errors,
            'data' => $additionalData,
        ]];
        return $out;
    }

    /**
     * Creates new success result of JSON scenario.
     *
     * @param array $additionalData   List of additional params.
     *
     * @return array  Result in required format.
     */
    public static function success($additionalData = null)
    {
        return [
            'toRender' => [
                'status' => 'success',
                'data' => $additionalData,
            ],
        ];
    }
}
