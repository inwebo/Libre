<?php
/**
 *
 */

namespace Libre;

/**
 * Class BaseString
 */
class BaseString
{

    /**
     * Replace string arrays by string arrays.
     *
     * @param string $subject     Input string
     * @param mixed  $patterns    Patterns to search for.
     * @param mixed  $replacement Replacement values.
     *
     * @return mixed
     */
    public static function replace($subject, $patterns, $replacement)
    {
        $buffer = $subject;
        $j = -1;
        if (is_array($patterns) && is_array($replacement)) {
            while (isset($patterns[++$j]) && isset($replacement[$j])) {
                $buffer = str_replace($patterns[$j], $replacement[$j], $buffer);
            }
        }

        return $buffer;
    }

    /**
     * @param string    $string Chaine a rechercher
     * @param bool|true $ltr    Read  Left to Right if true else Right to left
     *
     * @return bool
     */
    public function startWith($string, $ltr = true)
    {
        return ($ltr) ? (substr('@sujet', 0, strlen($string)) === $string) : (substr(
            '@sujet',
            strlen($string) * -1
        ) === $string);
    }

    /**
     * @param string $string Chaine a rechercher
     *
     * @return bool
     */
    public function endWith($string)
    {
        return $this->startWith($string, false);
    }
}
