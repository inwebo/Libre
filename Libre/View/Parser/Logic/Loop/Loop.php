<?php
namespace Libre\View\Parser\Logic;

/**
 * Class métier à appliqué sur un Tag Loop.
 *
 * Objet métier, sera le function de callback de preg_match_all cf la class Task
 * Itére un tableau.
 * 
 * <code>
 * <ul>
 * {loop array="{$array}"}
 * <li>{$key},{$value}</li>
 * {/loop}
 * </ul>
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
use Libre\View\Parser;
use Libre\View\Template\TemplateFromString;
use Libre\View\Task;
use Libre\View\ViewObject;

class Loop extends Logic {

    protected $buffer = "";
    protected $loopInformations;

    protected function initialize( $pregReplaceCallbackResult ){

        // Informations sur la loop courante
        $loopInformationsFactory = new Loop\Informations();
        $this->loopInformations = $loopInformationsFactory->process($pregReplaceCallbackResult);

        // Pas de DataProvider pas de traitement.
        if( !isset( $this->loopInformations->dataProvider ) || empty($this->loopInformations->dataProvider) || count($this->loopInformations->dataProvider) === 0 ) {
            return $this->loopInformations->toString;
        }

    }

    /**
     * Point entrée de la classe
     * 
     * @param array $match Un tableau de retour de preg_match_all
     * @return string Le contenu fichier template modifié par une fonction pcre
     */
    public function process($match) {
        $this->initialize( $match );

        /**
         * N'est pas iterable, ne posséde donc pas de membre. donc pas de traitement retourne tag loop inchangé.
         */
        if( ! is_object($this->dataProvider) ) {
            return $this->loopInformations->toString;
        }
        /**
         * Le remplacement peut se faire.
         */
        else {
            return  $this->processLocalVars($this->loopInformations);
        }

    }

    /**
     * Remplace une loop imbriquée par un placeholder. Permet le traitement des variables du scope local.
     * @param $stringLoop
     * @return mixed Voir preg_replace
     */
    protected function obfuscateInternalLoop( $stringLoop ) {
        return preg_replace(Tag::LOOP, TAG::PLACEHOLDER , $stringLoop);
    }

    protected function iterateDataProvider( $callback, $template = null ) {
        $buffer = "";
        $member = $this->loopInformations->dataProvider;
        foreach($this->dataProvider->$member as $key => $value) {
            $buffer .= call_user_func_array($callback, array("key"=>$key,"value"=>$value, "template"=>$template));
        }
        return $buffer;
    }

    protected function simpleBodyVarsCallback($key, $value, $template=null) {
        $_return ="";

        if( is_null($template) ) {
            $buffer = $this->loopInformations->body;
        }
        else {
            $buffer = $template;
        }

        // Remplace les occurences de $key / $value
        $buffer = $this->populateLocalVars($this->loopInformations->key, $key, $buffer);
        $buffer = $this->populateLocalVars($this->loopInformations->value, $value, $buffer);
        $_return .= $buffer;

        return $_return;
    }

    protected function isValidDataProviderMember( $dataProviderMember ) {
        return ( isset( $this->dataProvider->$dataProviderMember ) );
    }

    protected function isIterableDataProviderMember($dataProviderMember){
        return is_object($dataProviderMember) || is_array($dataProviderMember);
    }

    public function processLocalVars($loopInformations) {
        // A partir d'ici nous savons que le data provider est iterable
        //var_dump($loopInformations->recursive);
        /**
         * Est ce une boucle imbriquée ?
         * N'est pas une boucle imbriquée
         */
        if( !$loopInformations->recursive ) {
            //var_dump("Is not a recursive loop");
            return $this->iterateDataProvider( array( $this, 'simpleBodyVarsCallback' ) );
        }
        /**
         * Est une boucle imbriquée
         */
        else {
            //var_dump("Is a recursive loop");
            return $this->iterateDataProvider( array( $this, 'includedBodyVarsCallBack' ) );
        }
    }

    public function includedBodyVarsCallBack($key, $value) {
        // Obfuscation de la loop interne.
        $obfuscedLoop = $this->obfuscateInternalLoop($this->loopInformations->body);

        // Traitement du template du scope global
        $result = $this->iterateDataProvider(array($this, 'simpleBodyVarsCallback'), $obfuscedLoop);


        //var_dump( $this->isIterableDataProviderMember( $value ) );

        // Est ce que $value est iterable ?
        if( $this->isIterableDataProviderMember($value) ) {
            //var_dump($result);
            // Injection dans la boucle imbriquée
            // Nouvelle boucle informations pour récupérer le dataprovider et pouvoir injecter la clef courante de

            // Boucle inclue
            preg_match(Tag::LOOP, $this->loopInformations->body, $loop);

            // Les infos de la boucle inclue
            $boucleInformations = new Loop\Informations();
            $toInjectDataProvider = $boucleInformations->process($loop);
            //var_dump($toInjectDataProvider);

            // Injection de la variable
            $injected=str_replace('$'.$boucleInformations->dataProvider,'$'.$key, $toInjectDataProvider->loop);
            $injectedHeader=str_replace('$'.$boucleInformations->dataProvider,'$'.$key, $toInjectDataProvider->header);
            $injectedDataProvider=str_replace($boucleInformations->dataProvider,$key, $toInjectDataProvider->dataProvider);
            //var_dump($injected);
            $boucleInformations->loop = $injected;
            $boucleInformations->header = $injectedHeader;
            $boucleInformations->dataProvider = $injectedDataProvider;
            // Parser la nouvelle boucle avec le dataProvider courant $value
            $template = new TemplateFromString($injected);
            //var_dump($value);
            $vo = new ViewObject();
            $vo->$key = $value;
            //var_dump($vo);
            $taskLoop = new Task( new Tag(Tag::LOOP), new Loop($vo) );
            //var_dump($boucleInformations->toArray());
            $processedLoop = $taskLoop->getLogic()->process($boucleInformations->toArray());

            // Remplacement de la boucle obfuscée.
            $a = str_replace(Tag::PLACEHOLDER, $processedLoop, $obfuscedLoop);
            //var_dump($a);
            $result = $a;
        }


        return $result;
    }

    public function populateLocalVars( $search, $replacement, $subject ) {
        if(!is_object($replacement)) {
            return  preg_replace( '#\{\$'.$search.'\}#m',$replacement, $subject );
        }
        else {
            return '';
        }

    }

}