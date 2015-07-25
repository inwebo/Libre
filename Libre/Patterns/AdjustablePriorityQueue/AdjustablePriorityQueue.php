<?php

namespace Libre\Patterns {

    class AdjustablePriorityQueue extends \SplPriorityQueue{

        /**
         * Highest first, default behavior
         */
        const DESC  = 0;
        /**
         * Lowest first
         */
        const ASC   = 1;
        /**
         * @var int
         */
        protected $_direction;

        public function __construct($direction = self::DESC) {
            $this->_direction = $direction;
        }

        public function compare($p1, $p2) {
            if( $this->_direction === 0 ) {
                return parent::compare($p1,$p2);
            }
            else {
                return parent::compare($p2,$p1);
            }
        }

    }
}