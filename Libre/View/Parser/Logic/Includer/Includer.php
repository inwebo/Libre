<?php
namespace Libre\View\Parser\Logic;

/**
 * Libre
 *
 * LICENCE
 *
 * You are free:
 * to Share ,to copy, distribute and transmit the work to Remix —
 * to adapt the work to make commercial use of the work
 *
 * Under the following conditions:
 * Attribution, You must attribute the work in the manner specified by
 * the author or licensor (but not in any way that suggests that they
 * endorse you or your use of the work).
 *
 * Share Alike, If you alter, transform, or build upon
 * this work, you may distribute the resulting work only under the
 * same or similar license to this one.
 *
 *
 * @category   Libre
 * @package    View
 * @subpackage Template
 * @subpackage Logic
 * @copyright Copyright (c) 2005-2012 Inwebo (http://www.inwebo.net)
 * @license   http://http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @version   $Id:$
 * @link      https://inwebo@github.com/inwebo/My.Sessions.git
 * @since     File available since Beta 01-01-2012
 */

/**
 * Class métier à appliqué sur un Tag Include.
 *
 * Permet d'inclure un fichier à la manière de PHP.
 * 
 * <code>
 * {includer="README.md"}
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

class Includer extends Logic {

    /**
     * Inclusion PHP d'un fichier.
     */
    public function process($match) {

        $buffer = "";
        if (!file_exists($match[1]) || !is_readable($match[1])) {
            // Erreur
            throw new \Exception("Unknow file");
            return null;
        }

        ob_start();
        include($match[1]);
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }

}
