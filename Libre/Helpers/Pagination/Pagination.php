<?php

namespace Libre\Helpers {

    /**
     * Pagination simplifiée.
     *
     * Description longue
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
    class Pagination {

        /**
         * L'objet à paginer.
         * @var mixed
         */
        public $subject;

        /**
         * Nombre de page
         * @var int
         */
        public $max;

        /**
         * Index de la première page
         * @var int
         */
        public $min;

        /**
         * Nombre d'objet par page.
         * @var int
         */
        public $limite;

        /**
         * Index courant. (page en cours)
         * @var int
         */
        public $index;

        /**
         * @param $subject
         * @param int $index
         * @param int $limit
         */
        public function __construct( $subject, $index = 1 , $limit = 25) {
            $this->subject  = $subject;
            $this->index    = $index;
            $this->limite   = (int)$limit ;
            $this->min      = $this->setMin();
            $this->max      = $this->setMax();
        }

        /**
         * Si le sujet est paginable retourne 1 sinon -1.
         * @return int
         */
        public function setMin() {
            return (!empty($this->subject)) ? 1 : -1;
        }

        /**
         * @return int
         */
        public function getMax()
        {
            return $this->max;
        }

        /**
         * @return int
         */
        public function getMin()
        {
            return $this->min;
        }

        /**
         * Si le sujet est paginable retourne le nombre de page total sinon -1.
         * @return int
         */
        public function setMax() {
            return (!empty($this->subject)) ? $this->total() : -1;
        }

        /**
         * Existe t il une page suivante.
         * @return bool True si une page suivante existe sinon false
         */
        public function next() {
            return (isset($this->chunk[$this->index+1])) ? true : false;
        }

        public function getNextIndex() {
            return ($this->next()) ? $this->next() + 1 : $this->current();
        }

        public function getPrevIndex() {
            return ($this->prev()) ? $this->prev() - 1 : $this->current();
        }

        /**
         * Existe t il une page précédente.
         * @return bool True si une page précédente existe sinon false
         */
        public function prev() {
            return (isset($this->chunk[$this->index-1])) ? true : false;
        }

        /**
         * Retourne la premiére pagination si elle existe.
         * @return mixed objet si elle existe ou null
         */
        public function first() {
            if($this->min !== -1) {
                $this->index = 1;
                return $this->chunk[1];
            }
            else {
                return null;
            }
        }

        /**
         * Retourne la dernière pagination si elle existe.
         * @return mixed objet si elle existe ou null
         */
        public function last() {
            if( $this->max !== -1 ) {
                $this->index = $this->total();
                if( isset($this->chunk) ) {
                    return $this->chunk[$this->total()];
                }
            }
        }

        /**
         * Nombre items dans la pagination courante
         * @return ints
         */
        public function count() {
            return count( (array) $this->subject );
        }

        /**
         * Nombre de page maximum.
         * @return int
         */
        public function total() {
            return (int) ceil($this->count() / $this->limite);
        }

        /**
         * Retourne une pagination à la page $number.
         *
         * @param int $number Page souhaitée.
         * @param bool $asObject Si vaut true la fonction retourne la page sous forme
         * de class standart, sinon un tableau. Par défaut false
         * @return mixed Selon la valeur de <b>$asObject</b>. Provoque une erreur si la page
         * souhaitée $number est en dehors des limites.
         */
        public function page($number, $asObject = false) {
            if( is_numeric($number) && $number <= $this->max && $number >= $this->min ) {
                $chunks = $this->chunk();
                $this->index = $number;
                return ($asObject) ? (object) $chunks[$number] : $chunks[$number];

            }
            else {
                trigger_error("Page doesnt exists");
            }

        }

        public function current() {
            return $this->page((int)$this->index, true);
        }

        /**
         * @see array_chunk()
         * @return array
         */
        protected function chunk() {
            $chunked = @array_chunk( (array) $this->subject, $this->limite, true);
            $return = array();
            if(!is_null($chunked)) {
                $j=1;
                foreach ( $chunked as $c ) {
                    $return[$j++] = $c;
                }
            }
            return $return;
        }

        /**
         * Calcul les limites de la requête sql
         *
         * <code>
         * SELECT * FROM table [LIMIT [offset,] lignes] ]
         * </code>
         *
         * @param int $items Le nombre d'items à paginés
         * @param int $limit Nombre maximum d'items par page
         * @param int $page Le numéro de page demandé
         * @return Mixed Un tableau si les limites pour le numéro de page existent.
         * sinon null
         */
        static public function sqlLimit($items, $limit, $page) {
            $totalPage= ceil($items/$limit);
            if($page > $totalPage) {
                $_limit['start'] = 1;
                $_limit['end'] = 1;
            }
            else {
                $_limit['start'] = $limit * $page - $limit;
                $_limit['end'] = $limit * $page - 1;
            }

            return $_limit;
        }

        /**
         * @param $items
         * @param int $page
         * @param int $limit
         * @return Pagination
         */
        static public function dummyPagination($items, $page = 1, $limit = 20) {
            if($items!=0) {
                $subject = array_fill(0, $items, null);
                $pagination = new Pagination($subject, $page, $limit);
                return $pagination;
            }
        }

        public function __set($attribut, $value) {
            $this->$attribut = $value;
        }

        public function __get($attribut) {
            if (property_exists($this, $attribut)) {
                return $this->$attribut;
            }
        }

        public function getInterval( $interval ) {

            $index = $this->index;
            $pages = $this->max;

            $start  = 0;
            $end    = 0;

            $plage = $interval * 2 + 1;

            if( $index - $interval > 0 && $index + $interval <= $pages ) {

                $start = $index-$interval;
                $end    = $index + $interval;
            }


            if( $index <= $pages ) {
                // Borne moins en dehors
                if( $index - $interval <= 0 ) {
                    $start = 1;
                    $end = $plage + $start;
                }
                elseif($index <= $interval ) {
                    $start = 1;
                    $end = ($interval*2+1)-($index - $interval);
                }
                else {
                    $start = $index - $interval;
                    $end = $index + $interval;
                }

                if( $index + $interval >= $pages ) {
                    $end = $pages;
                    $start = $pages-$plage;
                }

            }

            if( $index == $this->total() ) {
                $start = $end = 1;
            }

            return array($start, $end);
        }

    }
}