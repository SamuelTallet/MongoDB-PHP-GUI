<?php

namespace Helpers;

class ErrorNormalizer {

    /**
     * Normalizes an error.
     * 
     * @param \Throwable $error
     * @param ?string $function
     * 
     * @return array
     */
    public static function normalize(\Throwable $error, $function = null) : array {

        $normalizedError = ['error' => null];

        $normalizedError['error']['code'] = $error->getCode();
        $normalizedError['error']['message'] = $error->getMessage();

        if ( MPG_DEV_MODE === true ) {

            if ( !is_null($function) ) {
                $normalizedError['error']['function'] = $function;
            }

            $normalizedError['error']['trace'] = $error->getTrace();

        }

        return $normalizedError;

    }

    /**
     * Normalizes then prints an error prettily.
     * 
     * @param \Throwable $error
     */
    public static function prettyPrint(\Throwable $error) {

        echo '<pre>' . print_r(self::normalize($error), true) . '</pre>';

    }

}
