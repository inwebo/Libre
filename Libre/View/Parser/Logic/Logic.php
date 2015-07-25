<?php
namespace Libre\View\Parser;

/**
 * Class métier à appliqué sur un objet Tag.
 *
 * Objet métier, sera le function de callback de preg_match_all voir la class
 * Task
 *
 * @category   Libre
 * @package    View
 * @subpackage Template
 * @copyright  Copyright (c) 1793-2222 Inwebo Veritas (http://www.inwebo.net)
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id:$
 * @link       https://github.com/inwebo/Template
 * @since      File available since Beta
 * @abstract
 */

use Libre\View\Interfaces\IProcess;

abstract class Logic implements IProcess {

    /**
     * Singleton d'un objet ViewBag
     * @static
     * @vars string
     */
    protected $dataProvider;

    /**
     * Setter viewobject.
     * @todo Injection de dépendance.
     */
    public function __construct( $dataProvider ) {
        $this->dataProvider = $dataProvider;
    }

    /**
     * Signature du callback de preg_match_all.
     */
    public function process($match) {}

    public function get() {
        return $this;
    }
}
