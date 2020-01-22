<?php

namespace App\Support;

class Helpers
{
    /**
     * Return the value of an environment vairable.
     *
     * @param string $envar   The name of an environment variable
     * @param mixed  $default Default value to return if no environment variable is set
     *
     * @return mixed
     */
    public static function env($envar, $default = null)
    {
        $value = getenv($envar);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
                return true;
            case 'false':
                return false;
            case 'null':
                return null;
        }

        return preg_replace('/^"(.*)"$/', '$1', $value);
    }
}
