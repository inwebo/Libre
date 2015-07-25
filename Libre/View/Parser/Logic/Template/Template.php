<?php
namespace Libre\View\Parser\Logic {
    /**
     * Class métier à appliqué sur un Tag Tpl.
     *
     * Permet d'inclure un fichier template qui sera lui même parsé.
     *
     * <code>
     * {tpl="data.tpl"}
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
    use Libre\View\Parser;
    use Libre\View\Template as Tpl;

    class Template extends Logic {

        /**
         * Inclus et Parse un fichier template.
         *
         * @param array $match Un tableau de retour de preg_match_all
         * @return string Le contenu fichier template modifié par une fonction pcre
         * @throws \Exception
         */
        public function process($match) {
            try {
                // Nouveau parser
                $_layout = new Tpl($match[1]);
                $temp = new Parser($_layout, $this->dataProvider );
            } catch (\Exception $e) {
                throw $e;
            }
            return $temp->getContent();
        }

    }
}