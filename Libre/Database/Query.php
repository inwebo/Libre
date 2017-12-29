<?php

namespace Libre\Database;

class Query
{

    /**
     * Formate une clef
     * <code><br>
     * $key = Query::toKey("key");<br>
     * echo $key;<br>
     * //Retourne<br>
     * // `key`<br>
     * </code>
     *
     * @param String $var la clef à formatée
     *
     * @return String Chaine formatée
     */
    public static function toKey($var)
    {
        return '`'.trim($var, '`').'`';
    }

    /**
     * Formate une valeur.
     *
     * @param String $value
     *
     * @return String valeur formatée
     */
    public static function toValue($value)
    {
        // @todo test si entier ou string
        return "'".$value."'";
    }

    /**
     * @param array $associativeArray
     *
     * @return string
     */
    public static function toUpdate($associativeArray)
    {
        $buffer = "";
        $i = 0;
        $loops = count((array)$associativeArray);
        foreach ($associativeArray as $key => $value) {
            ++$i;
            //$buffer .= ' ' . QueryString::toKey($key) . '=\'?\'';
            $buffer .= ' '.$key.'=? ';
            $buffer .= ($i !== $loops) ? ", " : '';
        }

        return $buffer;
    }

    /**
     * @param string $item
     */
    public static function toInsert(&$item)
    {
        $item = self::toKey($item);
    }
}
