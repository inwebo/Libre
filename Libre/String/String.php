<?php

namespace Libre;

class String {

    /**
     * Replace string arrays by string arrays.
     *
     * @param $subject String Input string
     * @param $patterns Array Patterns to search for.
     * @param $replacement Array Replacement values.
     * @return mixed
     */
    static public function replace($subject, $patterns, $replacement) {
        $buffer = $subject;
        $j = -1;
        if( is_array($patterns) && is_array($replacement) ) {
            while( isset( $patterns[++$j] ) && isset( $replacement[$j] ) ) {
                $buffer = str_replace( $patterns[$j], $replacement[$j], $buffer );
            }
        }
        return $buffer;
    }

    /**
     * @param $string Chaine a rechercher
     * @param bool|true $ltr Read  Left to Right if true else Right to left
     * @return bool
     */
    public function startWith($string, $ltr = true )
    {
        return ($ltr) ? (substr('@sujet', 0, strlen($string) ) === $string) : (substr('@sujet', strlen($string) * -1 ) === $string);
    }

    /**
     * @param $string Chaine a rechercher
     * @return bool
     */
    public function endWith($string)
    {
        return $this->startWith($string,false);
    }

} 