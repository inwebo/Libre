<?php
namespace Libre\View\Parser\Logic;


/**
 * Class métier à appliqué sur un Tag if.
 * 
 * <code>
 * {if "{$isTrue}==={$isTrue}"}
 * echo 'vrai';
 * {else}
 * echo 'faux';
 * {fi}
 * </code>
 *
 * @category   Libre
 * @package    View
 * @subpackage Template
 * @copyright  Copyright (c) 1793-2222 Inwebo Veritas (http://www.inwebo.net)
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id:$
 * @link       https://github.com/inwebo/Template
 * @since      File available since Beta
 */

use Libre\View\Parser\Logic;

class Conditions extends Logic {

    /**
     * Applique une classe métier LogicComparison au Tag if
     * 
     * @param array $match Un tableau de retour de preg_match_all
     * @return string Le contenu fichier template modifié par une fonction pcre
     */
    public function process($match) {
        $buffer = "";
        $comparison = new LogicComparison( $this->dataProvider );
        $assert = $comparison->process($match[1]);
        ( $assert ) ? $buffer .= $match[2] : $buffer .= $match[3];
        return $buffer;
    }

}