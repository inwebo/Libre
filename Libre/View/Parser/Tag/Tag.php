<?php
namespace Libre\View\Parser;
/**
 * Représentation d'un tag à parser.
 *
 * Objet simple avec un seul attribut pattern qui est un pattern PCRE.
 *
 * @category   Libre
 * @package    View
 * @subpackage Template
 * @subpackage Tag
 * @copyright  Copyright (c) 1793-2222 Inwebo Veritas (http://www.inwebo.net)
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id:$
 * @link       https://github.com/inwebo/Template
 * @since      File available since Beta
 */
class Tag {

    /**
     * Echappement du parser
     */
    const ESCAPEMENT = '#\{noparse\}(.*)\{\/noparse\}#ismU';

    /**
     * Constante est en majuscule {CONSTANTE}.
     */
    const CONSTS    = '#\{([A-Z_]*)\}#';
    /**
     * Un membre du ViewObject courant
     */
    //const VARS      = '#\{\$([aA-zZ_]*)\}#';
    const VARS      = '#(?<var>\{\$(?<dataProvider>(?:[a-zA-Z]*)(?:(?:->){1}(?:[a-zA-Z1-9]*){1})*)\})#';
    const VARS_SEPARATOR = '#\-\>#';

    const LOOP = '#(?<loop>(?<header>\[loop=\"\$(?<dataProvider>.*)\" +as +(?<key>.*)=>(?<value>.*)\])(?<body>(?:[^\[\]]++|(?R))*+)\[\/loop\])#';

    /**
     * Inclusion d'un fichier
     */
    const INCLUDER      = '#\{inc=(.*)\}#';

    /**
     * Inclusion d'un fichier template (sera parser par Parser)
     */
    const TEMPLATE      = '#\{tpl=(.*)\}#';

    /**
     * Internal tags
     */
    const PLACEHOLDER   = '{#}';

    /**
     * Dump du ViewObjectCourant
     */
    const VAR_DUMP      = '#(\{dump\})#s';

    /**
     * Un pattern PCRE à rechercher dans le template.
     * @vars string
     */
    protected $pattern = "";

    /**
     * Setter pattern PCRE à rechercher dans le template.
     * 
     * @param string $pattern Un pattern PCRE à rechercher dans le template.
     */
    public function __construct($pattern) {
        $this->pattern = $pattern;
    }

    public function getPattern(){
        return $this->pattern;
    }

}