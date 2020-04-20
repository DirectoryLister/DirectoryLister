<?php

namespace App\Support;

class Glob
{
    /**
     * Convert a glob pattern to a regular expression pattern.
     *
     * @param string $glob
     *
     * @return string
     */
    public static function toRegex(string $glob): string
    {
        $pattern = '';
        $characterGroup = 0;
        $patternGroup = 0;

        for ($i = 0; $i < strlen($glob); ++$i) {
            $char = $glob[$i];

            switch ($char) {
                case '\\':
                    $pattern .= '\\' . $glob[++$i];
                    break;

                case '?':
                    $pattern .= '.';
                    break;

                case '*':
                    if (isset($glob[$i + 1]) && $glob[$i + 1] === '*') {
                        $pattern .= '.*';
                        ++$i;
                    } else {
                        $pattern .= '[^/]*';
                    }
                    break;

                case '#':
                    $pattern .= '\#';
                    break;

                case '[':
                    $pattern .= $char;
                    ++$characterGroup;
                    break;

                case ']':
                    if ($characterGroup > 0) {
                        --$characterGroup;
                    }

                    $pattern .= $char;
                    break;

                case '^':
                    if ($characterGroup > 0) {
                        $pattern .= $char;
                    } else {
                        $pattern .= '\\' . $char;
                    }
                    break;

                case '{':
                    $pattern .= '(';
                    ++$patternGroup;
                    break;

                case '}':
                    if ($patternGroup > 0) {
                        $pattern .= ')';
                        --$patternGroup;
                    } else {
                        $pattern .= $char;
                    }
                    break;

                case ',':
                    if ($patternGroup > 0) {
                        $pattern .= '|';
                    } else {
                        $pattern .= $char;
                    }
                    break;

                default:
                    if (in_array($char, ['.', '(', ')', '|', '+', '$'])) {
                        $pattern .= '\\' . $char;
                    } else {
                        $pattern .= $char;
                    }

                    break;
            }
        }

        return sprintf('#^%s#', $pattern);
    }
}
