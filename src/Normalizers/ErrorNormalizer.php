<?php

namespace Normalizers;

class ErrorNormalizer {

    /**
     * Normalizes an error.
     * 
     * @param \Throwable $error
     * @param ?string $function
     * 
     * @return array
     */
    public static function normalize(\Throwable $error, string $function = null) : array {

        $normalizedError = ['error' => null];

        $normalizedError['error']['code'] = $error->getCode();
        $normalizedError['error']['message'] = $error->getMessage();

        if ( !is_null($function) ) {
            $normalizedError['error']['function'] = $function;
        }

        if ( MPG_DEV_MODE === true ) {
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
