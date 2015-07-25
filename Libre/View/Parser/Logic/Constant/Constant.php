<?php
namespace Libre\View\Parser\Logic;

/**
 * Class métier à appliqué sur un Tag constant.
 *
 * Objet métier, sera le function de callback de preg_match_all cf la class Task.
 * Affiche la valeur d'une constant.
 * <code>
 * {THIS_IS_CONSTANTE}
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

class Constant extends Logic {

    /**
     * Setter match. Applique une class métier sur un pattern PCRE.
     * 
     * @param array $match Un tableau de retour de preg_match_all
     * @return string Le contenu fichier template modifié par une fonction pcre
     */
    public function process($match) {
        ob_start();
        if (defined($match[1])) {
            echo constant($match[1]);
        }
        else {
            // Error
            return null;
        }
        $this->buffer = ob_get_contents();
        ob_end_clean();
        return $this->buffer;
    }

}
