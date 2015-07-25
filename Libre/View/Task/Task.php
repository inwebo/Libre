<?php
namespace Libre\View;

use Libre\View\Interfaces\IProcess;
use Libre\View\Parser\Tag;

/**
 * Une tâche est la combinaison d'un tag PCRE et d'une logique à lui appliquer.
 * 
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
Class Task {

    /**
     * Objet tag
     * @vars Tag
     */
    protected $tag;

    /**
     * Objet logic
     * @vars Logic
     */
    protected $logic;

    /**
     * Applique une classe métier LogicComparison au Tag if
     * 
     * Retourne le resultat de la comparaison de deux variables selon un opérateur.
     *
     * @param Tag $tag
     * @param IProcess $logic
     */
    public function __construct(Tag $tag, IProcess $logic) {
        $this->tag     = $tag;
        $this->logic   = $logic;
    }

    public function getTag() {
        return $this->tag;
    }

    public function getLogic() {
        return $this->logic;
    }

}
