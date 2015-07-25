<?php
namespace Libre\View\Parser\Logic;
/**
 * Class métier à appliqué sur un Tag de variable.
 *
 * Objet métier, sera le function de callback de preg_match_all voir la class
 * Task
 * <code>
 * {@vars}
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
use Libre\View\Parser\Tag;
class Vars extends Logic {

    protected $dataProviderMember;

    protected function initialize($match) {
        $this->dataProviderMember = $match['dataProvider'];
    }

    /**
     * Setter match. Applique une class métier sur un pattern PCRE.
     * 
     * @param array $match Un tableau de retour de preg_match_all
     * @return string Le contenu fichier template modifié par une fonction pcre
     */
    public function process($match) {
        $this->initialize($match);
        $buffer = "";

        // Est ce un objet ?
        if( $this->isFlat() ) {
            $toTree = $this->split();
            if( $this->isValidMember($toTree) ) {
                //Est ce un membre valide ?
                ob_start();
                echo $this->getValidMember($toTree);
                $buffer = ob_get_contents();
                ob_end_clean();
                return $buffer;
            }
            else {
                //return $match[0];
            }
        }
        else {
            ob_start();
            if ( isset( $this->dataProvider->$match['dataProvider'] ) ) {
                echo( $this->dataProvider->$match['dataProvider'] );
            } else {
                return $match[0];
            }
            $buffer = ob_get_contents();
            ob_end_clean();
        }


        return $buffer;
    }

    protected function isValidMember($array) {
        $return = $this->dataProvider->$array[0];
        foreach($array as $value) {
            if(is_object($return)) {
                if( !isset($return->$value) ) {
                    return false;
                }
            }
            else {
                return false;
            }
        }
        return $return;
    }

    protected function getValidMember($array) {
        $return = $this->dataProvider->$array[0];
        foreach($array as $value) {
            if(is_object($return)) {
                if( isset($return->$value) ) {
                    $return = $return->$value;
                }
            }
        }
        return $return;
    }

    protected function isFlat() {
        return (bool)preg_match(Tag::VARS_SEPARATOR, $this->dataProviderMember);
    }

    protected function split(){
        return preg_split('#\-\>#',$this->dataProviderMember);
    }

    /**
     * Getter du nom de la variable php en cours.
     * 
     * @param array $match Un tableau de retour de preg_match_all
     * @return string Le nom de l'attribut de ViewBag
     */
    public function getMemberName($match) {
        return $match[1];
    }

    /**
     * Getter ViewBag.
     * 
     * @param array $match Un tableau de retour de preg_match_all
     * @return mixed La valeur de la variable en cours contenu dans le ViewBag
     */
    public function __get($match) {
        //@todo child node
        Parser::$trace[] = $match[1];
        return self::$ViewBag->$match[1];
    }

}